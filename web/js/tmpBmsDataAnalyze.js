$(document).ready(function () {
    var registerId = 8;
    var mchart = $('#masterContainer').highcharts();
    var dchart = $('#detailContainer').highcharts();
    setDatepicker();
    var dtpStart = '\'' + $('input#dtpStart').val() + '\'';
    var dtpEnd = '\'' + $('input#dtpEnd').val() + '\'';
    loadData(registerId, dtpStart, dtpEnd);
});

//ustawienie zakresu danych
function setDatepicker() {
    var now = new Date();
    var yesterday = new Date();
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
    $("#changeScope").click(function () {
        
    });
}
//załadowanie danych
function loadData(registerId, dtpStart, dtpEnd) {
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
                yAxis: ret['yAxis'],
                type: "spline",
                lineWidth: 1
            };
            setSeries(dchart, mchart, series);
        }
    });
    

    function setSeries(dchart, mchart, series) {
        dchart.addSeries(series, true);
        mchart.addSeries(series, true);

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
    }
}