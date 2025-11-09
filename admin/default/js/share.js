(function ($) {
  "use strict";

  window.share = function (social, url, id) {
    const contentElement = $(`#message_text${id}`);
    let content = contentElement.text().trim();
    const image = encodeURIComponent($(`#message${id}`).find("img").attr("src") || "");

    // Truncate content if not email-based
    if (!['gmail', 'yahoo', 'email'].includes(social)) {
      content = content.substring(0, 350);
    }

    const encodedContent = encodeURIComponent(content);
    const shareUrls = {
      facebook: `https://www.facebook.com/sharer/sharer.php?u=${url}`,
      twitter: `https://twitter.com/intent/tweet?text=${encodedContent}&url=${url}`,
      pinterest: `https://pinterest.com/pin/create/button/?url=${url}&description=${encodedContent}&media=${image}`,
      tumblr: `https://www.tumblr.com/widgets/share/tool?canonicalUrl=${url}`,
      email: `mailto:?body=${encodedContent} - ${url}`,
      vkontakte: `http://vkontakte.ru/share.php?url=${url}&description=${encodedContent}&image=${image}&noparse=true`,
      reddit: `https://www.reddit.com/submit?url=${url}`,
      linkedin: `https://www.linkedin.com/cws/share?url=${url}`,
      whatsapp: `whatsapp://send?text=${encodedContent} - ${url}`,
      viber: `viber://forward?text=${encodedContent} - ${url}`,
      digg: `http://digg.com/submit?phase=&url=${url}`,
      evernote: `https://www.evernote.com/clip.action?url=${url}`,
      yummly: `http://www.yummly.com/urb/verify?url=${url}&title=${encodedContent}&image=&yumtype=button`,
      yahoo: `http://compose.mail.yahoo.com/?body=${encodedContent} - ${url}`,
      gmail: `https://mail.google.com/mail/u/0/?view=cm&fs=1&su=&body=${encodedContent} - ${url}&ui=2&tf=1`
    };

    const features = "width=650,height=450";

    if (shareUrls[social]) {
      const target = (social === 'email') ? "_self" : "";
      window.open(shareUrls[social], target, features);
    }
  };

})(jQuery);