/* global parseFloat */

var i;
var terms;

$(document).ready(function () {
    ajaxChangePage(1);
    setInterval(function () {
        ajaxRefreshPage(terms);
    }, 10000);
    setInterval(clock, 1000);

});

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
            $(window).resize(minBrowserSizeGuard);
        }
    });
    $(".content-container").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();

    function minBrowserSizeGuard() {
        if ($(window).width() < parseInt($(".page").css("width")) + 30) {
            var label = "<div class='text-center error-label'><h3><span class='label label-primary'>Za mała szerokość przeglądarki</span></h3></div>";

            $("div.content-container").children("div:not('.footer-well')").hide();
            $("div.content-container").append(label);
            fire = true;
        }
        if (fire && $(window).width() >= parseInt($(".page").css("width")) + 30) {
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
            var x = 8;
            i = setInterval(function () {
                $("span.timer").empty().append(x--);
            }, 1000);
                        
            setState(ret['state'], ret['devicesStatus']);
            setVariables(ret['registers']);
            makeTerms(terms, ret['registers']);
        }
    });
    clearInterval(i);
    $("span.timer").removeClass("label-primary").addClass("label-danger");
    $(".content-container").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();

    function setState(time, devicesStatus){
        var now = Date.parse(new Date);
        var readDelay = now / 1000 - time;
        $("span.stats").empty();
        if (readDelay >= 300) {
            var errorConnection = "<span class='fa-stack fa-lg blink'>\n\
                                        <i class='fa fa-wifi fa-stack-1x'></i>\n\
                                        <i class='fa fa-ban fa-stack-2x fa-red'></i>\n\
                                   </span>";
            $("div.variable-panel span").empty();
            $("span.stats").append(errorConnection);
        } else {
            $(".error-message span").empty();
        }  
//            $("div.variable-panel span").empty();
//            if (readDelay / 60 < 60) {
//                $(".error-message span").empty().append("Od " + Math.round(readDelay / 60) + " minut nie ma nowych danych!").show();
//            } else if (readDelay / 60 / 60 < 24) {
//                $(".error-message span").empty().append("Od " + Math.round(readDelay / 60 / 60) + " godzin nie ma nowych danych!").show();
//            } else {
//                $(".error-message span").empty().append("Od " + Math.round(readDelay / 60 / 60 / 24) + " dni nie ma nowych danych!").show();
//            }
//        } 
        $.each(devicesStatus, function(){
            if(this.status > 0){
                var device_id = this.name.substring(2, 3);
                $(".error-message span").append("Device: " + device_id + " błędny odczyt o " + this.time.date.substring(0, 19) + " sprawdź połączenie modbus!");
                //console.log("Device: " + this.name.substring(2, 3) + " błędny odczyt o " + this.time.date.substring(0, 19) + " sprawdź połączenie modbus!");
            }
        });

    }

    function setVariables(registers) {
        $.each(registers, function (key, value) {
            if (value !== null) {                
                var displayPrecision = parseInt($("div.bms-panel-variable").children("span#" + key).attr("value"));
                var roundValue = parseFloat(value).toFixed(displayPrecision);
            }
            $("div.bms-panel").children("span#" + key).empty().append(roundValue);
            
            if($("div.bms-panel-widget").find("div#value" + key).length > 0){
                var rangeMin = parseFloat($("div.bms-panel-widget").find("div#value" + key).parent().parent().find("div#rangeMin").text().trim());
                var rangeMax = parseFloat($("div.bms-panel-widget").find("div#value" + key).parent().parent().find("div#rangeMax").text().trim());
                
                var widgetValue = (value - rangeMin)/(rangeMax - rangeMin) * 100;
                if (widgetValue < 0) {
                    widgetValue = 0;
                    $("div.bms-panel-widget").find("div#value" + key).hide();
                }
                $("div.bms-panel-widget").find("div#value" + key).show().animate({
                    left: widgetValue + "%"
                }, 2000);
            }
            if($("div.bms-panel-widget").find("div#set" + key).length > 0){
                var rangeMin = parseFloat($("div.bms-panel-widget").find("div#set" + key).parent().parent().find("div#rangeMin").text().trim());
                var rangeMax = parseFloat($("div.bms-panel-widget").find("div#set" + key).parent().parent().find("div#rangeMax").text().trim());
                var widgetValue = (value - rangeMin)/(rangeMax - rangeMin) * 100;
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
                    $("div#" + term.panel_id + ".bms-panel span.content").text(term.effect_content);
                    break;
                case "popup" :
                    alert(term.effect_content);
                    break;
            }
        }
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
