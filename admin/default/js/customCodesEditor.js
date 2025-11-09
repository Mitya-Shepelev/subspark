(function () {
  "use strict";

  function initializeCodeMirror(id, mode) {
    const textarea = document.getElementById(id);
    if (textarea) {
      CodeMirror.fromTextArea(textarea, {
        mode: mode,
        theme: "default",
        lineNumbers: true,
        readOnly: false
      });
    }
  }

  document.addEventListener("DOMContentLoaded", function () {
    initializeCodeMirror("custom-css", "css");
    initializeCodeMirror("custom-js", "javascript");
    initializeCodeMirror("customfooter-js", "javascript");
  });
})();