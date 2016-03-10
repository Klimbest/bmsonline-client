/* global parseFloat */

var i;

$(document).ready(function () {
    ajaxChangePage(1);
    setInterval(function () {
        ajaxRefreshPage();
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
            var x = 5;
            i = setInterval(function () {
                $("span.timer").empty().append(x--);
            }, 1000);
            var now = new Date;
            now = Date.parse(now);
            var readDelay = now - ret['time_of_update']*1000;
            if(readDelay >= 300){
                $("div.variable-panel span").empty();
                $(".error-message span").empty().append("Od ponad 5 minut nie ma nowych danych!").show();
            }else{
                setVariables(ret['registers'], ret["terms"]);
            }
            
        }
    });
    clearInterval(i);
    $("span.timer").removeClass("label-primary").addClass("label-danger");
    $(".content-container").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();

    function setVariables(registers, terms) {

        //Tymczasowe
        var difference = registers[37] - registers[31];
        $("div#91.bms-panel.area-panel").empty().append("<span>" + Math.round(difference * 100) / 100 + "</span>");
        //Tymczasowe

        if (terms) {
            $.each(terms, function (key, term) {
                var eContent = term.effect_content;
                var content = eContent.split(";");
                var panelId = term.panel_id;
                $("div#" + panelId + ".bms-panel").removeClass("shake-little shake-constant fa-spin");

                switch (term.effect_type) {
                    case "css" :
                        $("div#" + panelId + ".bms-panel").css(content[0], content[1]);
                        break;
                    case "src" :
                        $("div#" + panelId + ".bms-panel img").attr("src", eContent);
                        break;
                    case "animation" :
                        $("div#" + panelId + ".bms-panel").addClass(eContent);
                        break;
                    case "text" :
                        $("div#" + panelId + ".bms-panel span.content").text(eContent);
                        break;
                }
            });
        }

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
