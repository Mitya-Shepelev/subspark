(function ($) {
  "use strict";

  navigator.getUserMedia =
    navigator.getUserMedia ||
    navigator.webkitGetUserMedia ||
    navigator.mozGetUserMedia ||
    navigator.msGetUserMedia;

  if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
    document.body.innerHTML = "<div class='notSupportedBox'>Sorry, your browser does not support live streaming.</div>";
  }

  const client = AgoraRTC.createClient({ mode: "live", codec: "vp8" });
  const localTracks = {
    videoTrack: null,
    audioTrack: null,
  };
  const localTrackState = {
    videoTrackMuted: false,
    audioTrackMuted: false,
  };
  const remoteUsers = {};
  const options = {
    appid: window.liveAppID || null,
    channel: window.liveChannel || null,
    uid: null,
    token: null,
    role: window.liveUserID == window.liveCreator ? "host" : "audience",
    audienceLatency: 2,
  };

  let mics = [],
    cams = [],
    currentMic,
    currentCam,
    volumeAnimation;

  $(async function () {
    $(".cam-list").on("click", "a", function () {
      switchCamera($(this).text());
    });

    $(".mic-list").on("click", "a", function () {
      switchMicrophone($(this).text());
    });

    await mediaDeviceTest();

    if (options.appid && options.channel) {
      JoinForm();
    }
  });

  async function JoinForm() {
    try {
      options.uid = "";
      options.token = "";
      await join();
    } catch (error) {
      console.error("HATA:" + error);
    } finally {
      $("#leave").attr("disabled", false);
    }
  }

  async function join() {
    if (options.role === "audience") {
      client.setClientRole(options.role, { level: options.audienceLatency });
      client.on("user-published", handleUserPublished);
      client.on("user-unpublished", handleUserUnpublished);
    } else {
      client.setClientRole(options.role);
    }

    options.uid = await client.join(
      options.appid,
      options.channel,
      options.token || null,
      options.uid || null
    );

    if (options.role === "host") {
      localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack();
      localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack();

      localTracks.videoTrack.play("local-player");
      $("#local-player-name").text(`localTrack(${options.uid})`);

      await client.publish(Object.values(localTracks));
    }

    if (!localTracks.audioTrack || !localTracks.videoTrack) {
      [localTracks.audioTrack, localTracks.videoTrack] = await Promise.all([
        AgoraRTC.createMicrophoneAudioTrack({ microphoneId: currentMic?.deviceId }),
        AgoraRTC.createCameraVideoTrack({ cameraId: currentCam?.deviceId }),
      ]);
    }
  }

  async function leave() {
    for (const trackName in localTracks) {
      const track = localTracks[trackName];
      if (track) {
        track.stop();
        track.close();
        localTracks[trackName] = undefined;
      }
    }

    remoteUsers = {};
    $("#remote-playerlist").html("");
    await client.leave();

    $("#local-player-name").text("");
    $("#leave").attr("disabled", true);
  }

  async function mediaDeviceTest() {
    [localTracks.audioTrack, localTracks.videoTrack] = await Promise.all([
      AgoraRTC.createMicrophoneAudioTrack(),
      AgoraRTC.createCameraVideoTrack(),
    ]);

    mics = await AgoraRTC.getMicrophones();
    currentMic = mics[0];
    mics.forEach((mic) => {
      $(".mic-list").append(`<a class="dropdown-item" href="#">${mic.label}</a>`);
    });

    cams = await AgoraRTC.getCameras();
    currentCam = cams[0];
    cams.forEach((cam) => {
      $(".cam-list").append(`<a class="dropdown-item" href="#">${cam.label}</a>`);
    });
  }

  async function subscribe(user, mediaType) {
    const uid = user.uid;
    await client.subscribe(user, mediaType);

    if (mediaType === "video") {
      const player = $(`
        <div id="player-wrapper-${uid}">
          <p class="player-name">remoteUser(${uid})</p>
          <div id="player-${uid}" class="player"></div>
        </div>
      `);
      $("#remote-playerlist").append(player);
      user.videoTrack.play(`player-${uid}`, { fit: "contain" });
    } else if (mediaType === "audio") {
      user.audioTrack.play();
    }
  }

  function handleUserPublished(user, mediaType) {
    remoteUsers[user.uid] = user;
    subscribe(user, mediaType);
  }

  function handleUserUnpublished(user, mediaType) {
    if (mediaType === "video") {
      delete remoteUsers[user.uid];
      $(`#player-wrapper-${user.uid}`).remove();
    }
  }

  async function switchCamera(label) {
    currentCam = cams.find((cam) => cam.label === label);
    await localTracks.videoTrack.setDevice(currentCam.deviceId);
  }

  async function switchMicrophone(label) {
    currentMic = mics.find((mic) => mic.label === label);
    await localTracks.audioTrack.setDevice(currentMic.deviceId);
  }

  async function muteAudio() {
    if (!localTracks.audioTrack) return;
    await localTracks.audioTrack.setMuted(true);
    localTrackState.audioTrackMuted = true;
    $("#mute-audio").text(window.LANG_UNMUTE_AUDIO || "Unmute Audio");
  }

  async function unmuteAudio() {
    if (!localTracks.audioTrack) return;
    await localTracks.audioTrack.setMuted(false);
    localTrackState.audioTrackMuted = false;
    $("#mute-audio").text(window.LANG_MUTE_AUDIO || "Mute Audio");
  }

  async function muteVideo() {
    if (!localTracks.videoTrack) return;
    await localTracks.videoTrack.setMuted(true);
    localTrackState.videoTrackMuted = true;
    $("#mute-video").text(window.LANG_UNMUTE_VIDEO || "Unmute Video");
  }

  async function unmuteVideo() {
    if (!localTracks.videoTrack) return;
    await localTracks.videoTrack.setMuted(false);
    localTrackState.videoTrackMuted = false;
    $("#mute-video").text(window.LANG_MUTE_VIDEO || "Mute Video");
  }

  $("body").on("click", "#leave", leave);
  $("body").on("click", "#mute-audio", function () {
    localTrackState.audioTrackMuted ? unmuteAudio() : muteAudio();
  });
  $("body").on("click", "#mute-video", function () {
    localTrackState.videoTrackMuted ? unmuteVideo() : muteVideo();
  }); 
  
  const preLoadingAnimation = '<div class="i_loading product_page_loading"><div class="dot-pulse"></div></div>';
  const loaderHTML = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">' + preLoadingAnimation + '</div></div></div>';

  function ScrollBottomLiveChat() {
    const container = $(".live_right_in_right_in");
    if (container.length) {
      container.stop().animate({ scrollTop: container[0].scrollHeight }, 100);
    }
  }

  function LiveMessage(ID, value, type) {
    const data = `f=${type}&id=${ID}&val=${encodeURIComponent(value)}`;
    $.ajax({
      type: "POST",
      url: `${window.siteurl}requests/request.php`,
      data: data,
      cache: false,
      success: function (response) {
        if (response === "404") {
          PopUPAlerts("sWrong", "ialert");
        } else {
          $(".live_right_in_right_in").append(response);
          ScrollBottomLiveChat();
        }
        $(".lmSize").val("");
        $(".Message_stickersContainer").remove();
        $(".nanos").css("height", "0px");
      }
    });
  }

  function deviceResizeFunction() {
    const vWidth = $(window).width();
    const liveWrapper = $(".live_wrapper_tik");

    if (vWidth < 1300) {
      $(".live_left").hide();
    } else {
      $(".live_left").show();
    }

    if (vWidth < 1050) {
      $(".header").hide();
      liveWrapper.css("padding-top", "0px");
      $(".live__live_video_holder").addClass("max_height_live_mobile");
      $(".live_video_header").addClass("live_video_header_mobile");
      $(".exen, .sumonline").addClass("loi");
      $(".i_header_btn_item").addClass("i_header_btn_item_live_mobile");
      $(".live_footer_holder").hide();
      $(".live_right_in_right").addClass("live_right_in_right_mobile");
      $(".live_holder_plus_in").addClass("live_plus_mobile");
      $(".live_gift_call").show();
    } else {
      $(".header").show();
      liveWrapper.css("padding-top", "72px");
      $(".live__live_video_holder").removeClass("max_height_live_mobile");
      $(".live_video_header").removeClass("live_video_header_mobile");
      $(".exen, .sumonline").removeClass("loi");
      $(".i_header_btn_item").removeClass("i_header_btn_item_live_mobile");
      $(".live_footer_holder").show();
      $(".live_right_in_right").removeClass("live_right_in_right_mobile");
      $(".live_holder_plus_in").removeClass("live_plus_mobile");
      $(".live_gift_call").hide();
      $(".live_footer_holder").removeClass("live_footer_holder_show");
      $(".appendBoxLive").remove();
    }

    if (vWidth < 700) {
      $(".mobile_footer_fixed_menu_container").remove();
    }
  }

  // Event Binding
  $(function () {
    ScrollBottomLiveChat();
    deviceResizeFunction();

    setInterval(() => {
      const data = `f=live_calcul&lid=${window.theLiveID}`;
      $.ajax({
        type: "POST",
        url: `${window.siteurl}requests/live.php`,
        dataType: "json",
        data: data,
        cache: false,
        success: function (res) {
          if (res.onlineCount) $(".sumonline").html(res.onlineCount);
          if (res.time) $(".count_time").html(res.time);
          if (res.likeCount) $(".lp_sum_l").html(res.likeCount);
          if (res.finished) window.location.href = res.finished;
        }
      });
    }, 15000);

    setInterval(() => {
      const lastCom = $(".eo2As:last").attr("id") || "";
      const data = `f=liveLastMessage&idc=${window.theLiveID}&lc=${lastCom}`;
      $.ajax({
        type: "POST",
        url: `${window.siteurl}requests/live.php`,
        data: data,
        cache: false,
        success: function (res) {
          if ($(".gElp9").length === 0) {
            $(".live_right_in_right_in").append(res);
          } else {
            $(`.cUq_${lastCom}`).after(res);
          }
          ScrollBottomLiveChat();
        }
      });
    }, 6000);

    $("body").on("click", ".getMEmojisa", function () {
      const type = "memoji";
      const ID = $(this).data("type");
      const data = `f=${type}&id=${ID}`;

      if (!$(".Message_stickersContainer").length) {
        $.ajax({
          type: "POST",
          url: `${window.siteurl}requests/request.php`,
          data: data,
          beforeSend: function () {
            $(".getMEmojisa").css("pointer-events", "none");
            $(".nanos").append(`<div class="preLoadC">${loaderHTML}</div>`);
            $(".nanos").css("height", "348px");
          },
          success: function (res) {
            $(".nanos").append(res);
            $(".preLoadC").remove();
            $(".getMEmojisa").css("pointer-events", "auto");
          }
        });
      } else {
        $(".Message_stickersContainer").remove();
        $(".nanos").css("height", "0px");
      }
    });

    $("body").on("click", ".livesendmes", function () {
      const value = $(".lmSize").val();
      if ($.trim(value).length !== 0) {
        LiveMessage(window.theLiveID, value, "livemessage");
      } else {
        $(this).attr("value", "");
      }
    });

    $(document).on("keydown", ".lmSize", function (e) {
      if ((e.which || e.keyCode) === 13) {
        const value = $(this).val();
        if ($.trim(value).length !== 0) {
          LiveMessage(window.theLiveID, value, "livemessage");
        }
      }
    });

    $("body").on("click", ".emoji_item_m", function () {
      const copyEmoji = $(this).data("emoji");
      const getValue = $(".lmSize").val();
      $(".lmSize").val(`${getValue} ${copyEmoji} `);
    });

    $("body").on("click", ".live_gift_call", function () {
      $(".live_footer_holder").addClass("live_footer_holder_show");
      $(".live__live_video_holder").append("<div class='appendBoxLive'></div>");
    });

    $("body").on("click", ".appendBoxLive", function () {
      $(".live_footer_holder").removeClass("live_footer_holder_show");
      $(".appendBoxLive").remove();
    });

    $("body").on("click", ".camera_chs", function () {
      $(".camList").toggleClass("camListOpen");
    });

    $("body").on("click", ".mick_chs", function () {
      $(".micList").toggleClass("camListOpen");
    });

    $("body").on("mouseup touchend", function (e) {
      if (!$(".camList, .micList").is(e.target) && $(".camList, .micList").has(e.target).length === 0) {
        $(".camList, .micList").removeClass("camListOpen");
      }
    });

    $(window).on("resize", deviceResizeFunction);

    if (window.liveUserID == window.liveCreator) {
      $("body").on("click", ".camcloseCall", function () {
        const ID = $(this).attr("id");
        const data = `f=finishLiveStreaming&id=${ID}`;
        $.ajax({
          type: "POST",
          url: `${window.siteurl}requests/request.php`,
          data: data,
          success: function (res) {
            if (res != "404") {
              $("body").append(res);
              setTimeout(() => {
                $(".i_modal_bg_in").addClass("i_modal_display_in");
              }, 200);
            } else {
              PopUPAlerts("sWrong", "ialert");
            }
          }
        });
      });

      $("body").on("click", ".camclose", function () {
        const data = `f=finishLive&lid=${window.theLiveID}`;
        $.ajax({
          type: "POST",
          url: `${window.siteurl}requests/request.php`,
          data: data,
          success: function (res) {
            leave();
            if (res === "finished") {
              setTimeout(() => {
                window.location.href = window.siteurl;
              }, 2000);
            }
          }
        });
      });
    }
  });
})(jQuery);