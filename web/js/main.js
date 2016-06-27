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

function setState(time, devicesStatus) {
        var now = Date.parse(new Date);
        var networkConnectionDelay = now / 1000 - time;
        var error = 0;
        var slaves = "";
        $("span.stats").empty();
        $(".error-message div").remove();
        $(".error-message").hide();

        $.each(devicesStatus, function () {
            if (this.status > 0) {
                error = 1;
                //var device_id = this.name.substring(2, 3);
                slaves = slaves + " " + this.name;
                //console.log("Device: " + this.name.substring(2, 3) + " błędny odczyt o " + this.time.date.substring(0, 19) + " sprawdź połączenie modbus!");
            }
        });

        if (networkConnectionDelay >= 300) {
            $("div.variable-panel span").empty();
            $("span#noInternetConnection img").attr("src", "/images/system/ethernetOff.png").addClass("blink");
            $(".error-message").show().append("<div class='row'><div class='col-xs-12'><span class='label label-danger'>Brak połączenia internetowego</span></div></div>");
            $("span#errorModbusConnection").hide();
        } else {
            $("span#noInternetConnection img").attr("src", "/images/system/ethernetOn.png").removeClass("blink");
            $("span#errorModbusConnection").show();
        }

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