/* global parseFloat */

var terms;

$(document).ready(function () {
    setErrorMessage();
    ajaxChangePage(1);
    setInterval(clock, 1000);
    setInterval(counter, 400);

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
            interval = setInterval(function () {
                ajaxRefreshPage(terms);
            }, 10000);

            $(window).resize(minBrowserSizeGuard);
        }
    });
    $(".content-container").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    clearInterval(interval);

    function minBrowserSizeGuard() {
        if ($(window).width() < parseInt($(".page").css("width")) + 45) {
            //$(".page").css({width: "100%"});
        }
        if (fire && $(window).width() >= parseInt($(".page").css("width")) + 45) {
            /* $("div.content-container").children("div:not('.footer-well')").show();
             $("div.error-label").remove();*/
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
            //makeTerms(terms, ret['registers']);
        }
    });
    countToRefresh = 0;
    $(".content-container").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();

    function setVariables(registers) {
        if (registers) {
            $.each(registers, function () {
                var key = this.name;
                var panel_id = this.panel_id;
                var value = this.fixedValue;
                if (value !== null) {
                    var displayPrecision = parseInt($("div#" + panel_id + ".bms-panel-variable").children("input#" + key).val());
                    var roundValue = parseFloat(value).toFixed(displayPrecision);
                }
                $("div#" + panel_id + ".bms-panel").children("span#" + key).empty().append(roundValue);
                //var bmsWidgetPanel = $("div#" + panel_id + ".bms-panel-widget");
                // var rangeMin, rangeMax, widgetValue;
                // if (bmsWidgetPanel.find("div#value" + key).length > 0) {
                //     rangeMin = parseFloat(bmsWidgetPanel.find("div#value" + key).parent().parent().find("div#rangeMin").text().trim());
                //     rangeMax = parseFloat(bmsWidgetPanel.find("div#value" + key).parent().parent().find("div#rangeMax").text().trim());
                //
                //     widgetValue = (value - rangeMin) / (rangeMax - rangeMin) * 100;
                //     if (widgetValue < 0) {
                //         widgetValue = 0;
                //         bmsWidgetPanel.find("div#value" + key).hide();
                //     }
                //     bmsWidgetPanel.find("div#value" + key).show().animate({
                //         left: widgetValue + "%"
                //     }, 2000);
                // }
                // if (bmsWidgetPanel.find("div#set" + key).length > 0) {
                //     rangeMin = parseFloat(bmsWidgetPanel.find("div#set" + key).parent().parent().find("div#rangeMin").text().trim());
                //     rangeMax = parseFloat(bmsWidgetPanel.find("div#set" + key).parent().parent().find("div#rangeMax").text().trim());
                //     widgetValue = (value - rangeMin) / (rangeMax - rangeMin) * 100;
                //     if (widgetValue < 0) {
                //         widgetValue = 0;
                //         bmsWidgetPanel.find("div#set" + key).hide();
                //     }
                //     bmsWidgetPanel.find("div#set" + key).show().animate({
                //         left: widgetValue + "%"
                //     }, 2000);
                // }
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