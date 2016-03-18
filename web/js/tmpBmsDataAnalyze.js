/* global registersToChart */

registersToChart = [[4, 1]];
$(document).ready(function () {
    var mchart = $('#masterContainer').highcharts();
    var dchart = $('#detailContainer').highcharts();
    setDialogButtons();
    var dtpStart = '\'' + $('input#dtpStart').val() + '\'';
    var dtpEnd = '\'' + $('input#dtpEnd').val() + '\'';

    $.each(registersToChart, function (key, value) {
        loadData(value[0], dtpStart, dtpEnd, value[1]);
    });
    mchart.xAxis[0].addPlotBand({
        id: 'mask-before',
        from: mchart.xAxis[0].min,
        to: Math.floor(Date.now()) - (3600000 * 3),
        color: 'rgba(0, 0, 0, 0.2)'
    });

    mchart.xAxis[0].addPlotBand({
        id: 'mask-after',
        from: mchart.xAxis[0].dataMax,
        to: mchart.xAxis[0].max,
        color: 'rgba(0, 0, 0, 0.2)'
    });
});

//ustawienie zakresu danych
function setDialogButtons() {
    var now = new Date();
    var yesterday = new Date();
    var mchart = $('#masterContainer').highcharts();
    var dchart = $('#detailContainer').highcharts();
    yesterday.setHours(now.getHours() - 3);
    //$.datetimepicker.setLocale('pl');
    $("input#dtpStart").datetimepicker({
        lang: 'pl',
        format: 'Y-m-d H:i',
        mask: true,
        value: yesterday
    });
    $("input#dtpEnd").datetimepicker({
        lang: 'pl',
        format: 'Y-m-d H:i',
        mask: true,
        value: now
    });
    //obsługa przycisku ustawienia zakresu na ostatnią godzinę 
    $("#setHour").click(function () {
        var now = new Date();
        var ago = new Date();
        ago.setHours(now.getHours() - 1);
        $("input#dtpStart").datetimepicker({value: ago});
        $("input#dtpEnd").datetimepicker({value: now});

    });
    //obsługa przycisku ustawienia zakresu na ostatni dzień
    $("#setDay").click(function () {
        var now = new Date();
        var ago = new Date();
        ago.setDate(now.getDate() - 1);
        $("input#dtpStart").datetimepicker({value: ago});
        $("input#dtpEnd").datetimepicker({value: now});
    });
    //obsługa przycisku ustawienia zakresu na ostatni weekend 
    $("#setWeek").click(function () {
        var now = new Date();
        var ago = new Date();
        ago.setDate(now.getDate() - 7);
        $("input#dtpStart").datetimepicker({value: ago});
        $("input#dtpEnd").datetimepicker({value: now});
    });
    //obsługa przycisku ustawienia zakresu na ostatni miesiąc 
    $("#setMonth").click(function () {
        var now = new Date();
        var ago = new Date();
        ago.setMonth(now.getMonth() - 1);
        $("input#dtpStart").datetimepicker({value: ago});
        $("input#dtpEnd").datetimepicker({value: now});
    });
    //obsługa przycisku ustawienia zakresu na ostatni rok 
    $("#setYear").click(function () {
        var now = new Date();
        var ago = new Date();
        ago.setFullYear(now.getFullYear() - 1);
        $("input#dtpStart").datetimepicker({value: ago});
        $("input#dtpEnd").datetimepicker({value: now});
    });
    //obsługa przycisku ustawienia zakresu na cały zakres 
    $("#setAll").click(function () {
        var now = new Date();
        var ago = new Date(0);
        $("input#dtpStart").datetimepicker({value: ago});
        $("input#dtpEnd").datetimepicker({value: now});
    });
    //zmiana zakresu
    $("#changeScope").click(function () {
        while (dchart.series.length > 0)
            dchart.series[0].remove(true);
        while (mchart.series.length > 0)
            mchart.series[0].remove(true);

        dchart.colorCounter = 0;
        mchart.colorCounter = 0;
        dchart.symbolCounter = 0;
        mchart.symbolCounter = 0;

        var dtpStart = '\'' + $('input#dtpStart').val() + '\'';
        var dtpEnd = '\'' + $('input#dtpEnd').val() + '\'';
        $.each(registersToChart, function (key, value) {
            loadData(value[0], dtpStart, dtpEnd, value[1]);
        });

    });
    //przycisk dodania serii
    $("#addSeries").click(function () {
        var regId = $("select#avRegs").val();
        var dtpStart = '\'' + $('input#dtpStart').val() + '\'';
        var dtpEnd = '\'' + $('input#dtpEnd').val() + '\'';
        var yAxis = $('input[name=axType]:checked').val();
        registersToChart.push([regId, parseInt(yAxis)]);
        loadData(regId, dtpStart, dtpEnd, parseInt(yAxis));
        $("option#" + parseInt(regId)).hide();

    });
}
//załadowanie danych
function loadData(registerId, dtpStart, dtpEnd, yAxis) {
    var data = {
        registerId: registerId,
        from: dtpStart,
        to: dtpEnd,
        yAxis: yAxis
    };
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i><div id='loading' class='row text-center'><div class='col-md-12'></br></br></br>Ładowanie...</div></div>").show();
    return $.ajax({
        type: "POST",
        datatype: "application/json",
        data: data,
        async: false,
        url: Routing.generate('bms_data_analyze_add_series'),
        success: function (ret) {
            $(".main-row").children(".fa-spinner, div#loading").remove();
            var series = {
                id: ret['id'],
                data: ret['data'],
                name: ret['name'] + " <i id='" + ret['id'] + "' class='fa fa-remove fa-lg' style='display: none; color: #B00'></i>",
                yAxis: yAxis,
                type: "spline",
                lineWidth: 1
            };
            setSeries(series);
        }
    });

    function setSeries(series) {
        var mchart = $('#masterContainer').highcharts();
        var dchart = $('#detailContainer').highcharts();

        dchart.addSeries(series, false);
        mchart.addSeries(series, false);

        dchart.redraw();
        mchart.redraw();

        setClickable();
        $("select#avRegs").val(null);
        $("." + parseInt(series.id)).hide();
    }
}

function setClickable() {
    $(".highcharts-legend-item").find("i").each(function () {
        $(this).unbind("click").click(function () {
            var mchart = $('#masterContainer').highcharts();
            var dchart = $('#detailContainer').highcharts();
            var id = parseInt($(this).attr("id"));
            mchart.get(id).remove();
            dchart.get(id).remove();
            registersToChart.splice($.inArray([id, 1], registersToChart), 1);
            setClickable();
            $("." + parseInt(id)).show();
        });
    });
    $(".highcharts-legend-item").hover(function () {
        $(this).find("i").show();

    }, function () {
        $(this).find("i").hide();
    });
}

