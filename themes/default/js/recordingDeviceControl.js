(function ($) {
  "use strict";

  var client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
  var localTracks = {
    videoTrack: null,
    audioTrack: null
  };
  var remoteUsers = {};
  var options = {
    appid: null,
    channel: null,
    uid: null,
    token: null
  };

  var mics = [], cams = [], currentMic, currentCam;
  var volumeAnimation;

  $(async function () {
    $(".cam-list").on("click", "a", function () {
      switchCamera($(this).text());
    });

    $(".mic-list").on("click", "a", function () {
      switchMicrophone($(this).text());
    });

    var urlParams = new URL(location.href).searchParams;
    options.appid = urlParams.get("appid");
    options.channel = urlParams.get("channel");
    options.token = urlParams.get("token");

    await mediaDeviceTest();
    volumeAnimation = requestAnimationFrame(setVolumeWave);
  });

  $("#leave").on("click", function () {
    leave();
  });

  $("#media-device-test").on("hidden.bs.modal", function () {
    cancelAnimationFrame(volumeAnimation);
    if (options.appid && options.channel) {
      $("#appid").val(options.appid);
      $("#token").val(options.token);
      $("#channel").val(options.channel);
      $("#join-form").trigger("submit");
    }
  });

  async function join() {
    client.on("user-published", handleUserPublished);
    client.on("user-unpublished", handleUserUnpublished);

    options.uid = await client.join(options.appid, options.channel, options.token || null);

    if (!localTracks.audioTrack || !localTracks.videoTrack) {
      [localTracks.audioTrack, localTracks.videoTrack] = await Promise.all([
        AgoraRTC.createMicrophoneAudioTrack({ microphoneId: currentMic?.deviceId }),
        AgoraRTC.createCameraVideoTrack({ cameraId: currentCam?.deviceId })
      ]);
    }

    localTracks.videoTrack.play("local-player");
    $("#local-player-name").text("localVideo(" + options.uid + ")");

    await client.publish(Object.values(localTracks)); 
  }

  async function mediaDeviceTest() {
    [localTracks.audioTrack, localTracks.videoTrack] = await Promise.all([
      AgoraRTC.createMicrophoneAudioTrack(),
      AgoraRTC.createCameraVideoTrack()
    ]);

    localTracks.videoTrack.play("pre-local-player");

    mics = await AgoraRTC.getMicrophones();
    currentMic = mics[0];
    $(".mic-input").val(currentMic.label);
    $(".mic-list").empty();
    mics.forEach(mic => {
      $(".mic-list").append('<a class="dropdown-item" href="#">' + mic.label + '</a>');
    });

    cams = await AgoraRTC.getCameras();
    currentCam = cams[0];
    $(".cam-input").val(currentCam.label);
    $(".cam-list").empty();
    cams.forEach(cam => {
      $(".cam-list").append('<a class="dropdown-item" href="#">' + cam.label + '</a>');
    });
  }

  async function leave() {
    for (const trackName in localTracks) {
      if (localTracks[trackName]) {
        localTracks[trackName].stop();
        localTracks[trackName].close();
        localTracks[trackName] = undefined;
      }
    }

    remoteUsers = {};
    $("#remote-playerlist").html("");
    await client.leave();

    $("#local-player-name").text("");
    $("#join").prop("disabled", false);
    $("#leave").prop("disabled", true);
    $("#device-wrapper").hide(); 
  }

  async function subscribe(user, mediaType) {
    const uid = user.uid;
    await client.subscribe(user, mediaType); 

    if (mediaType === "video") {
      const playerHtml = `
        <div id="player-wrapper-${uid}">
          <p class="player-name">remoteUser(${uid})</p>
          <div id="player-${uid}" class="player"></div>
        </div>`;
      $("#remote-playerlist").append(playerHtml);
      user.videoTrack.play("player-" + uid);
    }

    if (mediaType === "audio") {
      user.audioTrack.play();
    }
  }

  function handleUserPublished(user, mediaType) {
    remoteUsers[user.uid] = user;
    subscribe(user, mediaType);
  }

  function handleUserUnpublished(user) {
    delete remoteUsers[user.uid];
    $("#player-wrapper-" + user.uid).remove();
  }

  async function switchCamera(label) {
    currentCam = cams.find(cam => cam.label === label);
    if (currentCam) {
      $(".cam-input").val(currentCam.label);
      await localTracks.videoTrack.setDevice(currentCam.deviceId);
    }
  }

  async function switchMicrophone(label) {
    currentMic = mics.find(mic => mic.label === label);
    if (currentMic) {
      $(".mic-input").val(currentMic.label);
      await localTracks.audioTrack.setDevice(currentMic.deviceId);
    }
  }

  function setVolumeWave() {
    if (localTracks.audioTrack) {
      const level = localTracks.audioTrack.getVolumeLevel() * 100;
      $(".progress-bar")
        .css("width", level + "%")
        .attr("aria-valuenow", level);
    }
    volumeAnimation = requestAnimationFrame(setVolumeWave);
  }

})(jQuery);