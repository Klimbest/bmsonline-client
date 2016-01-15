var fire;

$(document).ready(function () {
    
    fitFooter();
    if ($.cookie('noShowWelcome'))
        $('.cookie').hide();
    else {
        $("#close-cookie").click(function () {
            $(".cookie").fadeOut(1000);
            $.cookie('noShowWelcome', true);
        });
    }
});

function fitFooter() {

    var footerH = $(".footer-well").height();
    var containerH = $(".content-container").height();
    $(".content-container").css("padding-bottom", footerH + 15 + "px");
}
