
$(document).ready(function () {
    
    $("div.row").find("span.alarmValue").each(function(){
        var value = parseInt($(this).text());
        if(value == 0){
            $(this).parent().parent().css({color: "#00AA00"});
        }else{
            $(this).parent().parent().css({color: "#FF0000"});            
        }
    });

});
