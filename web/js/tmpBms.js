/* global parseFloat */

$(document).ready(function () {
    setErrorMessage();
    ajaxChangePage();
    setInterval(counter, 1000);
    Highcharts.setOptions({
        plotOptions: {
            series: {
                animation: false
            }
        }
    });
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
            $("span.timer").removeClass("label-danger").addClass("label-primary");
            setState(ret['state'], ret['devicesStatus']);
            setPanelVariables(ret['registers']);
            setProgressBars(ret['progressbars']);
            makeEventsHideShow(ret['events_change_source'])
            setGadgetsCart(ret['gadget_chart']);
        }
    });
    count = 0;

}

function setPanelVariables(registers) {
    if (registers) {
        $.each(registers, function () {
            var panelVariable = $("div#" + this.panel_id + ".bms-panelvariable");
            //noinspection JSUnresolvedVariable
            var value = this.fixed_value;
            if (value !== null) {
                var displayPrecision = parseInt(panelVariable.find("input").val());
                var roundValue = parseFloat(value).toFixed(displayPrecision);
            }
            var old_color = panelVariable.css("color");
            var old_value = panelVariable.find("span.bms-panel-content").text();
            if (old_value !== roundValue) {
                panelVariable.find("span.bms-panel-content").empty().append(roundValue);
                panelVariable.css({
                    color: invert(old_color)
                }).animate({
                    color: old_color
                }, 500, "linear");
            }
        });
    }
}

function setProgressBars(progressbars) {
    if (progressbars) {
        $.each(progressbars, function () {
            var gadgetProgressBar = $("div#" + this.id + ".bms-gadgetprogressbar");
            if(this.value < 0 || this.value > 100){
                gadgetProgressBar.find("div#value").hide();
            } else{
                gadgetProgressBar.find("div#value").animate({
                    left: this.value + "%"
                }, 500, "linear");
            }
            if(this.value < 0 || this.value > 100){
                gadgetProgressBar.find("div#set").hide();
            }else{
                gadgetProgressBar.find("div#set").animate({
                    left: this.set + "%"
                }, 500, "linear");
            }

        });
    }
}

function makeEventsHideShow(events) {
    if (events) {
        $.each(events, function () {
            var panelImage = $("div#" + this.panel_id + ".bms-panelimage");
            //noinspection JSUnresolvedVariable
            panelImage.find("img").attr("src", this.source).attr("title", this.source_label);
            panelImage.show();

        });
    }
}

function setGadgetsCart(charts) {
    if (charts) {
        $.each(charts, function () {
            var chart = $("div#" + this.id + ".bms-gadgetchart div#chart_" + this.id).highcharts();

            while (chart.series.length > 0)
                chart.series[0].remove(true);
            chart.addSeries(this.series);
        });
    }
}

function writeRegister(value, register_id){
    var data = {
        value: value,
        register_id: register_id
    };
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('write_register'),
        data: data,
        success: function () {
            var container = $(".content-container");
            container.children(".fa-spinner").remove();
            ajaxRefreshPage();
            interval = setInterval(function () {
                ajaxRefreshPage();
            }, 10000);
        }
    });
    $(".content-container").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    clearInterval(interval);
}