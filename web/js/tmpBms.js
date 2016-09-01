/* global parseFloat */

var terms;

$(document).ready(function () {
    setErrorMessage();
    ajaxChangePage();
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
            var container = $(".content-container");
            container.children(".fa-spinner").remove();
            container.children("div").remove();
            container.append(ret["template"]).fadeIn("slow");
            terms = ret['terms'];
            ajaxRefreshPage();
            interval = setInterval(function () {
                ajaxRefreshPage();
            }, 10000);

            $(window).resize(minBrowserSizeGuard);
        }
    });
    $(".content-container").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    clearInterval(interval);

    function minBrowserSizeGuard() {
        var page = $(".page");
        if ($(window).width() < parseInt(page.css("width")) + 45) {
            //$(".page").css({width: "100%"});
        }
        if (fire && $(window).width() >= parseInt(page.css("width")) + 45) {
            /* $("div.content-container").children("div:not('.footer-well')").show();
             $("div.error-label").remove();*/
        }
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
            setState(ret['state'], ret['devicesStatus']);
            setVariables(ret['registers']);
        }
    });
    countToRefresh = 0;
    $(".content-container").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();

    function setVariables(registers) {
        if (registers) {
            $.each(registers, function () {
                var panelVariable = $("div#" + this.panel_id + ".bms-panel-variable");
                var value = this.fixed_value;
                if (value !== null) {
                    var displayPrecision = parseInt(panelVariable.find("input").val());
                    var roundValue = parseFloat(value).toFixed(displayPrecision);
                }
                panelVariable.find("span.bms-panel-content").empty().append(roundValue);
            });
        }
    }

}