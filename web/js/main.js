var fire;
var errorClosed = 0;
var interval;
var count = 0;

$(document).ready(function () {

    if ($.cookie('noShowWelcome'))
        $('.cookie').hide();
    else {
        $("#close-cookie").click(function () {
            $(".cookie").fadeOut(1000);
            $.cookie('noShowWelcome', true);
        });
    }
    setInterval(clock, 1000);
    setGenerateVisualization();
});


function setState(time, devicesStatus) {
    var now = Date.parse(new Date);
    var networkConnectionDelay = now / 1000 - time;
    var error = 0;
    var slaves = "";
    $("span.stats").empty();
    $(".error-message div").remove();
    $(".error-message").hide();

    $.each(devicesStatus, function () {
        if (this.scanState > 0) {
            error = 1;
            slaves = slaves + " " + this.name;
        }
    });

   if (networkConnectionDelay >= 300) {
       $("div.variable-panel span").empty();
       $("span#noInternetConnection img").attr("src", "/images/system/ethernetOff.png").addClass("blink");
       // $(".error-message").show().append("<div class='row'><div class='col-xs-12'><span class='label label-danger'>Brak połączenia internetowego</span></div></div>");
       $("span#errorModbusConnection").hide();
   } else {
       $("span#noInternetConnection img").attr("src", "/images/system/ethernetOn.png").removeClass("blink");
       $("span#errorModbusConnection").show();
   }

    if (error !== 0) {
        $("div.variable-panel span").empty();
        $("span#errorModbusConnection img").attr("src", "/images/system/disconnected.png").addClass("blink");
        if (errorClosed !== 1) {
            // $(".error-message").show().append("<div class='row'><div class='col-md-12'><span class='label label-danger'>Brak synchronizacji danych (slave: " + slaves + ")</span></div></div>");
        }
    } else {
        $("span#errorModbusConnection img").attr("src", "/images/system/connected.png").removeClass("blink");
    }

}

function setErrorMessage() {
    $("div.error-message i.fa-remove").click(function () {
        errorClosed = 1;
        $("div.error-message").hide();
    });
}

function setGenerateVisualization() {
    $('button#generateVisualization').click(function () {
        $.ajax({
            type: "POST",
            datatype: "application/json",
            url: Routing.generate('bms_visualization_generate'),
            success: function (ret) {
                $(".content-container").children(".fa-spinner").remove();
            }
        });
        $(".content-container").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    });
}

var totaltime = 10;
function update(percent){
    var deg;

    if(percent<(totaltime/2)){
        deg = 90 + (360*percent/totaltime);
        $('.pie').css('background-image',
            'linear-gradient('+deg+'deg, transparent 50%, white 50%),linear-gradient(90deg, white 50%, transparent 50%)'
        );
    } else if(percent>=(totaltime/2) && percent < totaltime){
        deg = -90 + (360*percent/totaltime);
        $('.pie').css('background-image',
            'linear-gradient('+deg+'deg, transparent 50%, #27AE60 50%),linear-gradient(90deg, white 50%, transparent 50%)'
        );
    }
}

function counter() {
    if(count==0){
        $('.pie').css('background-color', '#27AE60');
        $('.degree').css('background-color','#27AE60' );
    }
    count+=1;
    update(count);
    if(count==totaltime){
        $('.pie').css('background-image', 'none');
        $('.degree').css('background-color','#BB0000' );
        update(count);
        count = 0;
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

function hexToR(h) {return parseInt((cutHex(h)).substring(0,2),16)}
function hexToG(h) {return parseInt((cutHex(h)).substring(2,4),16)}
function hexToB(h) {return parseInt((cutHex(h)).substring(4,6),16)}
function cutHex(h) {return (h.charAt(0)=="#") ? h.substring(1,7):h}

function invert(rgb) {
    rgb = Array.prototype.join.call(arguments).match(/(-?[0-9\.]+)/g);
    for (var i = 0; i < rgb.length; i++) {
        rgb[i] = (i === 3 ? 1 : 255) - rgb[i];
    }
    return rgb;
}