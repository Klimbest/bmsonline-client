$(document).ready(function () {
    setDatepicker();
    //obsługa przycisku zmiany zakresu danych
//    $("#changeScope").click(function () {
//        clearInterval(intRefresh);					//wyczyszczenie interwału odświeżania
//        loadChart(serialPort, devId, readAddress);				//ładowanie wykresu
//        intRefresh = setInterval(function () {		//ustawienie interwału odświeżania wykresu
//            refreshChart(serialPort, devId, readAddress);		//odświeżenie wykresu
//        }, refresh * 1000);							//60 * 1 sek = minuta
//        setClickable();
//    });
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
});

//ustawienie zakresu danych
function setDatepicker() {
    var now = new Date();
    var yesterday = new Date();
    yesterday.setDate(now.getDate() - 1);
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
}