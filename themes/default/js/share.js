(function ($) {
  "use strict";

  function share(social, url, id) {
    const contentElement = $("#message_text" + id);
    let content = $.trim(contentElement.text());
    const imageElement = $("#message" + id).find("img");
    const image = encodeURIComponent(imageElement.attr("src"));

    if (social !== "gmail" && social !== "yahoo" && social !== "email") {
      content = content.substring(0, 350);
    }

    const encodedURL = encodeURIComponent(url);
    const encodedContent = encodeURIComponent(content);

    const shareWindows = {
      facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodedURL}`,
      twitter: `https://twitter.com/intent/tweet?text=${encodedContent}&url=${encodedURL}`,
      pinterest: `https://pinterest.com/pin/create/button/?url=${encodedURL}&description=${encodedContent}&media=${image}`,
      tumblr: `https://www.tumblr.com/widgets/share/tool?canonicalUrl=${encodedURL}`,
      email: `mailto:?body=${encodedContent} - ${encodedURL}`,
      vkontakte: `http://vkontakte.ru/share.php?url=${encodedURL}&description=${encodedContent}&image=${image}&noparse=true`,
      reddit: `https://www.reddit.com/submit?url=${encodedURL}`,
      linkedin: `https://www.linkedin.com/cws/share?url=${encodedURL}`,
      whatsapp: `whatsapp://send?text=${encodedContent} - ${encodedURL}`,
      viber: `viber://forward?text=${encodedContent} - ${encodedURL}`,
      digg: `http://digg.com/submit?phase=&url=${encodedURL}`,
      evernote: `https://www.evernote.com/clip.action?url=${encodedURL}`,
      yummly: `http://www.yummly.com/urb/verify?url=${encodedURL}&title=${encodedContent}&image=&yumtype=button`,
      yahoo: `http://compose.mail.yahoo.com/?body=${encodedContent} - ${encodedURL}`,
      gmail: `https://mail.google.com/mail/u/0/?view=cm&fs=1&su=&body=${encodedContent} - ${encodedURL}&ui=2&tf=1`
    };

    const target = (social === "email") ? "_self" : "";
    const width = 850;
    const height = 500;

    if (shareWindows[social]) {
      window.open(shareWindows[social], target, `width=${width}, height=${height}`);
    }
  }
 
  $(document).on("click", ".share-btn", function () {
    const social = $(this).data("social");
    const url = $(this).data("url");
    const id = $(this).data("id");
    share(social, url, id);
  });

})(jQuery);