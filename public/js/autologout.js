$(document).ready(function () {
    const timeout = 1200000;  // 900000 ms = 15 minutes
    var idleTimer = null;
    $('*').bind('mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick', function () {
        clearTimeout(idleTimer);

        idleTimer = setTimeout(function () {
            document.getElementById('logout-link').click();
        }, timeout);
    });
    $("body").trigger("mousemove");
});