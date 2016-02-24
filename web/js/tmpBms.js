/* global parseFloat */

var i;

$(document).ready(function () {
    ajaxChangePage(1);
    setInterval(function () {
        ajaxRefreshPage();
    }, 5000);
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
            ajaxRefreshPage();
            $(window).resize(function () {
                minBrowserSizeGuard();
            });
            setNavigation();
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

    function setNavigation() {
        $("div.bms-panel.navigation-panel").each(function () {
            var link_id = $(this).children("div").attr("id");
            $(this).click(function () {
                ajaxChangePage(link_id);
            });
        });
    }
}

function ajaxRefreshPage() {
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
//            var x = 5;
//            i = setInterval(function () {
//                $("span.timer").empty().append(x--);
//            }, 1000);
            setVariables(ret['registers'], ret["terms"]);
        }
    });
//    clearInterval(i);
    $("span.timer").removeClass("label-primary").addClass("label-danger");
    $(".content-container").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();

    function setVariables(registers, terms) {

        $.each(terms, function (key, term) {
            var condition = term.condition.split(";");
            condition[1] = parseFloat(condition[1]).toFixed(2);
            var register_id = term.register_id;
            var eContent = term.effect_content;
            var eField = term.effect_field;
            var ePanelId = term.effect_panel_id;
            var value = term.register_val;
            switch (condition[0]) {
                case "==" :
                    if (value === condition[1]) {
                        makeTerm(eField);
                    }
                    break;
                case "!=" :
                    if (value !== condition[1]) {
                        makeTerm(eField);
                    }
                    break;
                case ">" :
                    if (value > condition[1]) {
                        makeTerm(eField);
                    }
                    break;
                case "<" :
                    if (value < condition[1]) {
                        makeTerm(eField);
                    }
                    break;
                case ">=" :
                    if (value >= condition[1]) {
                        makeTerm(eField);
                    }
                    break;
                case "<=" :
                    if (value <= condition[1]) {
                        makeTerm(eField);
                    }
                    break;
            }

            function makeTerm(type) {
                var content = eContent.split(";");
                
                switch (type) {
                    case "css" :
                        $("div#" + ePanelId + ".bms-panel").css(content[0], content[1]);
                        break;
                    case "src" :
                        $("div#" + ePanelId + ".bms-panel img").attr("src", eContent);
                        break;
                    case "spin" :
                        $("div#" + ePanelId + ".bms-panel").children().addClass("fa-spin");
                        break;
                }
            }

        });

        $.each(registers, function (key, value) {
            $("div.variable-panel").children("span#" + key).empty().append(value);
        });



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
