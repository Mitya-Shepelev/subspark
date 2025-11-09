(function ($) {
    "use strict";

    let appConfig = {};

    document.addEventListener("DOMContentLoaded", function () {
        const configElement = document.getElementById("chat-config");
        if (!configElement) { 
            return;
        }
        appConfig = JSON.parse(configElement.textContent);

        initAgoraVideoCall(appConfig);
    });

    function initAgoraVideoCall(appConfig) {
        let client = null;
        const localTracks = { videoTrack: null, audioTrack: null };
        const localTrackState = { videoTrackMuted: false, audioTrackMuted: false };
        let remoteUsers = {};
        let initialized = false;
        let callActive = false; // true after successful publish, false after leave
        let intentionallyLeaving = false; // set true when user clicks Leave to block auto-rejoin

        const options = {
            appid: appConfig.agoraAppID,
            channel: appConfig.videoCall.channelName || appConfig.randomChannelName,
            uid: null,
            token: null
        };

        $(document).ready(function () {
            // Auto-join if there is an active accepted call record
            if (appConfig.videoCall.exists && appConfig.videoCall.channelName) {
                JoinVideoCall();
            }

            $(document).on("click", ".leave", leave);
            $(document).on("click", ".joinVideoCall", JoinVideoCall);
            $(document).on("click", ".crVidCall", buyVideoCall);
            // Reset flags on cancel
            $(document).on('click', '.call_decline', function(){
                try { $("#notification-sound-call")[0]?.pause(); } catch(e){}
                callCreated = false;
            });
            $(document).on("click", "#mute-audio", () => localTrackState.audioTrackMuted ? unmuteAudio() : muteAudio());
            $(document).on("click", "#mute-video", () => localTrackState.videoTrackMuted ? unmuteVideo() : muteVideo());

            typingPoll();
            setupTypingTrigger();
            scrollLoadMessages();
            getNewMessageLoop('');
        });

        let callCreated = false;
        async function JoinVideoCall() {
            try {
                if (!initialized) {
                    client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
                    client.enableAudioVolumeIndicator && client.enableAudioVolumeIndicator();
                    client.on("user-published", handleUserPublished);
                    client.on("user-unpublished", handleUserUnpublished);
                    client.on("user-left", handleUserLeft);
                    client.on("connection-state-change", onConnectionStateChange);
                    client.on("token-privilege-will-expire", onTokenWillExpire);
                    client.on("token-privilege-did-expire", onTokenDidExpire);
                    client.on && client.on("network-quality", onNetworkQuality);
                    initialized = true;
                }

                // Ensure DB call record exists before we attempt media perms/join
                const created = await ensureCallRecord();
                if (!created) {
                    return;
                }
                intentionallyLeaving = false;
                await joinAgora();
                $("#notification-sound-call")[0]?.pause();
                $(".live_pp_camera_container").show();
            } catch (e) {
                // Error suppressed intentionally for production
            } finally {
                $("#leave").attr("disabled", false);
            }
        }

        async function fetchToken(host = true){
            return new Promise((resolve) => {
                $.ajax({
                    type: 'POST',
                    url: appConfig.siteurl + 'requests/request.php',
                    dataType: 'json',
                    data: { f: 'agoraNewToken', ch: options.channel, host: host ? '1' : '0' },
                    success: function(res){
                        if(res && res.status === 'ok'){
                            resolve(res.token);
                        }else{ resolve(null); }
                    },
                    error: function(){ resolve(null); }
                });
            });
        }

        let reconnecting = false;
        async function joinAgora() {
            // always ensure we have a fresh token
            options.token = await fetchToken(true);
            [options.uid, localTracks.audioTrack, localTracks.videoTrack] = await Promise.all([
                client.join(options.appid, options.channel, options.token || null, options.uid || null),
                AgoraRTC.createMicrophoneAudioTrack(),
                AgoraRTC.createCameraVideoTrack()
            ]);

            localTracks.videoTrack.play("local-player");
            $("#local-player-name").text(`localVideo(${options.uid})`);
            await client.publish(Object.values(localTracks));
            callActive = true;

            // Already created before join
        }

        async function ensureCallRecord(){
            if (callCreated) return true;
            if (!appConfig.videoCall.exists) {
                return new Promise((resolve)=>{
                    // First time: show buy modal (paid) or direct (free)
                    if (!$('body').find('.videoCalli, .videoCall').length) {
                        // Clean any stale modals before showing a new one
                        $(".i_modal_bg_in.videoCalli, .i_modal_bg_in.videoCall").remove();
                        $.post(appConfig.siteurl + "requests/request.php", {
                            f: "buyVideoCall",
                            calledID: appConfig.conversationUserID,
                            callName: options.channel
                        }, function(response){
                            if (response && response !== '404') {
                                $("body").append(response);
                                setTimeout(() => {
                                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                                }, 200);
                                // wait for user confirm -> resolve false to stop join now
                                resolve(false);
                            } else {
                                // try direct creation (free call)
                                $.post(appConfig.siteurl + "requests/request.php", {
                                    f: "createVideoCall",
                                    calledID: appConfig.conversationUserID,
                                    callName: options.channel
                                }, function(resp2){
                                    if (resp2 && resp2 !== '404') {
                                        $(".i_modal_bg_in.videoCalli, .i_modal_bg_in.videoCall").remove();
                                        $("body").append(resp2);
                                        setTimeout(() => {
                                            $(".i_modal_bg_in").addClass('i_modal_display_in');
                                            $('#notification-sound-call')[0]?.play();
                                        }, 200);
                                        callCreated = true;
                                        resolve(true);
                                    } else {
                                        try { alert('Video call could not be started. Please check your balance or permissions.'); } catch(e){}
                                        resolve(false);
                                    }
                                }).fail(()=>resolve(false));
                            }
                        }).fail(()=>resolve(false));
                        return;
                    }
                    // Second time (after user pressed join in modal): charge and create
                    $.post(appConfig.siteurl + "requests/request.php", {
                        f: "createVideoCall",
                        calledID: appConfig.conversationUserID,
                        callName: options.channel
                    }, function(resp3){
                        if (resp3 && resp3 !== '404') {
                            $(".i_modal_bg_in.videoCalli, .i_modal_bg_in.videoCall").remove();
                            $("body").append(resp3);
                            setTimeout(() => {
                                $(".i_modal_bg_in").addClass('i_modal_display_in');
                                $('#notification-sound-call')[0]?.play();
                            }, 200);
                            callCreated = true;
                            resolve(true);
                        } else {
                            try { alert('Payment required or permission denied. Please check your balance.'); } catch(e){}
                            resolve(false);
                        }
                    }).fail(()=>resolve(false));
                });
            }
            return true;
        }

        async function leave() {
            try {
                intentionallyLeaving = true;
                for (let trackName in localTracks) {
                    const track = localTracks[trackName];
                    if (track) {
                        track.stop();
                        track.close();
                        localTracks[trackName] = null;
                    }
                }

                if (client) {
                    await client.unpublish();
                    await client.leave();
                }
                reconnecting = false;
                callActive = false;
                // Reset UI state
                localTrackState.audioTrackMuted = false;
                localTrackState.videoTrackMuted = false;
                $("#mute-audio, #mute-video").removeClass("activated_btn");
                $("#local-player .agora_video_player").css("filter", "none");

                for (let uid in remoteUsers) {
                    $(`#player-wrapper-${uid}`).remove();
                }
                remoteUsers = {};

                $("#remote-playerlist").html("");
                $("#local-player-name").text("");
                $(".live_pp_camera_container").hide();
                $("#notification-sound-call")[0]?.pause();

                $.post(appConfig.siteurl + "requests/request.php", {
                    f: "liveEnd",
                    chName: options.channel
                });
            } catch (err) {
                // Error suppressed intentionally for production
            }
        }

        // Removed old toggle that affected both audio & video at once

        function buyVideoCall() {
            $.post(appConfig.siteurl + "requests/request.php", {
                f: "buyVideoCall",
                calledID: appConfig.conversationUserID,
                callName: options.channel
            }, function (response) {
                if (response !== '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            });
        }

        async function subscribe(user, mediaType) {
            const uid = user.uid;
            await client.subscribe(user, mediaType);

            if (mediaType === 'video') {
                const player = $(`
                    <div id="player-wrapper-${uid}" class="remote-wrapper">
                        <div id="player-${uid}" class="player_friend"></div>
                        <div class="remote-status">
                            <div class="camera-off" title="Camera off" aria-label="Camera off">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#fff"><path d="M21 6.5l-5 4.5v-3c0-1.1-.9-2-2-2H7.8l9.9 9.9H19c1.1 0 2-.9 2-2v-7.4zM3.27 2L2 3.27 4.73 6H4c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h12c.23 0 .45-.05.65-.12L20.73 22 22 20.73 3.27 2z"/></svg>
                            </div>
                            <div class="audio-level" title="Audio level"><span class="bar"></span></div>
                        </div>
                    </div>
                `);
                $("#remote-playerlist").append(player);
                $(".live_pp_camera_container").show();
                $("#notification-sound-call")[0]?.pause();
                $(".videoCall").remove();
                user.videoTrack.play(`player-${uid}`);
                $(`#player-wrapper-${uid} .camera-off`).removeClass('show');
            }

            if (mediaType === 'audio') {
                user.audioTrack.play();
            }
        }

        function handleUserPublished(user, mediaType) {
            remoteUsers[user.uid] = user;
            subscribe(user, mediaType);
        }

        function handleUserUnpublished(user, mediaType) {
            const uid = user.uid;

            // If remote user stops publishing video, show camera-off overlay but keep call alive
            if (mediaType === 'video') {
                const wrapper = $(`#player-wrapper-${uid}`);
                if (wrapper.length) {
                    wrapper.find('.camera-off').addClass('show');
                    const surface = wrapper.find(`#player-${uid}`);
                    surface.children().remove();
                }
                return;
            }

            // For audio unpublish, we don't need to change UI
        }

        function handleUserLeft(user){
            const uid = user.uid;
            if ($(`#player-wrapper-${uid}`).length) {
                $(`#player-wrapper-${uid}`).remove();
            }
            if (remoteUsers[uid]) { delete remoteUsers[uid]; }
            // If the only remote user left the channel, end the call locally as well
            if (Object.keys(remoteUsers).length === 0 && callActive && !intentionallyLeaving) {
                leave();
            }
        }

        async function onTokenWillExpire(){
            if (intentionallyLeaving || !callActive) return;
            const newToken = await fetchToken(true);
            if(newToken){
                try{ await client.renewToken(newToken); options.token = newToken; }catch(e){}
            }
        }

        async function onTokenDidExpire(){
            if (intentionallyLeaving || !callActive) return;
            const newToken = await fetchToken(true);
            if(newToken){
                try{ await client.renewToken(newToken); options.token = newToken; }catch(e){ tryRejoin(); }
            }else{ tryRejoin(); }
        }

        function onNetworkQuality(stats){
            const uplink = stats.uplinkNetworkQuality || 0;
            const downlink = stats.downlinkNetworkQuality || 0;
            const q = Math.max(uplink, downlink);
            updateLocalNetQualityBadge(q);
        }

        // Volume indicator for remote users
        if (client && client.on) {
            client.on('volume-indicator', function(result){
                (result || []).forEach(function(u){
                    const uid = u.uid;
                    const vol = (typeof u.volume !== 'undefined') ? u.volume : (u.level ? u.level * 100 : 0);
                    const bar = $(`#player-wrapper-${uid} .audio-level .bar`);
                    if (bar.length) {
                        const w = Math.max(4, Math.min(100, Math.round(vol)));
                        bar.css('width', w + '%');
                    }
                });
            });
        }

        function updateLocalNetQualityBadge(q){
            let cls = 'q-unknown';
            if (q === 1) cls = 'q-good';
            else if (q === 2) cls = 'q-fair';
            else if (q === 3) cls = 'q-poor';
            else if (q === 4) cls = 'q-bad';
            else if (q === 5) cls = 'q-verybad';
            if ($('#local-net-quality').length === 0) {
                $('#local-player').append('<div id="local-net-quality" class="net-quality-badge"></div>');
            }
            $('#local-net-quality').attr('class', `net-quality-badge ${cls}`).attr('title', `Network quality: ${q}`);
        }

        let retryCount = 0; const maxRetries = 3;
        async function onConnectionStateChange(cur, prev, reason){
            if (intentionallyLeaving || !callActive) return;
            if(cur === 'DISCONNECTED' && prev !== 'DISCONNECTED' && !reconnecting){
                tryRejoin();
            }
        }

        async function tryRejoin(){
            if(reconnecting) return; reconnecting = true;
            while(retryCount < maxRetries){
                retryCount++;
                try{
                    await leave();
                    await JoinVideoCall();
                    reconnecting = false; retryCount = 0; return;
                }catch(e){
                    await new Promise(r=>setTimeout(r, 1000 * retryCount));
                }
            }
            reconnecting = false; retryCount = 0;
        }

        async function muteAudio() {
            if (!localTracks.audioTrack) return;
            await localTracks.audioTrack.setMuted(true);
            localTrackState.audioTrackMuted = true;
            $("#mute-audio").addClass("activated_btn");
        }

        async function unmuteAudio() {
            if (!localTracks.audioTrack) return;
            await localTracks.audioTrack.setMuted(false);
            localTrackState.audioTrackMuted = false;
            $("#mute-audio").removeClass("activated_btn");
        }

        async function muteVideo() {
            if (!localTracks.videoTrack) return;
            await localTracks.videoTrack.setMuted(true);
            localTrackState.videoTrackMuted = true;
            $("#mute-video").addClass("activated_btn");
            $("#local-player .agora_video_player").css("filter", "blur(5px) brightness(0)");
        }

        async function unmuteVideo() {
            if (!localTracks.videoTrack) return;
            await localTracks.videoTrack.setMuted(false);
            localTrackState.videoTrackMuted = false;
            $("#mute-video").removeClass("activated_btn");
            $("#local-player .agora_video_player").css("filter", "none");
        }

        function getNewMessageLoop(x) {
            const lastMessage = $(`.mm_${appConfig.conversationUserID}:last`);
            const lastID = lastMessage.attr("data-id");

            if (!lastID) {
                setTimeout(() => getNewMessageLoop(x), 3000);
                return;
            }

            $.ajax({
                type: 'POST',
                url: appConfig.siteurl + 'requests/request.php',
                data: {
                    f: 'getNewMessage',
                    ci: appConfig.cID,
                    to: appConfig.conversationUserID,
                    lm: lastID
                },
                success: function (response) {
                    if (response && response.trim()) {
                        $(".all_messages").stop().animate({ scrollTop: $(".all_messages")[0].scrollHeight }, 100);
                        $(".all_messages_container").append(response);
                        setTimeout(() => getNewMessageLoop(x), 300);
                    } else {
                        setTimeout(() => getNewMessageLoop(x), 5000);
                    }
                },
                error: function () {
                    setTimeout(() => getNewMessageLoop(x), 5000);
                }
            });
        }

        function typingPoll() {
            $.post(appConfig.siteurl + "requests/request.php", {
                f: "typing",
                ci: appConfig.cID,
                to: appConfig.conversationUserID
            }, function (response) {
                if (response.timeStatus) {
                    $(".c_u_time").html(response.timeStatus);
                }
                if (response.seenStatus === "1") {
                    $(".seenStatus").removeClass("notSeen").addClass("seen");
                }
                const player = $(".friendsCam .player_friend .agora_video_player");
                if (player.length > 0) {
                    player.css("filter", "none");
                    unmuteAudio();
                }
                setTimeout(typingPoll, 8000);
            }).fail(() => setTimeout(typingPoll, 8000));
        }

        function setupTypingTrigger() {
            let typingTimer;
            const doneTypingInterval = 5000;

            $("body").on("focus", ".mSize", function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    $.post(appConfig.siteurl + 'requests/request.php', {
                        f: 'utyping',
                        ci: appConfig.cID,
                        to: appConfig.conversationUserID
                    });
                }, doneTypingInterval);
            });

            $("body").on('keydown', ".mSize", function () {
                clearTimeout(typingTimer);
            });
        }

        function scrollLoadMessages() {
            const container = $(".all_messages");
            container.scrollTop(container[0].scrollHeight);

            container.on("scroll", function () {
                if (container.scrollTop() === 0 && !$(".seen_all").length) {
                    const lastID = $(".msg:first-child").data("id");
                    $.post(appConfig.siteurl + "requests/request.php", {
                        f: "moreMessage",
                        ch: appConfig.cID,
                        last: lastID
                    }, function (html) {
                        if (html.trim()) {
                            $(".all_messages_container").prepend(html);
                        } else {
                            $(".all_messages_container").prepend('<div class="seen_all flex_ tabing"><div class="nmore">No more messages</div></div>');
                        }
                    });
                }
            });
        }

        $("body").on("click", ".sendSecretMessage", function () {
            $(".i_write_secret_post_price").toggleClass("boxD");
        });
    }
     
    $(document).ready(function(){
        $(".dynamic-bg").each(function () {
          const bg = $(this).data("img");
          if (bg) {
            $(this).css("background-image", "url(" + bg + ")");
          }
        });
    }); 

})(jQuery);
