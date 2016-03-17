var registersToChart = [4, 5, 6];
$(document).ready(function () {

    var mchart = $('#masterContainer').highcharts();
    var dchart = $('#detailContainer').highcharts();
    setDialogButtons();
    var dtpStart = '\'' + $('input#dtpStart').val() + '\'';
    var dtpEnd = '\'' + $('input#dtpEnd').val() + '\'';

    $.each(registersToChart, function (key, value) {
        loadData(value, dtpStart, dtpEnd, null);
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
    $("input#dtpStart").datetimepicker({
        lang: 'pl',
        format: 'Y-m-d H:i',
        maxDate: 0,
        value: yesterday
    });
    $("input#dtpEnd").datetimepicker({
        lang: 'pl',
        format: 'Y-m-d H:i',
        maxDate: 0,
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

        var dtpStart = '\'' + $('input#dtpStart').val() + '\'';
        var dtpEnd = '\'' + $('input#dtpEnd').val() + '\'';
        $.each(registersToChart, function (key, value) {
            loadData(value, dtpStart, dtpEnd);
        });

    });
    //przycisk dodania serii
    $("#addSeries").click(function () {
        var regId = $("select#avRegs").val();
        var dtpStart = '\'' + $('input#dtpStart').val() + '\'';
        var dtpEnd = '\'' + $('input#dtpEnd').val() + '\'';
        var yAxis = $('input[name=axType]:checked').val();
        console.log(yAxis);
        registersToChart.push(parseInt(regId));
        loadData(parseInt(regId), dtpStart, dtpEnd, yAxis);
    });
}
//załadowanie danych
function loadData(registerId, dtpStart, dtpEnd, yAxis) {
    var data = {
        registerId: registerId,
        from: dtpStart,
        to: dtpEnd
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
            var mchart = $('#masterContainer').highcharts();
            var dchart = $('#detailContainer').highcharts();
            var series = {
                id: ret['id'],
                data: ret['data'],
                name: ret['name'],
                yAxis: yAxis,
                type: "spline",
                lineWidth: 1
            };
            setSeries(dchart, mchart, series);
        }
    });

    function setSeries(dchart, mchart, series) {
        dchart.addSeries(series, false);
        mchart.addSeries(series, false);
        dchart.redraw();
        mchart.redraw();
    }
}