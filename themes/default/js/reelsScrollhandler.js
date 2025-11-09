'use strict';

const reelsContainer = document.getElementById('reelsContainer');
let reels = Array.from(document.querySelectorAll('.reel'));
let videos = Array.from(document.querySelectorAll('video'));
videos = videos.filter((v) => v !== null && typeof v.play === 'function');

let currentIndex = 0;
let isAnimating = false;
let scrollLock = false; // blocks input while animating to prevent stutter
const delay = 500; // base animation delay
const INPUT_LOCK_BUFFER = 120; // extra ms to ensure CSS animations finish

function playVideoSafely(video) {
  const playPromise = video.play();
  if (playPromise !== undefined) {
    playPromise
      .then(() => {})
      .catch((error) => {
        // Autoplay blocked or other error; ignore silently to avoid jank
      });
  }
}

function formatTime(time) {
  const mins = Math.floor(time / 60);
  const secs = Math.floor(time % 60);
  return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
}

function setActiveReel(index) {
  reels.forEach((reel, i) => {
    reel.classList.remove(
      'active',
      'fadeOutUp',
      'fadeOutDown',
      'fadeInUp',
      'fadeInDown',
    );
    if (i === index) {
      reel.classList.add('active');

      const video = reel.querySelector('video');
      const loader = reel.querySelector('.video-loader');

      if (loader) {
        loader.style.display = 'block';
      }

      bindVideoEvents(video, reel, i);

      if (video && video.readyState >= 3 && loader) {
        loader.style.display = 'none';
      }

      if (video) {
        bindVideoEvents(video, reel, i);

        if (video.readyState >= 3 && loader) {
          loader.style.display = 'none';
        }

        playVideoSafely(video);
      }
    } else {
      const otherVideo = reel.querySelector('video');
      if (otherVideo) {
        otherVideo.pause();
      }
    }
  });
  currentIndex = index;
  reels = Array.from(document.querySelectorAll('.reel'));
  currentIndex = reels.findIndex((r) => r.classList.contains('active'));
}

function showOverlay(overlay, type) {
  if (!overlay) return;
  overlay.classList.remove('show', 'playing', 'paused');
  overlay.classList.add(type, 'show');
  setTimeout(() => overlay.classList.remove('show'), 700);
}

function animateScroll(direction) {
  if (isAnimating) return;

  reels = Array.from(document.querySelectorAll('.reel'));
  videos = Array.from(document.querySelectorAll('video'));

  const nextIndex = currentIndex + direction;

  if (nextIndex < 0) return;

  if (direction === 1 && nextIndex >= reels.length) {
    if (noMoreReels) {
      isAnimating = false;

      const msg = document.getElementById('noMoreContentMessage');
      if (msg) {
        msg.style.display = 'block';
        setTimeout(() => {
          msg.style.display = 'none';
        }, 3000);
      }

      return;
    }

    scrollLock = true;
    loadNextReel(() => {
      reels = Array.from(document.querySelectorAll('.reel'));
      videos = Array.from(document.querySelectorAll('video'));

      if (reels.length > currentIndex + 1) {
        const activeReel = reels[currentIndex];
        const nextReel = reels[currentIndex + 1];

        activeReel.classList.remove('active');
        activeReel.classList.add('fadeOutUp');
        nextReel.classList.add('fadeInUp');

        setTimeout(() => {
          setActiveReel(currentIndex + 1);
          isAnimating = false;
          setTimeout(() => (scrollLock = false), INPUT_LOCK_BUFFER);
        }, delay);
      } else {
        isAnimating = false;
        setTimeout(() => (scrollLock = false), INPUT_LOCK_BUFFER);
      }
    });

    return;
  }

  if (nextIndex >= 0 && nextIndex < reels.length) {
    isAnimating = true;
    scrollLock = true;

    const activeReel = reels[currentIndex];
    const nextReel = reels[nextIndex];

    activeReel.classList.remove('active');

    if (direction === 1) {
      activeReel.classList.add('fadeOutUp');
      nextReel.classList.add('fadeInUp');
    } else {
      activeReel.classList.add('fadeOutDown');
      nextReel.classList.add('fadeInDown');
    }

    setTimeout(() => {
      setActiveReel(nextIndex);
      isAnimating = false;
      // allow a tiny buffer past CSS animation to avoid double triggers
      setTimeout(() => (scrollLock = false), INPUT_LOCK_BUFFER);
    }, delay);
  }
}

function seekVideo(seconds) {
  const reel = reels[currentIndex];
  const video = reel ? reel.querySelector('video') : null;
  if (!video) return;
  video.currentTime = Math.min(
    Math.max(0, video.currentTime + seconds),
    video.duration || video.currentTime,
  );

  const feedback = document.getElementById('seekFeedback');
  if (!feedback) return;
  feedback.textContent =
    seconds > 0 ? `⏩ +${seconds}s` : `⏪ ${Math.abs(seconds)}s`;
  feedback.classList.add('show');
  setTimeout(() => feedback.classList.remove('show'), 700);
}

let noMoreReels = false;

function loadNextReel(callback) {
  if (noMoreReels) return;

  const loadedIds = reels.map((r) => parseInt(r.dataset.reelId));
  const lastId = Math.min(...loadedIds);

  const xhr = new XMLHttpRequest();
  xhr.open('POST', '/requests/load-next-reel.php');
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function () {
    if (xhr.status === 200) {
      const response = xhr.responseText.trim();

      if (!response) {
        noMoreReels = true;

        const msg = document.getElementById('noMoreContentMessage');
        if (msg) {
          msg.classList.add('show');
          setTimeout(() => {
            msg.classList.remove('show');
            setTimeout(() => (msg.style.display = 'none'), 500);
          }, 3000);
        }

        return;
      }

      const temp = document.createElement('div');
      temp.innerHTML = response;

      const newReels = temp.querySelectorAll('.reel');
      if (newReels.length > 0) {
        newReels.forEach((newReel, i) => {
          const newId = parseInt(newReel.dataset.reelId);
          if (!loadedIds.includes(newId)) {
            const index = reels.length;
            newReel.setAttribute('data-index', index);
            reelsContainer.appendChild(newReel);

            const video = newReel.querySelector('video');
            if (video && typeof video.load === 'function') {
              video.load();
              bindVideoEvents(video, newReel, index);
            }
          }
        });

        reels = Array.from(document.querySelectorAll('.reel'));
        videos = Array.from(document.querySelectorAll('video'));

        currentIndex = reels.findIndex((r) => r.classList.contains('active'));
      }
      if (typeof initExpandableDescriptions === 'function') {
        initExpandableDescriptions();
      }
    }

    if (typeof callback === 'function') {
      callback();
    }
  };

  xhr.send('last_id=' + encodeURIComponent(lastId));
}

function bindVideoEvents(video, reel, i) {
  if (!video || video.bound) return;
  video.bound = true;

  const overlay = reel.querySelector('.video-overlay');
  const progressFillEl = reel.querySelector('.progress-fill');
  const timeDisplay = reel.querySelector('.time-display');
  const playToggle = reel.querySelector('.video-play-toggle');
  const volumeIcon = reel.querySelector('.volume-control');
  const loader = reel.querySelector('.video-loader');
  const dotEl = reel.querySelector('.progress-dot');

  video.addEventListener('loadstart', () => {
    if (loader) loader.style.display = 'block';
  });

  video.addEventListener('canplay', () => {
    if (loader) loader.style.display = 'none';
  });

  video.addEventListener('playing', () => {
    if (loader) loader.style.display = 'none';
  });

  video.addEventListener('error', () => {
    if (loader) loader.style.display = 'none';
  });

  video.addEventListener('loadedmetadata', () => {
    const duration = video.duration;
    if (timeDisplay) {
      timeDisplay.textContent = `0:00 / ${formatTime(duration)}`;
    }
  });

  video.addEventListener('loadeddata', () => {
    playVideoSafely(video);
    if (loader) loader.style.display = 'none';
    if (playToggle) {
      playToggle.classList.remove('play', 'pause');
      playToggle.classList.add(video.paused ? 'play' : 'pause');
    }
  });

  video.addEventListener('click', () => {
    if (video.muted) {
      video.muted = false;
      video.volume = 1;
    }
    if (video.paused) {
      playVideoSafely(video);
      showOverlay(overlay, 'playing');
    } else {
      video.pause();
      showOverlay(overlay, 'paused');
    }
  });

  video.addEventListener('timeupdate', () => {
    if (video.__progressRAF) return;
    video.__progressRAF = requestAnimationFrame(() => {
      const current = video.currentTime;
      const total = video.duration || 0;
      const percent = total ? (current / total) * 100 : 0;

      if (progressFillEl) {
        progressFillEl.style.width = `${percent}%`;
      }

      if (dotEl) {
        dotEl.style.left = `${percent}%`;
      }

      if (timeDisplay) {
        timeDisplay.textContent = `${formatTime(current)} / ${formatTime(total)}`;
      }
      video.__progressRAF = null;
    });
  });

  if (playToggle) {
    playToggle.addEventListener('click', () => {
      if (video.paused) {
        video.play();
        playToggle.classList.remove('play');
        playToggle.classList.add('pause');
      } else {
        video.pause();
        playToggle.classList.remove('pause');
        playToggle.classList.add('play');
      }
    });
  }

  if (volumeIcon) {
    volumeIcon.addEventListener('click', function () {
      video.muted = !video.muted;
      if (!video.muted) video.volume = 1;

      volumeIcon.setAttribute('data-muted', video.muted);

      volumeIcon.innerHTML = video.muted
        ? `<svg xmlns="http://www.w3.org/2000/svg" fill="#fff" viewBox="0 0 24 24" width="24" height="24"><path d="M14.6,4.6c-0.9-0.6-2.1-0.8-3.1-0.4c-1.1,0.4-2.1,0.9-2.9,1.6L6.2,7.6H5c-1.7,0-3,1.3-3,3v2.9c0,1.7,1.3,3,3,3h1.2 l2.3,1.8c0.9,0.7,1.9,1.2,2.9,1.6c0.4,0.1,0.8,0.2,1.2,0.2c0.7,0,1.4-0.2,2-0.6c0.9-0.6,1.4-1.6,1.4-2.7V7.3 C16,6.2,15.5,5.2,14.6,4.6z M5.5,14.4H5c-0.6,0-1-0.4-1-1v-2.9c0-0.6,0.4-1,1-1h0.5V14.4z M14,16.7c0,0.4-0.2,0.8-0.6,1 c-0.4,0.3-0.9,0.3-1.3,0.2c-0.9-0.3-1.7-0.8-2.4-1.3l-2.2-1.7V9.1l2.2-1.7c0.7-0.6,1.5-1,2.4-1.3c0.4-0.2,0.9-0.1,1.3,0.2 c0.3,0.2,0.6,0.6,0.6,1V16.7z"/><path d="M21.4,12l0.7-0.7c0.4-0.4,0.4-1,0-1.4s-1-0.4-1.4,0L20,10.6l-0.7-0.7c-0.4-0.4-1-0.4-1.4,0s-0.4,1,0,1.4l0.7,0.7l-0.7,0.7 c-0.4,0.4-0.4,1,0,1.4c0.2,0.2,0.5,0.3,0.7,0.3s0.5-0.1,0.7-0.3l0.7-0.7l0.7,0.7c0.2,0.2,0.5,0.3,0.7,0.3s0.5-0.1,0.7-0.3 c0.4-0.4,0.4-1,0-1.4L21.4,12z"/></svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" fill="#fff" viewBox="0 0 24 24" width="24" height="24"><path d="M3,13.4c0,1.7,1.3,3,3,3h1.2l2.3,1.8c0.9,0.7,1.9,1.2,2.9,1.6c0.4,0.1,0.8,0.2,1.2,0.2c0.7,0,1.4-0.2,2-0.6 c0.9-0.6,1.4-1.6,1.4-2.7V7.3c0-1.1-0.5-2-1.4-2.7c-0.9-0.6-2.1-0.8-3.1-0.4c-1.1,0.4-2.1,0.9-2.9,1.6L7.2,7.6H6c-1.7,0-3,1.3-3,3 V13.4z M8.5,9.1l2.2-1.7c0.7-0.6,1.5-1,2.4-1.3c0.4-0.2,0.9-0.1,1.3,0.2c0.3,0.2,0.5,0.6,0.5,1v9.4c0,0.4-0.2,0.8-0.6,1 c-0.4,0.3-0.9,0.3-1.3,0.2c-0.9-0.3-1.7-0.8-2.4-1.3l-2.2-1.7V9.1z M5,10.6c0-0.6,0.4-1,1-1h0.5v4.9H6c-0.6,0-1-0.4-1-1V10.6z"/><path d="M19.7,9.3c-0.4-0.4-1-0.4-1.4,0c-0.4,0.4-0.4,1,0,1.4c0.3,0.3,0.5,0.8,0.5,1.3s-0.2,1-0.5,1.3c-0.4,0.4-0.4,1,0,1.4 c0.2,0.2,0.4,0.3,0.7,0.3c0.3,0,0.5-0.1,0.7-0.3c0.7-0.7,1-1.6,1-2.7S20.4,10,19.7,9.3z"/></svg>`;
    });
  }
  const progressBarEl = reel.querySelector('.progress-bar');
  if (progressBarEl) {
    progressBarEl.addEventListener('click', function (e) {
      const rect = progressBarEl.getBoundingClientRect();
      const percent = (e.clientX - rect.left) / rect.width;
      const newTime = percent * video.duration;
      video.currentTime = newTime;
    });
  }
}

document.addEventListener('visibilitychange', function () {
  if (document.visibilityState === 'visible') {
    const reel = reels[currentIndex];
    const video = reel ? reel.querySelector('video') : null;
    if (video) playVideoSafely(video);
  }
});

// Smooth, debounced mouse wheel handler (works well for trackpads & mice)
let wheelAccum = 0;
let wheelRaf = null;
const WHEEL_THRESHOLD = 100; // pixels; adjust for sensitivity

window.addEventListener(
  'wheel',
  (e) => {
    if (scrollLock || isAnimating) return;
    wheelAccum += e.deltaY;
    if (wheelRaf) return;
    wheelRaf = requestAnimationFrame(() => {
      const acc = wheelAccum;
      wheelAccum = 0;
      wheelRaf = null;
      if (Math.abs(acc) < WHEEL_THRESHOLD) return;
      if (acc > 0) {
        animateScroll(1);
      } else {
        animateScroll(-1);
      }
    });
  },
  { passive: true },
);

document.getElementById('scrollUpBtn').addEventListener('click', () => {
  animateScroll(-1);
});

document.getElementById('scrollDownBtn').addEventListener('click', () => {
  animateScroll(1);
});

$(document).on('click', '.in_comment', function () {
  const postID = $(this).attr('id');
  const wrapper = document.querySelector(`.this_reels_${postID}`);
  const commentsContainer = wrapper.querySelector(`#i_user_comments_${postID}`);

  if (wrapper) {
    wrapper.classList.remove('animateOut');
    wrapper.style.display = 'flex';

    commentsContainer.innerHTML =
      '<div class="spinner_out"><div class="spinner"></div></div>';

    requestAnimationFrame(() => {
      wrapper.classList.add('animateIn');

      const onAnimationEnd = function () {
        wrapper.removeEventListener('animationend', onAnimationEnd);

        const textarea = wrapper.querySelector(
          `.nwComment[data-id="${postID}"]`,
        );
        if (textarea && !textarea.id) {
          textarea.id = `comment${postID}`;
        }

        const type = 'getReelsComment';
        const data = 'f=' + type + '&id=' + postID;

        $.ajax({
          type: 'POST',
          url: siteurl + 'requests/request.php',
          data: data,
          cache: false,
          success: function (response) {
            commentsContainer.innerHTML = response;
          },
          error: function () {
            commentsContainer.innerHTML =
              '<div class="no_comments_msg">No comments will be shown right now!</div>';
          },
        });
      };

      wrapper.addEventListener('animationend', onAnimationEnd);
    });

    scrollLock = true;
    document
      .querySelectorAll('.scroll-buttons')
      .forEach((el) => (el.style.display = 'none'));
  }
});

$(document).on('click', '.close_reels_comment', function () {
  const wrapper = this.closest('.reels_comments_wrapper');
  if (wrapper) {
    wrapper.classList.remove('animateIn');
    wrapper.classList.add('animateOut');

    wrapper.addEventListener('animationend', function handler() {
      wrapper.style.display = 'none';
      wrapper.classList.remove('animateOut');

      const postIDMatch = wrapper.className.match(/this_reels_(\d+)/);
      if (postIDMatch && postIDMatch[1]) {
        const postID = postIDMatch[1];
        const commentContainer = wrapper.querySelector(
          `#i_user_comments_${postID}`,
        );
        if (commentContainer) {
          commentContainer.innerHTML = '';
        }
      }

      wrapper.removeEventListener('animationend', handler);
    });
  }

  scrollLock = false;
  document
    .querySelectorAll('.scroll-buttons')
    .forEach((el) => (el.style.display = 'flex'));
});

let lastWheelTime = 0;
let touchStartY = 0;
let touchStartX = 0;
let lastTouchTime = 0;

window.addEventListener('touchstart', (e) => {
  touchStartY = e.touches[0].clientY;
  touchStartX = e.touches[0].clientX;
});

window.addEventListener('touchend', (e) => {
  if (scrollLock) return;
  const deltaY = touchStartY - e.changedTouches[0].clientY;
  const deltaX = touchStartX - e.changedTouches[0].clientX;
  const now = Date.now();
  if (now - lastTouchTime < delay) return;
  lastTouchTime = now;

  if (Math.abs(deltaY) > Math.abs(deltaX)) {
    if (deltaY > 60) animateScroll(1);
    else if (deltaY < -60) animateScroll(-1);
  } else {
    if (deltaX > 40) seekVideo(-5);
    else if (deltaX < -40) seekVideo(5);
  }
});

window.addEventListener('keydown', (e) => {
  if (scrollLock) return;
  if (e.key === 'ArrowDown') animateScroll(1);
  else if (e.key === 'ArrowUp') animateScroll(-1);
  else if (e.key === 'ArrowRight') seekVideo(1);
  else if (e.key === 'ArrowLeft') seekVideo(-1);
});

window.addEventListener('load', () => {
  setActiveReel(0);
  const activeReel = reels[0];
  const video = activeReel ? activeReel.querySelector('video') : null;
  if (video) {
    playVideoSafely(video);
    bindVideoEvents(video, activeReel, 0);
  }
});

document.addEventListener('click', function (e) {
  const shareBtn = e.target.closest('.in_social_share');
  if (shareBtn) {
    const postID = shareBtn.getAttribute('id');
    const shareUrl = window.location.origin + '/reels/' + postID;
    const shareText = 'Check out this video!';

    if (navigator.share) {
      navigator
        .share({
          title: 'Reels - Dizzy',
          text: shareText,
          url: shareUrl,
        })
        .catch(function () {
          // Silently ignore
        });
    } else if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(shareUrl).catch(function () {
        openFallback(shareUrl);
      });
    } else {
      openFallback(shareUrl);
    }
  }
});

function openFallback(url) {
  const modal = document.getElementById('fallbackShareModal');
  const input = document.getElementById('fallbackShareInput');
  if (modal && input) {
    input.value = url;
    modal.style.display = 'flex';
  }
}

function closeFallback() {
  const modal = document.getElementById('fallbackShareModal');
  if (modal) {
    modal.style.display = 'none';
  }
}

function copyFallbackLink() {
  const input = document.getElementById('fallbackShareInput');
  input.select();
  input.setSelectionRange(0, input.value.length);
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(input.value);
  }
  closeFallback();
}
$(document).on('click', '.openPostMenu_reel', function () {
  var ID = $(this).attr('id');
  $('.mnoBox' + ID).addClass('dblock');
});
function initExpandableDescriptions() {
  document.querySelectorAll('.description-wrapper').forEach((wrapper) => {
    if (!wrapper || wrapper.dataset.initialized === 'true') return;
    const desc = wrapper.querySelector('.description');
    let readMore = wrapper.querySelector('.read-more');

    if (!desc) {
      wrapper.dataset.initialized = 'true';
      return;
    }

    const isOverflowing = desc.scrollHeight > desc.clientHeight;
    if (isOverflowing) {
      wrapper.classList.add('truncated');
      if (readMore) {
        readMore.style.display = 'block';
      } else {
        // Create a fallback read-more element if not present
        readMore = document.createElement('div');
        readMore.className = 'read-more';
        readMore.textContent = 'Devamını gör';
        wrapper.appendChild(readMore);
      }
    } else if (readMore) {
      // No overflow: hide the control if exists
      readMore.style.display = 'none';
    }

    if (readMore) {
      readMore.addEventListener('click', function (e) {
        e.stopPropagation();
        desc.classList.toggle('expanded');
        wrapper.classList.toggle('expanded');
        wrapper.classList.remove('truncated');
      });
    }

    wrapper.dataset.initialized = 'true';
  });
}

document.addEventListener('DOMContentLoaded', function () {
  initExpandableDescriptions();
});
