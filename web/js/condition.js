$(document).ready(function () {
    $(".btn-add-condition").click(function () {
        ajaxCreateConditionDialog();
    });
});

function ajaxCreateConditionDialog() {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_create_condition_dialog'),
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            $("body").append(ret['template']);
            createConditionDialog().dialog("open");
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}

function createConditionDialog() {
    var dialog = $("div.dialog-condition");
    return $("div.dialog-condition").dialog({
        autoOpen: false,
        width: $(window).width(),
        height: $(window).height(),
        modal: true,
        buttons: [
            {
                text: "Anuluj",
                click: function () {
                    $(this).dialog('destroy').remove();
                }
            }],
        open: function () {
            setDialog(dialog);
            setNavButtons(dialog);
            setEvents(dialog);
        },
        close: function () {
            $(this).dialog('destroy').remove();
        }
    });

    function setNavButtons(dialog) {
        dialog.find("button.next").click(function () {
            $(this).parent().parent().parent().parent().hide("slide", {direction: "left"}, 500);
            var id = $(this).attr("id");
            dialog.find("div#" + id).delay(505).show("slide", {direction: "right"}, 500);
            var progress = dialog.find("div#progressbar").progressbar("value");
            dialog.find("div#progressbar").progressbar("value", progress + 25);
        });
        dialog.find("button.prev").click(function () {
            $(this).parent().parent().parent().parent().hide("slide", {direction: "right"}, 500);
            var id = $(this).attr("id");
            dialog.find("div#" + id).delay(505).show("slide", {direction: "left"}, 500);
            var progress = dialog.find("div#progressbar").progressbar("value");
            dialog.find("div#progressbar").progressbar("value", progress - 25);
        });
        dialog.find("button#save").click(function () {
            var condition_type = dialog.find("select#condition_type").val();
            console.log(dialog.find("input#effect_panel_id").val());
            var condition_val = dialog.find("input#condition_val").val();
            var data = {
                register_id: dialog.find("input#register_id").val(),
                condition: condition_type + ";" + condition_val, //do poprawy żeby było spójne
                effect_type: dialog.find("select#effect_type").val(),
                effect_content: dialog.find("input#effect_content").val(),
                effect_panel_id: dialog.find("input#effect_panel_id").val()
            };
            ajaxCreateCondition(data);
            dialog.dialog('destroy').remove();
        });
    }

    function setDialog(dialog) {
        $(this).css({zIndex: 500});
        $("#progressbar").progressbar({value: 0});
        //set initial list display
        dialog.find("div.register-choice:not(." + dialog.find("select#device_filter").val() + ")").hide();
        dialog.find("div.condition-choice:not(." + dialog.find("select#condition_type_filter").val() + ")").hide();
        dialog.find("div.effect_type:not(#effect_type_" + dialog.find("select#effect_type").val() + ")").hide();
    }

    function setEvents(dialog) {
        //setp1
        //set hover and click on list of registers
        dialog.find("div.row.register-choice").each(function () {
            setHover($(this));
            $(this).click(function () {
                $(this).parent().children("div.row.register-choice").css({backgroundColor: "", color: ""}).unbind('mouseenter mouseleave').each(function () {
                    setHover($(this));
                });
                $(this).unbind('mouseenter mouseleave').css({backgroundColor: "#337ab7", color: "#FFF"});
                var register_id = $(this).attr("id");
                dialog.find("input#register_id").val(register_id);
                var description = "<span>Jeżeli wartość rejestru <strong>" + $(this).children(".register_name").text() + "</strong> jest ...</span>";
                dialog.find("div#description").empty().append(description);
            });
        });
        dialog.find("select#device_filter").change(function () {
            var name = $(this).val();
            dialog.find("div.register-choice").hide();
            dialog.find("div." + name).show();
        });
        dialog.find("select#condition_type_filter").change(function () {
            var name = $(this).val();
            dialog.find("div.condition-choice").hide();
            dialog.find("div." + name).show();
        });
        
//        if ($(this).attr("id") === "step4" && dialog.find("select#effect_field").val() === "css") {
//            dialog.find("div.effect_type").hide();
//            dialog.find("div#effect_type_css.effect_type").show();
//        }
//        dialog.find("div#effect_type_css").show();
//
//        dialog.find("div.row.panel-choice").each(function () {
//            setHover($(this));
//            $(this).click(function () {
//                $(this).parent().children("div.row.panel-choice").css({backgroundColor: "", color: ""}).unbind('mouseenter mouseleave').each(function () {
//                    setHover($(this));
//                });
//                $(this).unbind('mouseenter mouseleave').css({backgroundColor: "#337ab7", color: "#FFF"});
//                var effect_panel_id = $(this).attr("id");
//                dialog.find("input#effect_panel_id").val(effect_panel_id);
//            });
//        });
//
//        dialog.find("select#effect_type").change(function () {
//            var type = $(this).val();
//            dialog.find("div.effect_type").hide();
//            dialog.find("div#effect_type_" + type + ".effect_type").show();
//            switch (type) {
//                case "src":
//                    dialog.find("div.panel-choice").hide();
//                    dialog.find("div.panel-choice.image").show();
//                    break;
//                case "text":
//                    dialog.find("div.panel-choice").hide();
//                    dialog.find("div.panel-choice.text").show();
//                    break;
//                case "css":
//                    dialog.find("div.panel-choice").show();
//                    dialog.find("div.panel-choice.image, div.panel-choice.navigation").hide();
//                    break;
//                case "spin":
//                    dialog.find("div.panel-choice").show();
//                    break;
//            }
//        });
//
//        
//
//        dialog.find("div.row.image-container i.fa-plus-circle").each(function () {
//            $(this).click(function () {
//                $(this).parent().children('.images').toggleClass('hidden');
//                $(this).toggleClass("fa-minus-circle");
//            });
//        });
//
//        dialog.find("div.image-list span.label").click(function () {
//            var name = $(this).text();
//            var url = name;
//            $(this).parents(".images").each(function () {
//                url = $(this).attr("id") + "/" + url;
//            });
//            url = "/images/" + url;
//            dialog.find("div.dialog-panel img").attr("src", url);
//            dialog.find("input#effect_content").val(url);
//        });

        function setHover(item) {
            item.hover(function () {
                $(this).css({backgroundColor: "#337ab7"});
            }, function () {
                $(this).css({backgroundColor: ""});
            });
        }
    }
}

function ajaxCreateCondition(data) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        data: data,
        url: Routing.generate('bms_visualization_create_condition'),
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            console.log(ret);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}