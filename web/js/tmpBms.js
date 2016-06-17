/* global parseFloat */

var terms, interval;
var countToRefresh = 0;
var errorClosed = 0;

$(document).ready(function () {
    setErrorMessage();
    ajaxChangePage(1);
    setInterval(clock, 1000);
    setInterval(counter, 800);

});

function setErrorMessage() {
    $("div.error-message").draggable();
    $("div.error-message i.fa-remove").click(function () {
        errorClosed = 1;
        $("div.error-message").hide();
    });
}

function ajaxChangePage(page_id) {
    var data = {
        page_id: page_id
    };
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_change_page'),
        data: data,
        success: function (ret) {
            $(".content-container").children(".fa-spinner").remove();
            $(".content-container").children("div").remove();
            $(".content-container").append(ret["template"]).fadeIn("slow");
            terms = ret['terms'];
            ajaxRefreshPage(terms);
            interval = setInterval(function () {
                ajaxRefreshPage(terms);
            }, 5 * 1000);

            $(window).resize(minBrowserSizeGuard);
        }
    });
    $(".content-container").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    clearInterval(interval);
    function minBrowserSizeGuard() {
        if ($(window).width() < parseInt($(".page").css("width")) + 45) {
            var label = "<div class='text-center error-label'><h3><span class='label label-primary'>Za mała szerokość przeglądarki</span></h3></div>";

            $("div.content-container").children("div:not('.footer-well')").hide();
            $("div.content-container").append(label);
            fire = true;
        }
        if (fire && $(window).width() >= parseInt($(".page").css("width")) + 45) {
            $("div.content-container").children("div:not('.footer-well')").show();
            $("div.error-label").remove();
        }
    }
}

function ajaxRefreshPage(terms) {
    var data = {
        page_id: $("div.well.page").attr("id")
    };
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_refresh_page'),
        data: data,
        success: function (ret) {
            $(".content-container").children(".fa-spinner").remove();
            $("span.timer").removeClass("label-danger").addClass("label-primary");
            setState(ret['state'], ret['devicesStatus']);
            setVariables(ret['registers']);
            makeTerms(terms, ret['registers']);
        }
    });
    countToRefresh = 0;
    $(".content-container").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();


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

    function setVariables(registers) {
        if(registers){
            $.each(registers, function (key, value) {
                if (value !== null) {
                    var displayPrecision = parseInt($("div.bms-panel-variable").children("span#" + key).attr("value"));
                    var roundValue = parseFloat(value).toFixed(displayPrecision);
                }
                $("div.bms-panel").children("span#" + key).empty().append(roundValue);

                if ($("div.bms-panel-widget").find("div#value" + key).length > 0) {
                    var rangeMin = parseFloat($("div.bms-panel-widget").find("div#value" + key).parent().parent().find("div#rangeMin").text().trim());
                    var rangeMax = parseFloat($("div.bms-panel-widget").find("div#value" + key).parent().parent().find("div#rangeMax").text().trim());

                    var widgetValue = (value - rangeMin) / (rangeMax - rangeMin) * 100;
                    if (widgetValue < 0) {
                        widgetValue = 0;
                        $("div.bms-panel-widget").find("div#value" + key).hide();
                    }
                    $("div.bms-panel-widget").find("div#value" + key).show().animate({
                        left: widgetValue + "%"
                    }, 2000);
                }
                if ($("div.bms-panel-widget").find("div#set" + key).length > 0) {
                    var rangeMin = parseFloat($("div.bms-panel-widget").find("div#set" + key).parent().parent().find("div#rangeMin").text().trim());
                    var rangeMax = parseFloat($("div.bms-panel-widget").find("div#set" + key).parent().parent().find("div#rangeMax").text().trim());
                    var widgetValue = (value - rangeMin) / (rangeMax - rangeMin) * 100;
                    if (widgetValue < 0) {
                        widgetValue = 0;
                        $("div.bms-panel-widget").find("div#set" + key).hide();
                    }
                    $("div.bms-panel-widget").find("div#set" + key).show().animate({
                        left: widgetValue + "%"
                    }, 2000);
                }
            });
        }
    }

    function makeTerms(terms, registers) {
        if (terms) {
            $.each(terms, function (key, term) {
                var id = term.panel_id;
                $("div#" + id + ".bms-panel").hide();
            });
            $.each(terms, function (key, term) {
                switch (term.condition_type) {
                    case "==" :
                        if (registers[term.register_id] == term.condition_value) {
                            applyTermEffect(term);
                        }
                        break;
                    case "!=" :
                        if (registers[term.register_id] != term.condition_value) {
                            applyTermEffect(term);
                        }
                        break;
                    case "<" :
                        if (registers[term.register_id] < term.condition_value) {
                            applyTermEffect(term);
                        }
                        break;
                    case "<=" :
                        if (registers[term.register_id] <= term.condition_value) {
                            applyTermEffect(term);
                        }
                        break;
                    case ">" :
                        if (registers[term.register_id] > term.condition_value) {
                            applyTermEffect(term);
                        }
                        break;
                    case ">=" :
                        if (registers[term.register_id] >= term.condition_value) {
                            applyTermEffect(term);
                        }
                        break;
                }
            });
        }
        function applyTermEffect(term) {
            $("div#" + term.panel_id + ".bms-panel").show().removeClass("shake-little shake-constant fa-spin");
            switch (term.effect_type) {
                case "css" :
                    var content = term.effect_content.split(";");
                    $("div#" + term.panel_id + ".bms-panel").css(content[0], content[1]);
                    break;
                case "src" :
                    $("div#" + term.panel_id + ".bms-panel img").attr("src", term.effect_content);
                    break;
                case "animation" :
                    $("div#" + term.panel_id + ".bms-panel").addClass(term.effect_content);
                    break;
                case "text" :
                    $("div#" + term.panel_id + ".bms-panel span.bms-panel-content").empty().append(term.effect_content);
                    break;
                case "popup" :
                    alert(term.effect_content);
                    break;
            }
        }
    }
}

function counter() {

    countToRefresh++;
    if ($("div.well.page").length > 0) {
        $("div.timer div.progress-bar").css({width: countToRefresh * 800 / 100 + "%"});
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
