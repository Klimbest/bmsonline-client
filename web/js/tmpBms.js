var i;

$(document).ready(function () {
    ajaxChangePage(1);
    setInterval(function () {
        ajaxRefreshPage();
    }, 20000);
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
            var x = 20;
            i = setInterval(function () {
                $("span.timer").empty().append(x--);
            }, 1000);
            setRegisters(ret['registers']);
        }
    });
    clearInterval(i);
    $("span.timer").removeClass("label-primary").addClass("label-danger");
    $(".content-container").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();

    function setRegisters(registers) {
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
