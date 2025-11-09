(function($) {
  "use strict";

  let client = null;
  let localTracks = {
    videoTrack: null,
    audioTrack: null
  };
  let localTrackState = {
    videoTrackMuted: false,
    audioTrackMuted: false
  };
  let remoteUsers = {};
  let options = {
    appid: window.liveAppID || null,
    channel: window.liveChannel || null,
    uid: null,
    token: '',
    role: (window.liveUserID == window.liveCreator) ? 'host' : 'audience',
    audienceLatency: 2
  };
  let mics = [], cams = [], currentMic, currentCam;

  async function join() {
    client = AgoraRTC.createClient({ mode: "live", codec: "vp8" });
    if (options.role === "audience") {
      client.setClientRole(options.role, { level: options.audienceLatency });
      client.on("user-published", handleUserPublished);
      client.on("user-unpublished", handleUserUnpublished);
    } else {
      client.setClientRole(options.role);
    }

    options.uid = await client.join(options.appid, options.channel, options.token || null, options.uid || null);

    if (options.role === "host") {
      [localTracks.audioTrack, localTracks.videoTrack] = await Promise.all([
        AgoraRTC.createMicrophoneAudioTrack(),
        AgoraRTC.createCameraVideoTrack()
      ]);

      localTracks.videoTrack.play("local-player");
      await client.publish(Object.values(localTracks));
    }
  }

  async function leave() {
    for (const trackName in localTracks) {
      const track = localTracks[trackName];
      if (track) {
        track.stop();
        track.close();
        localTracks[trackName] = null;
      }
    }
    remoteUsers = {};
    $("#remote-playerlist").html("");
    await client.leave();
  }

  async function muteAudio() {
    if (!localTracks.audioTrack) return;
    await localTracks.audioTrack.setMuted(true);
    localTrackState.audioTrackMuted = true;
    $("#mute-audio").text("Unmute Audio");
  }

  async function unmuteAudio() {
    if (!localTracks.audioTrack) return;
    await localTracks.audioTrack.setMuted(false);
    localTrackState.audioTrackMuted = false;
    $("#mute-audio").text("Mute Audio");
  }

  async function muteVideo() {
    if (!localTracks.videoTrack) return;
    await localTracks.videoTrack.setMuted(true);
    localTrackState.videoTrackMuted = true;
    $("#mute-video").text("Unmute Video");
  }

  async function unmuteVideo() {
    if (!localTracks.videoTrack) return;
    await localTracks.videoTrack.setMuted(false);
    localTrackState.videoTrackMuted = false;
    $("#mute-video").text("Mute Video");
  }

  async function switchCamera(label) {
    currentCam = cams.find(cam => cam.label === label);
    await localTracks.videoTrack.setDevice(currentCam.deviceId);
  }

  async function switchMicrophone(label) {
    currentMic = mics.find(mic => mic.label === label);
    await localTracks.audioTrack.setDevice(currentMic.deviceId);
  }

  async function mediaDeviceTest() {
    [localTracks.audioTrack, localTracks.videoTrack] = await Promise.all([
      AgoraRTC.createMicrophoneAudioTrack(),
      AgoraRTC.createCameraVideoTrack()
    ]);

    mics = await AgoraRTC.getMicrophones();
    currentMic = mics[0];
    mics.forEach(mic => {
      $(".mic-list").append(`<a class="dropdown-item" href="#">${mic.label}</a>`);
    });

    cams = await AgoraRTC.getCameras();
    currentCam = cams[0];
    cams.forEach(cam => {
      $(".cam-list").append(`<a class="dropdown-item" href="#">${cam.label}</a>`);
    });
  }

  async function subscribe(user, mediaType) {
    const uid = user.uid;
    await client.subscribe(user, mediaType);
    if (mediaType === 'video') {
      const player = $(`
        <div id="player-wrapper-${uid}">
          <p class="player-name">remoteUser(${uid})</p>
          <div id="player-${uid}" class="player"></div>
        </div>
      `);
      $("#remote-playerlist").append(player);
      user.videoTrack.play(`player-${uid}`, { fit: "contain" });
    }
    if (mediaType === 'audio') {
      user.audioTrack.play();
    }
  }

  function handleUserPublished(user, mediaType) {
    const id = user.uid;
    remoteUsers[id] = user;
    subscribe(user, mediaType);
  }

  function handleUserUnpublished(user, mediaType) {
    if (mediaType === 'video') {
      const id = user.uid;
      delete remoteUsers[id];
      $(`#player-wrapper-${id}`).remove();
    }
  }

  function ScrollBottomLiveChat() {
    const box = $(".live_right_in_right_in");
    if (box.length > 0) {
      box.stop().animate({ scrollTop: box[0].scrollHeight }, 100);
    }
  }

  $(document).ready(function () {
    if (options.role === "host") {
      mediaDeviceTest();
    }
    join();

    $("body").on("click", "#leave", leave);
    $("body").on("click", "#mute-audio", function () {
      localTrackState.audioTrackMuted ? unmuteAudio() : muteAudio();
    });
    $("body").on("click", "#mute-video", function () {
      localTrackState.videoTrackMuted ? unmuteVideo() : muteVideo();
    });

    $("body").on("click", ".camera_chs", function () {
      $(".camList").toggleClass("camListOpen");
    });

    $("body").on("click", ".mick_chs", function () {
      $(".micList").toggleClass("camListOpen");
    });

    $("body").on("mouseup touchend", function (e) {
      const listCont = $('.camList , .micList');
      if (!listCont.is(e.target) && listCont.has(e.target).length === 0) {
        listCont.removeClass('camListOpen');
      }
    });

    // Online count + likes + time update
    setInterval(function () {
      $.ajax({
        type: "POST",
        url: window.siteurl + "requests/live.php",
        data: { f: 'live_calcul', lid: window.theLiveID },
        dataType: "json",
        success: function (res) {
          if (res.onlineCount) $(".sumonline").html(res.onlineCount);
          if (res.time) $(".count_time").html(res.time);
          if (res.likeCount) $(".lp_sum_l").html(res.likeCount);
          if (res.finished) window.location.href = res.finished;
        }
      });
    }, 15000);

    // New chat messages
    setInterval(function () {
      const lastCom = $(".eo2As:last").attr("id") || '';
      const postData = {
        f: 'liveLastMessage',
        idc: window.theLiveID,
        lc: lastCom
      };
    
      $.post(window.siteurl + "requests/live.php", postData, function (response) {
        if (!response || response.trim() === '' || response.includes('no new live messages')) {
          return; // boş cevapta işlem yapma
        }
    
        if ($('.gElp9').length === 0) { 
          $(".live_right_in_right_in").append(response);
        } else { 
          $(".cUq_" + lastCom).after(response);
        }
      });
    }, 6000);

    // Send message
    $("body").on("click", ".livesendmes", function () {
      const value = $(".lmSize").val();
      if (value.trim()) {
        LiveMessage(window.theLiveID, value, 'livemessage');
      }
    });

    $(document).on('keydown', ".lmSize", function (e) {
      if (e.which === 13 && $(this).val().trim()) {
        LiveMessage(window.theLiveID, $(this).val(), 'livemessage');
        e.preventDefault();
      }
    });

    function LiveMessage(ID, value, type) {
      $.post(window.siteurl + 'requests/request.php', {
        f: type,
        id: ID,
        val: encodeURIComponent(value)
      }, function (response) {
        if (response !== '404') {
          $(".live_right_in_right_in").append(response);
          ScrollBottomLiveChat();
        }
        $(".lmSize").val('');
        $(".Message_stickersContainer").remove();
        $(".nanos").css('height', '0px');
      });
    }

    // Emoji
    $("body").on("click", ".getMEmojisa", function () {
      if (!$(".Message_stickersContainer").length) {
        $.post(window.siteurl + 'requests/request.php', {
          f: 'memoji',
          id: $(this).data("type")
        }, function (res) {
          $(".nanos").css('height', '348px').append(res);
        });
      } else {
        $(".Message_stickersContainer").remove();
        $(".nanos").css('height', '0px');
      }
    });

    $("body").on("click", ".emoji_item_m", function () {
      const emoji = $(this).data("emoji");
      const val = $(".lmSize").val();
      $(".lmSize").val(val + ' ' + emoji + ' ');
    });

    // Gift panel toggle
    $("body").on("click", ".live_gift_call", function () {
      $(".live_footer_holder").addClass("live_footer_holder_show");
      $(".live__live_video_holder").append("<div class='appendBoxLive'></div>");
    });

    $("body").on("click", ".appendBoxLive", function () {
      $(".live_footer_holder").removeClass("live_footer_holder_show");
      $(this).remove();
    });
    
    $("body").on("click", ".camcloseCall", function() {
        var type = 'finishLiveStreaming';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    
    $("body").on("click", ".camclose", function() {
        var type = 'finishLive';
        var liveID = window.theLiveID;
        var data = 'f=' + type + '&lid=' + liveID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            success: function(response) {
                leave();
                if (response == 'finished') {
                    setTimeout(() => {
                        window.location.href = siteurl;
                    }, 2000);
                }
            }
        });
    });
    
    if (window.liveUserID === window.liveCreator) {
        $("body").on("click", ".camera_close", function () {
            const data = {
                f: 'finishLive',
                lid: window.theLiveID
            };
            $.ajax({
                type: 'POST',
                url: window.siteurl + 'requests/request.php',
                data: data,
                success: function (response) {
                    if (response === 'finished') {
                        window.location.href = window.siteurl;
                    }
                }
            });
        });
    }

    // Responsive
    function deviceResizeFunction() {
      const vW = $(window).width();
      $(".live_left").toggle(vW >= 1300);
      $(".header").toggle(vW >= 1050);
      $(".live_wrapper_tik").css("padding-top", vW < 1050 ? "0px" : "72px");
      $(".live__live_video_holder").toggleClass("max_height_live_mobile", vW < 1050);
      $(".live_video_header").toggleClass("live_video_header_mobile", vW < 1050);
      $(".exen, .sumonline").toggleClass("loi", vW < 1050);
      $(".i_header_btn_item").toggleClass("i_header_btn_item_live_mobile", vW < 1050);
      $(".live_footer_holder").toggle(vW >= 1050);
      $(".live_right_in_right").toggleClass("live_right_in_right_mobile", vW < 1050);
      $(".live_holder_plus_in").toggleClass("live_plus_mobile", vW < 1050);
      $(".live_gift_call").toggle(vW < 1050);
      if (vW < 700) $(".mobile_footer_fixed_menu_container").remove();
    }

    $(window).on("resize", deviceResizeFunction);
    deviceResizeFunction();
    ScrollBottomLiveChat();
  });
})(jQuery);