var fire;
var errorClosed = 0;
var interval;
var countToRefresh = 0;

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
    setGenerateVisualization();
});

function fitFooter() {

    var footerH = $(".footer-well").height();
    var containerH = $(".content-container").height();
    $(".content-container").css("padding-bottom", footerH + 15 + "px");
}

function setState(time, devicesStatus) {
    var now = Date.parse(new Date);
    var networkConnectionDelay = now / 1000 - time;
    var error = 0;
    var slaves = "";
    $("span.stats").empty();
    $(".error-message div").remove();
    $(".error-message").hide();

    $.each(devicesStatus, function () {
        if (this.scanState > 0) {
            error = 1;
            slaves = slaves + " " + this.name;
        }
    });

//    if (networkConnectionDelay >= 300) {
//        $("div.variable-panel span").empty();
//        $("span#noInternetConnection img").attr("src", "/images/system/ethernetOff.png").addClass("blink");
//        $(".error-message").show().append("<div class='row'><div class='col-xs-12'><span class='label label-danger'>Brak połączenia internetowego</span></div></div>");
//        $("span#errorModbusConnection").hide();
//    } else {
//        $("span#noInternetConnection img").attr("src", "/images/system/ethernetOn.png").removeClass("blink");
//        $("span#errorModbusConnection").show();
//    }

    if (error !== 0) {
        $("div.variable-panel span").empty();
        $("span#errorModbusConnection img").attr("src", "/images/system/disconnected.png").addClass("blink");
        if (errorClosed !== 1) {
            $(".error-message").show().append("<div class='row'><div class='col-md-12'><span class='label label-danger'>Brak synchronizacji danych (slave: " + slaves + ")</span></div></div>");
        }
    } else {
        $("span#errorModbusConnection img").attr("src", "/images/system/connected.png").removeClass("blink");
    }

}

function setErrorMessage() {
    $("div.error-message i.fa-remove").click(function () {
        errorClosed = 1;
        $("div.error-message").hide();
    });
}

function setGenerateVisualization() {
    $('button#generateVisualization').click(function () {
        $.ajax({
            type: "POST",
            datatype: "application/json",
            url: Routing.generate('bms_visualization_generate'),
            success: function (ret) {
                $(".content-container").children(".fa-spinner").remove();
            }
        });
        $(".content-container").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    });
}

function counter() {

    countToRefresh++;
    if ($("div.well").length > 0) {
        $("div.timer div.progress-bar").css({width: countToRefresh * 400 / 100 + "%"});
    } else {
        $("div.timer div.progress-bar").css({width: "0%"});
    }
}

function clock() {
    var currentTime = new Date( );
    var currentHours = currentTime.getHours( );
    var currentMinutes = currentTime.getMinutes( );
    var currentSeconds = currentTime.getSeconds( );

    currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;
    currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;

    var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds;

    $("span.clock").empty().append(currentTimeString);
}
