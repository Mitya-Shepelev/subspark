(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        const toolHovers = document.querySelector(".tool-hovers");
        if (toolHovers) {
            setTimeout(() => {
                toolHovers.style.display = "block";
                setTimeout(() => {
                    toolHovers.style.display = "";
                }, 3000);
            }, 500);
        }
    });
})();