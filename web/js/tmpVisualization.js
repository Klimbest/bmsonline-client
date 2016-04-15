/* global parseInt, parseFloat */
//w przyszłości pobrać z bazy 
var defaultPatternNetSize = 50;

$(document).ready(function () {
//wczytanie strony startowej
    var data = {
        page_id: 1
    };
    //załadowanie strony startowej
    ajaxChangePage(data);
    //ustawienie eventów w bocznym menu
    setSidebarEvents();
});
//ustawienie obsługi przycisków w menu bocznym
function setSidebarEvents() {
    //przycisk dodający stronę
    $("button.btn-add-page").click(function () {
        createDialogPageAddSettings().dialog("open");
    });
    //przycisk dodający panel
    $("button.btn-add-panel").click(function () {
        $.ajax({
            type: "POST",
            datatype: "application/json",
            url: Routing.generate('bms_visualization_load_panel_dialog'),
            success: function (ret) {
                $(".main-row").children(".fa-spinner").remove();
                $(".main-row").append(ret["template"]);
                createPanel().dialog("open");
            }
        });
        $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    });
    //ON/OFF siatka pomocnicza
    $("button.btn-pattern-net").click(function () {
        $(this).children("span").toggleClass('off');
        var state = $(this).children("span").hasClass("off");
        if (state === true) {
            $(".pattern-net, .pattern-net-right").remove();
        } else {
            var gridSize = $("input#pattern-net-size").val();
            if (gridSize.length === 0) {
                setPatternNet(defaultPatternNetSize);
            } else {
                setPatternNet(gridSize);
            }
        }
    });
    //ustaw domyślny rozmiar siatki
    $("input#pattern-net-size").val(defaultPatternNetSize);
    //zmiana rozmiaru siatki
    $("input#pattern-net-size").change(function () {

        var x = $(this).val();
        setPatternNet(x);
    });
    //ON/OFF lista paneli
    $("button.btn-panel-list").click(function () {
        $(this).children("span").toggleClass('off');
        var state = $(this).children("span").hasClass("off");
        if (state === true) {
            $("div.panel-list-container").hide();
        } else {
            $("div.panel-list-container").show();
        }
    });
}

//*****PANEL START*****
function createPanel() {
    return $("div.dialog-panel-settings").dialog({
        autoOpen: false,
        width: $(window).width(),
        height: $(window).height(),
        modal: true,
        buttons: [{
                text: "Zapisz",
                click: function () {
                    var data = new FormData();
                    data.append("page_id", $("div.label-page.active").attr("id"));
                    data.append("type", $("select#panel-type").val());
                    data.append("name", $("form#panel input#panel-name").val());
                    data.append("topPosition", $("form#panel input#topPosition").val());
                    data.append("leftPosition", $("form#panel input#leftPosition").val());
                    data.append("width", $("form#panel input#width").val());
                    data.append("height", $("form#panel input#height").val());
                    data.append("border", $("form#panel input#borderWidth").val() + "px " + $("form#panel select#borderStyle").val() + " " + $("form#panel input#borderColor").val());
                    data.append("backgroundColor", hex2rgba($("form#panel input#backgroundColor").val(), parseFloat($("form#panel input#opacity").val())));
                    data.append("textAlign", $("form#panel div.panel-preview").css("textAlign"));
                    data.append("fontWeight", $("form#panel div.panel-preview").css("fontWeight"));
                    data.append("textDecoration", $("form#panel div.panel-preview").css("textDecoration"));
                    data.append("fontStyle", $("form#panel div.panel-preview").css("fontStyle"));
                    data.append("fontFamily", $("form#panel select#fontFamily").val());
                    data.append("fontSize", $("form#panel select#fontSize").val());
                    data.append("fontColor", $("form#panel input#fontColor").val());
                    data.append("borderRadius", $("form#panel input#borderRadiusTL").val() + "px " + $("form#panel input#borderRadiusTR").val() + "px " + $("form#panel input#borderRadiusBR").val() + "px " + $("form#panel input#borderRadiusBL").val() + "px");
                    data.append("zIndex", 5);
                    data.append("visibility", $("form#panel input#visibility").is(':checked'));
                    data.append("contentSource", $("input#panel-source-content").val());
                    data.append("displayPrecision", $("form#panel select#displayPrecision").val());
                    data.append("href", $("div.dialog-panel-navigation select.pages").val());
                    var fail = false;
                    var fail_log = '';
                    $("input").each(function () {
                        if (!$(this).prop('required')) {

                        } else {
                            if (!$(this).val()) {
                                fail = true;
                                var name = $(this).attr('name');
                                $(this).parent(".form-group").addClass("has-error");
                                fail_log += name + " jest wymagane \n";
                            }
                        }
                    });
                    if (!fail) {
                        saveData(data);
                        $(this).dialog('destroy').remove();
                    } else {
                        alert(fail_log);
                    }
                }
            },
            {
                text: "Anuluj",
                click: function () {
                    $(this).dialog('destroy').remove();
                }
            }],
        open: function () {
            setDialog();
            setDialogButtonsData();
            setDialogButtonsFormat();
            setDialogButtonsNavigation();
        },
        close: function () {
            $(this).dialog('destroy').remove();
        }
    });

    function setDialog() {
        var panel = $("div.panel-preview");
        var br = $("form#panel input#borderRadiusTL").val() + "px " + $("form#panel input#borderRadiusTR").val() + "px " + $("form#panel input#borderRadiusBR").val() + "px " + $("form#panel input#borderRadiusBL").val() + "px";

        var css = {
            //ramka
            borderWidth: $("form#panel input#borderWidth").val() + "px",
            borderColor: $("form#panel input#borderColor").val(),
            borderStyle: $("form#panel select#borderStyle").val(),
            //narożniki
            borderRadius: br,
            //tło
            backgroundColor: hex2rgba($("form#panel input#backgroundColor").val(), parseFloat($("form#panel input#opacity").val())),
            //czcionka
            fontFamily: $("form#panel select#fontFamily").val(),
            fontSize: $("form#panel select#fontSize").val() + "px",
            color: $("form#panel input#fontColor").val(),
            textAlign: "center"
        };
        panel.css(css);
    }
    function saveData(data) {

        $.ajax({
            type: "POST",
            url: Routing.generate('bms_visualization_add_panel'),
            data: data,
            contentType: false,
            processData: false,
            success: function (ret) {
                $(".main-row").children(".fa-spinner").remove();
                $("div.main-row div.well").append(ret['template']);
                loadPanelList(ret["panelList"]);
                setPanelEvents();
            }
        });
        $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();

    }
}
function editPanel(panel_id) {
    return $("div.dialog-panel-settings").dialog({
        autoOpen: false,
        width: $(window).width(),
        height: $(window).height(),
        modal: true,
        buttons: [{
                text: "Zapisz",
                click: function () {
                    var data = new FormData();
                    data.append("panel_id", panel_id);
                    data.append("name", $("form#panel input#panel-name").val());
                    data.append("type", $("select#panel-type").val());
                    data.append("topPosition", $("form#panel input#topPosition").val());
                    data.append("leftPosition", $("form#panel input#leftPosition").val());
                    data.append("width", $("form#panel input#width").val());
                    data.append("height", $("form#panel input#height").val());
                    data.append("border", $("form#panel input#borderWidth").val() + "px " + $("form#panel select#borderStyle").val() + " " + $("form#panel input#borderColor").val());
                    data.append("backgroundColor", hex2rgba($("form#panel input#backgroundColor").val(), parseFloat($("form#panel input#opacity").val())));
                    data.append("textAlign", $("form#panel div.panel-preview").css("textAlign"));
                    data.append("fontWeight", $("form#panel div.panel-preview").css("fontWeight"));
                    data.append("textDecoration", $("form#panel div.panel-preview").css("textDecoration"));
                    data.append("fontStyle", $("form#panel div.panel-preview").css("fontStyle"));
                    data.append("fontFamily", $("form#panel select#fontFamily").val());
                    data.append("fontSize", $("form#panel select#fontSize").val());
                    data.append("fontColor", $("form#panel input#fontColor").val());
                    data.append("borderRadius", $("form#panel input#borderRadiusTL").val() + "px " + $("form#panel input#borderRadiusTR").val() + "px " + $("form#panel input#borderRadiusBR").val() + "px " + $("form#panel input#borderRadiusBL").val() + "px");
                    data.append("zIndex", 5);
                    data.append("visibility", $("form#panel input#visibility").is(':checked'));
                    data.append("contentSource", $("input#panel-source-content").val());
                    data.append("displayPrecision", $("form#panel select#displayPrecision").val());
                    data.append("href", $("div.dialog-panel-navigation select.pages").val());
                    saveData(data);
                    $(this).dialog('destroy').remove();
                }
            }, {
                text: "Anuluj",
                click: function () {
                    $(this).dialog('destroy').remove();
                }
            }],
        open: function () {

            setGeneral();
            setBorder();
            setFont();
            setPreview();
            setDialogButtonsData();
            setDialogButtonsFormat();
            setDialogButtonsNavigation();
            setSource();
        },
        close: function () {
            $(this).dialog('destroy').remove();
        }
    });

    function setSource() {
        var panel = $("div#" + panel_id + ".bms-panel");
        if (panel.hasClass("bms-panel-text")) {
            var c = panel.children("span.bms-panel-content").text();
            $("div.dialog-panel-data select#panel-type").val("text");
            $("div.panel-preview span").empty().append(c);
            $(".input-group-btn button#manager").addClass("disabled");
            $("div.dialog-panel-data input#panel-source-content").val(c).removeAttr("disabled", false);
            $(".precision-group").hide();
            $(".font-group").show();
            $(".input-group-btn button#manager").unbind("click");
        } else if (panel.hasClass("bms-panel-image")) {
            var imgSource = panel.children("img").attr("src");
            $("div.dialog-panel-data select#panel-type").val("image");
            $("div.dialog-panel-settings div.panel-preview").empty().append("<img src=\"" + imgSource + "\" class=\"img-responsive\">");
            $("div.dialog-panel-settings input#panel-source-content").val(imgSource);
            $(".precision-group, .font-group").hide();
            $(".input-group-btn button#manager").unbind("click");
            setOpenImageManager();
        }
    }
    function setGeneral() {
        var panel = $("div#" + panel_id + ".bms-panel");
        $("form#panel input#panel-name").val(panel.attr("title"));
        $("form#panel input#topPosition").val(parseInt(panel.css("top")));
        $("form#panel input#leftPosition").val(parseInt(panel.css("left")));
        $("form#panel input#width").val(parseInt(panel.css("width")));
        $("form#panel input#height").val(parseInt(panel.css("height")));
        if (panel.css("backgroundColor") === "transparent") {
            $("form#panel input#backgroundColor").val("#ffffff");
            $("form#panel input#opacity").val(0);
        } else {
            var bc = getColorValues(panel.css("backgroundColor"));
            $("form#panel input#backgroundColor").val(rgb2hex("rgba(" + bc.red + ", " + bc.green + ", " + bc.blue + ", " + bc.alpha + ")"));
            $("form#panel input#opacity").val(bc.alpha);
        }
    }
    function setBorder() {
        var panel = $("div#" + panel_id + ".bms-panel");
        $("form#panel input#borderWidth").val(parseInt(panel.css("borderTopWidth")));
        $("form#panel input#borderColor").val(rgb2hex(panel.css("borderTopColor")));
        $("form#panel select#borderStyle").val(panel.css("borderTopStyle"));
        $("form#panel input#borderRadiusTL").val(parseInt(panel.css("borderTopLeftRadius")));
        $("form#panel input#borderRadiusTR").val(parseInt(panel.css("borderTopRightRadius")));
        $("form#panel input#borderRadiusBL").val(parseInt(panel.css("borderBottomLeftRadius")));
        $("form#panel input#borderRadiusBR").val(parseInt(panel.css("borderBottomRightRadius")));
    }
    function setFont() {
        var panel = $("div#" + panel_id + ".bms-panel");
        $("form#panel select#fontFamily").val(panel.css("fontFamily"));
        $("form#panel select#fontSize").val(parseInt(panel.css("fontSize")));
        $("form#panel input#fontColor").val(rgb2hex(panel.css("color")));
        if (panel.hasClass("text-left")) {
            $(".btn-align-center, .btn-align-right").removeClass("active");
            $(".btn-align-left").addClass("active");
        } else if (panel.hasClass("text-center")) {
            $(".btn-align-left, .btn-align-right").removeClass("active");
            $(".btn-align-center").addClass("active");
        } else if (panel.hasClass("text-right")) {
            $(".btn-align-center, .btn-align-left").removeClass("active");
            $(".btn-align-right").addClass("active");
        }
        panel.css("fontWeight") === "700" ? $(".btn-bold").addClass("active") : $(".btn-bold").removeClass("active");
        panel.css("textDecoration") === "underline" ? $(".btn-underline").addClass("active") : $(".btn-underline").removeClass("active");
        panel.css("fontStyle") === "italic" ? $(".btn-italic").addClass("active") : $(".btn-italic").removeClass("active");

    }
    function setPreview() {
        var br = $("form#panel input#borderRadiusTL").val() + "px " + $("form#panel input#borderRadiusTR").val() + "px " + $("form#panel input#borderRadiusBR").val() + "px " + $("form#panel input#borderRadiusBL").val() + "px";
        var panel = $("div#" + panel_id + ".bms-panel");
        if ($("button.btn-align-left").hasClass("active")) {
            var ta = "left";
        } else if ($("button.btn-align-center").hasClass("active")) {
            var ta = "center";
        } else if ($("button.btn-align-right").hasClass("active")) {
            var ta = "right";
        }
        var css = {
            //ramka
            borderWidth: $("form#panel input#borderWidth").val() + "px",
            borderColor: $("form#panel input#borderColor").val(),
            borderStyle: $("form#panel select#borderStyle").val(),
            //narożniki
            borderRadius: br,
            //tło
            backgroundColor: hex2rgba($("form#panel input#backgroundColor").val(), parseFloat($("form#panel input#opacity").val())),
            //czcionka
            fontFamily: $("form#panel select#fontFamily").val(),
            fontSize: $("form#panel select#fontSize").val() + "px",
            color: $("form#panel input#fontColor").val(),
            textAlign: ta,
            fontWeight: panel.css("fontWeight"),
            textDecoration: panel.css("textDecoration"),
            fontStyle: panel.css("fontStyle")
        };

        $("div.panel-preview").css(css);
    }
    function saveData(data) {
        var fail = false;
        var fail_log = '';
        $("input").each(function () {
            if (!$(this).prop('required')) {

            } else {
                if (!$(this).val()) {
                    fail = true;
                    var name = $(this).attr('name');
                    fail_log += name + " jest wymagane \n";
                }
            }
        });
        if (!fail) {
            $.ajax({
                type: "POST",
                url: Routing.generate('bms_visualization_edit_panel'),
                data: data,
                contentType: false,
                processData: false,
                success: function (ret) {
                    $(".main-row").children(".fa-spinner").remove();
                    $("div#" + ret["panel_id"] + ".bms-panel").remove();
                    $("div.main-row div.well").append(ret['template']);
                    loadPanelList(ret["panelList"]);
                    setPanelEvents();
                }
            });
            $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
        } else {
            alert(fail_log);

        }
    }
}
function setDialogButtonsData() {
    //zwijanie i rozwijanie sekcji
    $("div.dialog-panel-settings div.nav-row").click(function () {
        $(this).next().find(".well").toggle();
    });
    //panel type
    $("select#panel-type").change(function () {
        var value = $(this).val();
        switch (value) {
            case "variable":
                $("input#panel-source-content").val("").prop("disabled", true).prop("required", true);
                $(".input-group-btn button#manager").removeClass("disabled");
                $(".precision-group, .font-group").show();
                $(".input-group-btn button#image").unbind("click");
                setOpenVariableManager();
                break;
            case "image":
                $("input#panel-source-content").val("").prop("disabled", true).prop("required", false);
                $(".input-group-btn button#manager").removeClass("disabled");
                $(".precision-group, .font-group").hide();
                $(".input-group-btn button#manager").unbind("click");
                setOpenImageManager();
                break;
            case "text":
                $(".input-group-btn button#manager").addClass("disabled");
                $("input#panel-source-content").val("").removeAttr("disabled required");
                $(".precision-group").hide();
                $(".font-group").show();
                $(".input-group-btn button#manager").unbind("click");
                break;
        }
    });
    //zmiana zawartości źródła powoduje wyświetlenie na podglądzie aktualną zawartość
    $("input#panel-source-content").change(function () {
        $("div.panel-preview span").empty().append($(this).val());
    });
    setOpenVariableManager();
}
function setDialogButtonsFormat() {
    var panel = $("div.panel-preview");
    //tło
    $("form#panel input#backgroundColor").on('input', function () {
        var backgroundColor = hex2rgba($(this).val(), parseFloat($("form#panel input#opacity").val()));
        panel.css({backgroundColor: backgroundColor});
    });
    $("form#panel input#opacity").change(changeOpacity).mousemove(changeOpacity);
    function changeOpacity() {
        var backgroundColor = hex2rgba($("form#panel input#backgroundColor").val(), parseFloat($(this).val()));
        panel.css({backgroundColor: backgroundColor});
    }
    //ramka
    $("form#panel input#borderWidth").change(function () {
        var value = $(this).val();
        panel.css({borderWidth: value + "px", lineHeight: (100 - value * 2) + "px"});
    });
    $("form#panel select#borderStyle").change(function () {
        var value = $(this).val();
        panel.css({borderStyle: value});
    });
    $("form#panel input#borderColor").on('input', function () {
        var value = $(this).val();
        panel.css({borderColor: value});
    });
    $("form#panel input#borderRadiusTL").change(changeTL).mousemove(changeTL);
    $("form#panel input#borderRadiusTR").change(changeTR).mousemove(changeTR);
    $("form#panel input#borderRadiusBL").change(changeBL).mousemove(changeBL);
    $("form#panel input#borderRadiusBR").change(changeBR).mousemove(changeBR);
    function changeTL() {
        panel.css({borderTopLeftRadius: $(this).val() + "px"});
    }
    function changeTR() {
        panel.css({borderTopRightRadius: $(this).val() + "px"});
    }
    function changeBL() {
        panel.css({borderBottomLeftRadius: $(this).val() + "px"});
    }
    function changeBR() {
        panel.css({borderBottomRightRadius: $(this).val() + "px"});
    }
    //pogrubienie
    $("form#panel .btn-bold").click(function () {
        $(this).hasClass("active") ? panel.css({fontWeight: "initial"}) : panel.css({fontWeight: "bold"});
        $(this).toggleClass("active");
    });
    //podkreślenie
    $("form#panel .btn-underline").click(function () {
        $(this).hasClass("active") ? panel.css({textDecoration: "initial"}) : panel.css({textDecoration: "underline"});
        $(this).toggleClass("active");
    });
    //pochylenie
    $("form#panel .btn-italic").click(function () {
        $(this).hasClass("active") ? panel.css({fontStyle: "initial"}) : panel.css({fontStyle: "italic"});
        $(this).toggleClass("active");
    });
    //wyrównanie
    $("form#panel .btn-align-left").click(function () {
        $(this).hasClass("active") ? panel.css({textAlign: "auto"}) : panel.css({textAlign: "left"});
        setAlign("left");
    });
    $("form#panel .btn-align-center").click(function () {
        $(this).hasClass("active") ? panel.css({textAlign: "auto"}) : panel.css({textAlign: "center"});
        setAlign("center");
    });
    $("form#panel .btn-align-right").click(function () {
        $(this).hasClass("active") ? panel.css({textAlign: "auto"}) : panel.css({textAlign: "right"});
        setAlign("right");
    });
    function setAlign(align) {
        switch (align) {
            case "left":
                $(".btn-align-center, .btn-align-right").removeClass("active");
                $(".btn-align-left").addClass("active");
                break;
            case "center":
                $(".btn-align-left, .btn-align-right").removeClass("active");
                $(".btn-align-center").addClass("active");
                break;
            case "right":
                $(".btn-align-center, .btn-align-left").removeClass("active");
                $(".btn-align-right").addClass("active");
                break;
        }
    }
    //styl czcionki
    $("form#panel select.font-family").change(function () {

        panel.css({fontFamily: $(this).val()});
    });
    //rozmiar czcionki
    $("form#panel select.font-size").change(function () {
        panel.css({fontSize: $(this).val() + "px"});
    });
    //Kolor
    $("form#panel input#fontColor").on('input', function () {
        panel.css({color: hex2rgba($(this).val(), 1)});
    });
    //precyzja wyświetlania
    $("form#panel select#displayPrecision").change(function () {
        if ($("select#panel-type").val() === "variable") {
            var displayPrecision = $(this).val();
            var value = parseFloat($("div.dialog-panel-settings input#panel-source-value").val());
            value = value.toFixed(displayPrecision);
            panel.children("span").empty().append(value);
        }
    });

}
function setDialogButtonsNavigation() {
    $("input#href").change(function () {

        if ($(this).is(':checked')) {
            $("select.pages").prop("disabled", false);
        } else {
            $("select.pages").prop("disabled", true);
            $("select.pages").val("");
        }
    });
}
function setOpenVariableManager() {
    $(".input-group-btn button#manager").click(function () {
        $.ajax({
            type: "POST",
            datatype: "application/json",
            url: Routing.generate('bms_visualization_load_variable_manager'),
            success: function (ret) {
                $(".main-row").children(".fa-spinner").remove();
                $(".main-row").append(ret["template"]);
                createVariableManager().dialog("open");
            }
        });
        $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    });
}
function setOpenImageManager() {
    $(".input-group-btn button#manager").click(function () {
        $.ajax({
            type: "POST",
            datatype: "application/json",
            url: Routing.generate('bms_visualization_load_image_manager'),
            success: function (ret) {
                $(".main-row").children(".fa-spinner").remove();
                $(".main-row").append(ret["template"]);
                createImageManager().dialog("open");
            }
        });
        $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    });
}

function createVariableManager() {
    return $("div.variable-manager").dialog({
        autoOpen: false,
        width: 700,
        height: 500,
        modal: true,
        buttons: [
            {
                text: "Zapisz",
                click: function () {
                    var value = $("div.variable-manager select.registers").val();
                    var res = value.split("&");
                    $("div.dialog-panel-settings input#panel-source-content").val(res[0]);
                    $("div.dialog-panel-settings div.panel-preview span").empty().append(res[1]);
                    $("div.dialog-panel-settings input#panel-source-value").val(res[1]);
                    $(this).dialog('destroy').remove();
                }
            },
            {
                text: "Anuluj",
                click: function () {
                    $(this).dialog('destroy').remove();
                }
            }],
        open: function () {

        },
        close: function () {
            $(this).dialog('destroy').remove();
        }
    });
}
function createImageManager() {
    var input;
    return $("div.image-manager").dialog({
        autoOpen: false,
        width: $(window).width(),
        height: $(window).height(),
        modal: true,
        buttons: [
            {
                text: "Zapisz",
                click: function () {
                    var w = $("div.image-manager input#resolutionX").val();
                    var h = $("div.image-manager input#resolutionY").val();
                    $("div.dialog-panel-settings div.panel-preview").css({
                        width: w + "px",
                        height: h + "px",
                        backgroundColor: "rgba(0,0,0,0)",
                        borderWidth: "0px"
                    });
                    $("form#panel input#width").val(w);
                    $("form#panel input#height").val(h);
                    $("form#panel input#opacity").val(0);
                    $("form#panel input#borderWidth").val(0);
                    var imgSource = $("div.image-manager div.dialog-panel img").attr("src");
                    if (imgSource.length > 200) {
                        var data = new FormData();
                        data.append('file', input.files[0]);
                        data.append("fileName", $("div.image-manager input#imageName").val());
                        data.append("resolutionX", $("div.image-manager input#resolutionX").val());
                        data.append("resolutionY", $("div.image-manager input#resolutionY").val());
                        saveData(data);
                    } else {
                        $("div.dialog-panel-settings input#panel-source-content").val(imgSource);
                        $("div.dialog-panel-settings div.panel-preview").empty().append("<img src=\"" + imgSource + "\" class=\"img-responsive\">");
                    }

                    $(this).dialog('destroy').remove();
                }
            },
            {
                text: "Anuluj",
                click: function () {
                    $(this).dialog('destroy').remove();
                }
            }],
        open: function () {
            setDialogButtons();
        },
        close: function () {
            $(this).dialog('destroy').remove();
        }
    });

    function setDialogButtons() {
        var dp = $("div.image-manager div.dialog-panel");
        if ($("input#panel-source-content").val()) {
            var src = $("input#panel-source-content").val();
            $("div.image-manager input#imageName").val(src);
            $("div.image-manager input#resolutionX").val(parseInt(dp.css("width")));
            $("div.image-manager input#resolutionY").val(parseInt(dp.css("height")));
            dp.children("img").attr("src", src);
        }
        //loading image from disk
        $("div.image-manager input#image").change(function (event) {
            input = event.target;
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = new Image();
                    img.onload = function () {
                        dp.css({
                            width: this.width,
                            height: this.height
                        }).children("img").attr("src", e.target.result);
                        $("div.image-manager input#resolutionX").val(parseInt(dp.css("width")));
                        $("div.image-manager input#resolutionY").val(parseInt(dp.css("height")));
                    };
                    img.src = e.target.result;

                };
                reader.readAsDataURL(input.files[0]);
            }
            var imgName = input.files[0].name;
            $("div.image-manager input#imageName").val(imgName);

        });
        //load image from server
        $("div.image-manager div.image-list span.label").click(function () {
            var name = $(this).text();
            //var dir = [];
            var url = name;
            $(this).parents(".images").each(function () {
                //dir.push($(this).attr("id"));
                url = $(this).attr("id") + "/" + url;
            });
            url = "/images/" + url;

            var img = new Image();
            img.onload = function () {
                dp.css({
                    width: this.width,
                    height: this.height
                }).children("img").attr("src", url);
                $("div.image-manager input#resolutionX").val(parseInt(dp.css("width")));
                $("div.image-manager input#resolutionY").val(parseInt(dp.css("height")));
            };
            img.src = url;
            dp.children("img").attr("src", url);
            $("div.image-manager input#imageName").val(name);

        });
        //removing image from server
        $("div.image-manager div.image-list i.fa-remove").click(function () {
            var name = $(this).parent().children("span.label").text();
            var data = {
                image_name: name.replace(" ", "")
            };
            ajaxDeleteImage(data);
            $(this).parent().remove();
        });

        //change size of image
        $("div.image-manager input#resolutionX").change(function () {
            var ar = parseInt(dp.css("width")) / parseInt(dp.css("height"));
            var h = $(this).val() / ar;
            $("div.image-manager input#resolutionY").val(Math.round(h));
            dp.css({
                width: $(this).val() + "px",
                height: Math.round(h) + "px"
            });

        });
        $("div.image-manager input#resolutionY").change(function () {
            var ar = parseInt(dp.css("width")) / parseInt(dp.css("height"));
            var w = $(this).val() * ar;
            $("div.image-manager input#resolutionX").val(Math.round(w));
            dp.css({
                width: Math.round(w) + "px",
                height: $(this).val() + "px"
            });
        });
        //sidebar
        var imageListItems = $("div.image-manager div.row.image-container i.fa-plus-circle");
        imageListItems.each(function () {
            $(this).click(function () {
                $(this).parent().children('.images').toggleClass('hidden');
                $(this).toggleClass("fa-minus-circle");
            });
        });
    }
    function saveData(data) {
        $.ajax({
            type: "POST",
            url: Routing.generate('bms_visualization_add_image'),
            data: data,
            contentType: false,
            processData: false,
            success: function (ret) {
                $(".main-row").children(".fa-spinner").remove();

                $("div.dialog-panel-settings input#panel-source-content").val(ret["content"]);
                $("div.dialog-panel-settings div.panel-preview").empty().append("<img src=\"" + ret["content"] + "\" class=\"img-responsive\">");
            }
        });
        $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    }
}

function ajaxMovePanel(data) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_move_panel'),
        data: data,
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            loadPanelList(ret["panelList"]);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}

function copyPanel(data) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_copy_panel'),
        data: data,
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            $("div.main-row div.well").append(ret["template"]);
            $(".main-row").append(ret["dialog"]);
            loadPanelList(ret["panelList"]);

            setPanelEvents();
            editPanel(ret["panel_id"]).dialog("open");
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
function ajaxDeletePanel(data) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_delete_panel'),
        data: data,
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            loadPanelList(ret["panelList"]);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}

//dodanie obsługi zdarzeń na każdym panelu na stronie
function setPanelEvents() {
    var panels = $(".bms-panel");

    panels.each(function () {
        //pobranie id panelu 
        var id = $(this).attr("id");
        var aR;
        if ($(this).children("img").length > 0) {
            aR = true;
        } else {
            aR = false;
        }

        //usunięcie starych eventów
        $(this).removeAttr("onclick");
        $(this).unbind("mouseenter mouseleave");
        //draggable and resizable
        $(this).draggable({
            containment: "parent",
            snap: ".pattern-net",
            snapTolerance: 10,
            snapMode: "both",
            distance: 5,
            stop: function (event, ui) {
                var data = {
                    panel_id: id,
                    topPosition: ui.helper.css("top"),
                    leftPosition: ui.helper.css("left"),
                    width: ui.helper.css("width"),
                    height: ui.helper.css("height"),
                    zIndex: ui.helper.css("z-index")
                };
                ajaxMovePanel(data);
            }
        }).resizable({
            containment: "parent",
            snap: ".pattern-net",
            snapTolerance: 10,
            snapMode: "both",
            aspectRatio: aR,
            handles: "se",
            resize: function (event, ui) {
                if (ui.element.hasClass("image-panel")) {
                    var image = $(this).children("img");
                    var mW = image[0].naturalWidth;
                    if (ui.size.width > mW) {
                        ui.size.width = mW;
                    }
                    var mH = image[0].naturalHeight;
                    if (ui.size.height > mH) {
                        ui.size.height = mH;
                    }
                } else {
                    var bw = ui.element.css("border-top-width");
                    bw = parseInt(bw);
                    var delta_x = ui.size.width - (ui.originalSize.width + 2 * bw);
                    var delta_y = ui.size.height - (ui.originalSize.height + 2 * bw);
                    if (delta_x !== 0) {
                        ui.size.width += 2 * bw;
                    }
                    if (delta_y !== 0) {
                        ui.size.height += 2 * bw;
                    }
                    ui.element.css({lineHeight: ui.element.height() + "px"});
                }
                ui.element.addClass("hover");
            },
            stop: function (event, ui) {
                ui.element.removeClass("hover");
                var data = {
                    panel_id: id,
                    topPosition: ui.helper.css("top"),
                    leftPosition: ui.helper.css("left"),
                    width: ui.element.css("width"),
                    height: ui.element.css("height"),
                    zIndex: ui.element.css("zIndex")
                };
                ajaxMovePanel(data);
            }
        });
        $(this).hover(function () {
            $(".panel-list-container div#" + id + " span.label").css({backgroundColor: "#FF0000"});
        }, function () {
            if ($("div#" + id + ".panel-list").hasClass("active")) {

            } else {
                $(".panel-list-container div#" + id + " span.label").css({backgroundColor: ""});
            }
        });
        $(this).click(function (e) {
            $(this).css({zIndex: 100});
            if ($("span.label-bms-panel").length > 0) {
                var relX = parseInt($("span.label-bms-panel").css("left"));
                var relY = parseInt($("span.label-bms-panel").css("top"));
            } else {
                var relX = e.pageX - $(this).offset().left;
                var relY = e.pageY - $(this).offset().top;
                if ((parseInt($(this).css("left"))) + relX > parseInt($(this).parent().css("width")) / 2) {
                    relX = relX - 115;
                }
                if ((parseInt($(this).css("top"))) + relY > parseInt($(this).parent().css("height")) / 2) {
                    relY = relY - 40;
                }
            }
            $("span.label-bms-panel").remove();
            var label = "<span id=" + id + " class='label label-bms-panel' \n\
                               style='top: " + relY + "px; \n\
                                      left: " + relX + "px;\n\
                                      font-size: initial;\n\
                                      font-weight: initial;\n\
                                      text-decoration: none;\n\
                                      font-style: initial;'>\n\
                            <div>\n\
                                " + id + "\n\
                                <i class='fa fa-fw fa-clone fa-blue'></i>\n\
                                <i class='fa fa-fw fa-cogs fa-yellow'></i>\n\
                                <i class='fa fa-fw fa-remove fa-red'></i>\n\
                            </div>\n\
                        </span>";
            $(this).append(label);
            setPanelLabelEvents(id);
        });
        $(this).mouseleave(function () {
            var zI = $(".panel-list-container div#" + id + " span.label").attr("value");
            $(this).css({zIndex: zI});
            $("span.label-bms-panel").remove();
        });
    });
    //ustawienie przełączania między stronami
    function setPanelLabelEvents(id) {
        var label = "span#" + id + ".label-bms-panel";
        $(label).unbind("click");
        //kopiowania
        $(label + " i.fa-clone").click(function () {
            var data = {
                panel_id: id
            };
            copyPanel(data);
        });
        //ustawienia
        $(label + " i.fa-cogs").click(function () {
            var panel = $("div#" + id + ".bms-panel");
            if (panel.hasClass("bms-panel-variable")) {
                var rid = panel.children("span").attr("id");
                var data = {
                    reg_id: rid,
                    panel_id: id
                };
            } else {
                var data = {
                    panel_id: id
                };
            }
            $.ajax({
                type: "POST",
                datatype: "application/json",
                url: Routing.generate('bms_visualization_load_panel_dialog'),
                data: data,
                success: function (ret) {
                    $(".main-row").children(".fa-spinner").remove();
                    $(".main-row").append(ret["template"]);

                    editPanel(id).dialog("open");
                    if (panel.hasClass("bms-panel-variable")) {
                        $("div.dialog-panel-settings input#panel-source-content").val(ret["register"].name);
                        $("div.dialog-panel-settings div.panel-preview span").empty().append(ret["register"].value);
                        $("div.dialog-panel-settings input#panel-source-value").val(ret["register"].value);
                    }
                }
            });
            $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
        });
        //usuwanie
        $(label + " i.fa-remove").click(function () {
            if (confirm("Na pewno chcesz usunąć ten panel? Zostaną usunięte również wszystkie zdarzenia przypisane do tego panelu.")) {
                $("div#" + id + ".bms-panel").remove();
                var data = {
                    panel_id: id
                };
                ajaxDeletePanel(data);
            }
        });
    }

}

//*****PAGE START*****
function createDialogPageAddSettings() {

    return $("div.dialog-page-add-settings").dialog({
        autoOpen: false,
        height: 300,
        width: 450,
        modal: true,
        buttons: [
            {
                text: "Dodaj",
                click: function () {
                    var data = {
                        width: $("div.dialog-page-add-settings input#width").val(),
                        height: $("div.dialog-page-add-settings input#height").val(),
                        name: $("div.dialog-page-add-settings input#name").val()
                    };
                    ajaxAddPage(data);
                    $(this).dialog("close");
                }
            },
            {
                text: "Anuluj",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        open: function () {
            if (page_id !== null) {
                setFormField();
            }
        },
        close: function () {
            $(this).dialog("close");
        }
    });
    function setFormField() {
        var width = parseInt($('div.main-row div.well').css("width"));
        var height = parseInt($('div.main-row div.well').css("height"));
        var name = $('div.label-page.active span#name').text();
        $("div.dialog-page-add-settings input#width").val(width);
        $("div.dialog-page-add-settings input#height").val(height);
        $("div.dialog-page-add-settings input#name").val(name);
    }
}
function createDialogPageEditSettings(page_id) {

    return $("div.dialog-page-edit-settings").dialog({
        autoOpen: false,
        height: 300,
        width: 450,
        modal: true,
        buttons: [
            {
                text: "Zapisz",
                click: function () {
                    var data = {
                        page_id: page_id,
                        width: $("div.dialog-page-edit-settings input#width").val(),
                        height: $("div.dialog-page-edit-settings input#height").val(),
                        name: $("div.dialog-page-edit-settings input#name").val()
                    };
                    ajaxEditPage(data);
                    $(this).dialog("close");
                }
            },
            {
                text: "Anuluj",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        open: function () {
            if (page_id !== null) {
                setFormField();
            }
        },
        close: function () {
            $(this).dialog("close");
        }
    });
    function setFormField() {
        var width = parseInt($('div.main-row div.well').css("width"));
        var height = parseInt($('div.main-row div.well').css("height"));
        var name = $('div.label-page.active span#name').text();
        $("div.dialog-page-edit-settings input#width").val(width);
        $("div.dialog-page-edit-settings input#height").val(height);
        $("div.dialog-page-edit-settings input#name").val(name);
    }
}

function ajaxAddPage(data) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_add_page'),
        data: data,
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            createPage(ret["ret"]);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
function ajaxDeletePage(data) {
    $.ajax({
        type: "POST",
        url: Routing.generate('bms_visualization_delete_page'),
        data: data,
        success: function () {
            $(".main-row").children(".fa-spinner").remove();
            var data = {
                page_id: 1
            };
            ajaxChangePage(data);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
function ajaxEditPage(data) {
    $.ajax({
        type: "POST",
        url: Routing.generate('bms_visualization_edit_page'),
        data: data,
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            var data = {
                page_id: ret['page_id']
            };
            ajaxChangePage(data);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
function ajaxChangePage(data) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_change_page'),
        data: data,
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            createPage(ret["template"]);
            loadPanelList(ret["panelList"]);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();

    function createPage(content) {
        $(".main-row div.col-md-12").children().remove();
        $(".main-row div.col-md-12").append(content).fadeIn("slow");
        setPatternNet($("input#pattern-net-size").val());
        setPageLabelsEvent();

        setPanelEvents();
    }
    //przełączanie zakładek stron
    function setPageLabelsEvent() {
        var pageLabels = $(".label-page");
        pageLabels.unbind("click");
        pageLabels.each(function () {
            var id = $(this).attr("id");
            $(this).click(function () {
                var data = {
                    page_id: id
                };
                ajaxChangePage(data);
            });
            if ($(this).hasClass("active")) {
                $(this).unbind("click");
            }
            deletePageEvent($(this), id);
            editPageEvent($(this), id);
        });
        //usuwanie strony
        function deletePageEvent(label, page_id) {
            label.children("i.fa-remove").click(function () {
                var data = {
                    page_id: page_id
                };
                ajaxDeletePage(data);
                label.remove();
                $("div.main-row div.well").remove();
            });
        }
        //edycja strony
        function editPageEvent(label, page_id) {
            label.children("i.fa-cogs").click(function () {
                createDialogPageEditSettings(page_id).dialog("open");
            });
        }


    }

}
//*****PAGE END*****

//****TOOLS START*****
//ustaw siatkę pomocniczą
function setPatternNet(x) {

    var state = $("button.btn-pattern-net span").hasClass("off");
    if (state === false) {
        $(".pattern-net, .pattern-net-right").remove();
        var divItem;
        if (x.length === 0) {
            x = defaultPatternNetSize;
        } else if (x < 25) {
            x = 25;
        }
        var mainWell = $("div.main-row div.well");
        var nx = mainWell.width() / x,
                ny = mainWell.height() / x;
        for (var i = 0; i < Math.floor(nx) * Math.floor(ny) + Math.floor(nx); i++) {
            divItem = "<div id=" + i + " class='pattern-net'></div>";
            mainWell.append(divItem);
            $("div#" + i + ".pattern-net").width(x).height(x);
        }
        for (i = 0; i < ny; i++) {
            divItem = "<div id=" + i + " class='pattern-net-right'></div>";
            mainWell.append(divItem);
            $("div#" + i + ".pattern-net-right").height(x);
        }
    }
}
//usuń obrazek z serwera
function ajaxDeleteImage(data) {
    $.ajax({
        type: "POST",
        url: Routing.generate('bms_visualization_delete_image'),
        data: data,
        success: function () {
            $(".main-row").children(".fa-spinner").remove();
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
//załaduj listę paneli
function loadPanelList(panelList) {
    $("input#panel-list-text, input#panel-list-image, input#panel-list-variable").prop("checked", true).unbind("click, change");
    $("div.bms-panel").removeClass("active");
    //załaduj panele na listę
    $("div.panel-list-container").empty().append(panelList).show();
    //ukryj pokaż panele typu text na liście
    $('input#panel-list-text').change(function () {
        $(this).is(':checked') ? $("span.panel-list-text").parent().parent().parent("div.panel-list").show() : $("span.panel-list-text").parent().parent().parent("div.panel-list").hide();
    });
    //ukryj pokaż panele typu image na liście
    $('input#panel-list-image').change(function () {
        $(this).is(':checked') ? $("span.panel-list-image").parent().parent().parent("div.panel-list").show() : $("span.panel-list-image").parent().parent().parent("div.panel-list").hide();
    });
    //ukryj pokaż panele typu variable na liście
    $('input#panel-list-variable').change(function () {
        $(this).is(':checked') ? $("span.panel-list-variable").parent().parent().parent("div.panel-list").show() : $("span.panel-list-variable").parent().parent().parent("div.panel-list").hide();
    });
    //obsługa najechania na panel na liscie
    $('div.panel-list').hover(function () {
        var id = $(this).attr("id");
        $("div#" + id + ".bms-panel").addClass("active");
        $(this).find("span").css({backgroundColor: "#FF0000", width: "20px"});
        $(this).find("i.icon-type").hide();
        $(this).find("div.panel-list-controls").show();
        $(this).find("div.panel-list-label").removeClass("col-md-12").addClass("col-md-5").css({overflow: "hidden"});
    }, function () {
        var id = $(this).attr("id");
        $(this).find("span").css({backgroundColor: "", width: ""});

        $(this).find("div.panel-list-label").css({overflow: ""});
        if ($(this).hasClass("active")) {

        } else {
            $(this).find("div.panel-list-label").removeClass("col-md-5").addClass("col-md-12");
            $(this).find("i.icon-type").show();
            $(this).find("div.panel-list-controls").hide();
            $("div#" + id + ".bms-panel").removeClass("active");
        }

    });
    //obsługa zaznaczania paneli na liście
    $("div.panel-list").click(function () {
        $(this).toggleClass("active");
    });
    //edycja kolejności w górę
    $("div.panel-list i.fa-arrow-up").click(function () {
        var id = $(this).parent().parent().parent().attr("id");
        var panel = $("div#" + id + ".bms-panel");
        var zIndex = $(this).parent().parent().parent().find("span").attr("value");
        zIndex++;
        $("div#" + id + ".bms-panel").css({zIndex: zIndex});
        $(this).parent().parent().parent().find("span").attr("value", zIndex);
        var data = {
            panel_id: id,
            topPosition: panel.css("top"),
            leftPosition: panel.css("left"),
            width: panel.css("width"),
            height: panel.css("height"),
            zIndex: panel.css("zIndex")
        };
        ajaxMovePanel(data);
    });
    //edycja kolejności w dół
    $("div.panel-list i.fa-arrow-down").click(function () {
        var id = $(this).parent().parent().parent().attr("id");
        var panel = $("div#" + id + ".bms-panel");
        var zIndex = $(this).parent().parent().parent().find("span").attr("value");
        zIndex--;
        if (zIndex < 0) {
            zIndex = 0;
        }
        $("div#" + id + ".bms-panel").css({zIndex: zIndex});
        var data = {
            panel_id: id,
            topPosition: panel.css("top"),
            leftPosition: panel.css("left"),
            width: panel.css("width"),
            height: panel.css("height"),
            zIndex: panel.css("zIndex")
        };
        ajaxMovePanel(data);
    });
    //kopiowanie
    $("div.panel-list i.fa-clone").click(function () {
        var id = $(this).parent().parent().parent().attr("id");
        var data = {
            panel_id: id
        };
        copyPanel(data);
    });
    //ustawienia
    $("div.panel-list i.fa-cogs").click(function () {
        var id = $(this).parent().parent().parent().attr("id");
        var panel = $("div#" + id + ".bms-panel");
        if (panel.hasClass("bms-panel-variable")) {
            var rid = panel.children("span").attr("id");
            var data = {
                reg_id: rid,
                panel_id: id
            };
        } else {
            var data = {
                panel_id: id
            };
        }
        $.ajax({
            type: "POST",
            datatype: "application/json",
            url: Routing.generate('bms_visualization_load_panel_dialog'),
            data: data,
            success: function (ret) {
                $(".main-row").children(".fa-spinner").remove();
                $(".main-row").append(ret["template"]);

                editPanel(id).dialog("open");
                if (panel.hasClass("bms-panel-variable")) {
                    $("div.dialog-panel-settings input#panel-source-content").val(ret["register"].name);
                    $("div.dialog-panel-settings div.panel-preview span").empty().append(ret["register"].value);
                    $("div.dialog-panel-settings input#panel-source-value").val(ret["register"].value);
                }
            }
        });
        $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    });
    //usuwanie
    $("div.panel-list i.fa-remove").click(function () {
        var id = $(this).parent().parent().parent().attr("id");
        if (confirm("Na pewno chcesz to usunąć?")) {
            $("div#" + id + ".bms-panel").remove();
            var data = {
                panel_id: id
            };
            ajaxDeletePanel(data);
        }
    });
}

function rgb2hex(orig) {
    var rgb = orig.replace(/\s/g, '').match(/^rgba?\((\d+),(\d+),(\d+)/i);
    return (rgb && rgb.length === 4) ? "#" +
            ("0" + parseInt(rgb[1], 10).toString(16)).slice(-2) +
            ("0" + parseInt(rgb[2], 10).toString(16)).slice(-2) +
            ("0" + parseInt(rgb[3], 10).toString(16)).slice(-2) : orig;
}
function hex2rgba(hex, opacity) {
    hex = hex.replace('#', '');
    r = parseInt(hex.substring(0, 2), 16);
    g = parseInt(hex.substring(2, 4), 16);
    b = parseInt(hex.substring(4, 6), 16);
    result = 'rgba(' + r + ',' + g + ',' + b + ',' + opacity + ')';
    return result;
}
function getColorValues(color) {
    var values = {red: null, green: null, blue: null, alpha: null};
    if (typeof color === 'string') {
        /* hex */
        if (color.indexOf('#') === 0) {
            color = color.substr(1)
            if (color.length == 3)
                values = {
                    red: parseInt(color[0] + color[0], 16),
                    green: parseInt(color[1] + color[1], 16),
                    blue: parseInt(color[2] + color[2], 16),
                    alpha: 1
                }
            else
                values = {
                    red: parseInt(color.substr(0, 2), 16),
                    green: parseInt(color.substr(2, 2), 16),
                    blue: parseInt(color.substr(4, 2), 16),
                    alpha: 1
                }
            /* rgb */
        } else if (color.indexOf('rgb(') === 0) {
            var pars = color.indexOf(',');
            values = {
                red: parseInt(color.substr(4, pars)),
                green: parseInt(color.substr(pars + 1, color.indexOf(',', pars))),
                blue: parseInt(color.substr(color.indexOf(',', pars + 1) + 1, color.indexOf(')'))),
                alpha: 1
            }
            /* rgba */
        } else if (color.indexOf('rgba(') === 0) {
            var pars = color.indexOf(','),
                    repars = color.indexOf(',', pars + 1);
            values = {
                red: parseInt(color.substr(5, pars)),
                green: parseInt(color.substr(pars + 1, repars)),
                blue: parseInt(color.substr(color.indexOf(',', pars + 1) + 1, color.indexOf(',', repars))),
                alpha: parseFloat(color.substr(color.indexOf(',', repars + 1) + 1, color.indexOf(')')))
            }
            /* verbous */
        } else {
            var stdCol = {acqua: '#0ff', teal: '#008080', blue: '#00f', navy: '#000080',
                yellow: '#ff0', olive: '#808000', lime: '#0f0', green: '#008000',
                fuchsia: '#f0f', purple: '#800080', red: '#f00', maroon: '#800000',
                white: '#fff', gray: '#808080', silver: '#c0c0c0', black: '#000'};
            if (stdCol[color] !== undefined)
                values = getColorValues(stdCol[color]);
        }
    }
    return values;
}