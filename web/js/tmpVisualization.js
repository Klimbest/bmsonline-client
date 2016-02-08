/* global parseInt */
//w przyszłości pobrać z bazy 
var defaultPatternNetSize = 150;
var tid = $("div#target_id.hidden").text();

$(document).ready(function () {
//wczytanie strony startowej
    var data = {
        page_id: 1
    };
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
    //dodawanie panel obszaru
    $("button.btn-add-area-panel").click(function () {
        var page_id = $(".label-page.active").attr("id");
        var type = "area";
        var data = {
            type: type,
            page_id: page_id
        };
        ajaxAddPanel(data);
    });
    $("button.btn-add-text-panel").click(function () {

        var page_id = $(".label-page.active").attr("id");
        var type = "text";
        var data = {
            type: type,
            page_id: page_id
        };
        ajaxAddPanel(data);
    });
    $("button.btn-add-image-panel").click(function () {
        ajaxLoadImageSettingsPanel();
    });
    $("button.btn-add-variable-panel").click(function () {
        var page_id = $(".label-page.active").attr("id");
        var type = "variable";
        var data = {
            type: type,
            page_id: page_id
        };
        ajaxAddPanel(data);
    });
    $("button.btn-add-navigation-panel").click(function () {
        var page_id = $(".label-page.active").attr("id");
        var type = "navigation";
        var data = {
            type: type,
            page_id: page_id
        };
        ajaxAddPanel(data);
    });
    //****ADD PANELS END
    //****GRID START 
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
    //****GRID END    
    //ON/OFF lista paneli
    $("button.btn-panel-list").click(function () {
        $(this).children("span").toggleClass('off');
        var state = $(this).children("span").hasClass("off");
        if (state === true) {

            $("div.panel-list-container").hide().empty();
        } else {
            $("div.panel-list-container").show();
            var d = {
                page_id: $("div.label-page.active").attr("id")
            };
            ajaxLoadPanelList(d);
        }
    });
}

//****SETTING DIALOGS START
//PAGE ADD SETTINGS
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
//PAGE EDIT SETTINGS
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
//AREA PANEL SETTINGS
function createDialogAreaPanelSettings(id) {

    return $("div.dialog-area-panel-settings").dialog({
        autoOpen: false,
        width: 900,
        height: 700,
        modal: true,
        buttons: [
            {
                text: "Zapisz",
                click: function () {
                    var br = $(".dialog-panel").css("border-top-left-radius") + " " + $(".dialog-panel").css("border-top-right-radius") + " " + $(".dialog-panel").css("border-bottom-right-radius") + " " + $(".dialog-panel").css("border-bottom-left-radius");
                    var data = {
                        panel_id: id,
                        topPosition: $("div.dialog-area-panel-settings input#topPosition").val(),
                        leftPosition: $("div.dialog-area-panel-settings input#leftPosition").val(),
                        width: $("div.dialog-area-panel-settings input#width").val(),
                        height: $("div.dialog-area-panel-settings input#height").val(),
                        borderWidth: $("div.dialog-area-panel-settings input#borderWidth").val(),
                        borderStyle: $("div.dialog-area-panel-settings select#borderStyle option:selected").val(),
                        borderColor: hex2rgba($("div.dialog-area-panel-settings input#borderColor").val(), 1),
                        borderRadius: br,
                        backgroundColor: hex2rgba($("div.dialog-area-panel-settings input#backgroundColor").val(), parseFloat($("div.dialog-area-panel-settings input#opacity").val()))
                    };
                    ajaxEditAreaPanel(data);
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
            setFormField();
            setEvents();
        },
        close: function () {

            $(this).dialog("close");
        }
    });
    function setFormField() {
        var panel = $("div#" + id + ".area-panel");
        $("div.dialog-panel").css({
            borderWidth: panel.css("border-top-width"),
            borderColor: panel.css("border-top-color"),
            borderStyle: panel.css("border-top-style"),
            borderTopLeftRadius: panel.css("border-top-left-radius"),
            borderTopRightRadius: panel.css("border-top-right-radius"),
            borderBottomRightRadius: panel.css("border-bottom-right-radius"),
            borderBottomLeftRadius: panel.css("border-bottom-left-radius"),
            backgroundColor: panel.css("background-color")

        });
        //Position
        $("input#topPosition").val(parseInt(panel.css("top")));
        $("input#leftPosition").val(parseInt(panel.css("left")));
        //Border
        $("input#borderWidth").val(parseInt(panel.css("border-top-width")));
        $("input#borderColor").val(rgb2hex(panel.css("border-top-color")));
        $("select#borderStyle").val(panel.css("border-top-style"));
        //Border Radius
        //TL
        $("input#borderRadiusTL").val(parseInt(panel.css("border-top-left-radius")));
        $("label#borderRadiusTL").empty().append($("input#borderRadiusTL").val());
        //TR
        $("input#borderRadiusTR").val(parseInt(panel.css("border-top-right-radius")));
        $("label#borderRadiusTR").empty().append($("input#borderRadiusTR").val());
        //BR
        $("input#borderRadiusBR").val(parseInt(panel.css("border-bottom-right-radius")));
        $("label#borderRadiusBR").empty().append($("input#borderRadiusBR").val());
        //BL
        $("input#borderRadiusBL").val(parseInt(panel.css("border-bottom-left-radius")));
        $("label#borderRadiusBL").empty().append($("input#borderRadiusBL").val());
        //Size
        $("input#width").val(parseInt(panel.css("width")));
        $("input#height").val(parseInt(panel.css("height")));
        //Background
        $("input#backgroundColor").val(rgb2hex(panel.css("background-color")));
        $("input#opacity").val(parseFloat(getColorValues(panel.css("background-color"))["alpha"]));
    }
    function setEvents() {
        var panel = $(".dialog-area-panel-settings div.dialog-panel");
        //kolor
        var backgroundColor;
        $(".dialog-area-panel-settings input#backgroundColor").on('input', function () {
            backgroundColor = hex2rgba($(this).val(), parseFloat($(".dialog-area-panel-settings input#opacity").val()));
            panel.css({backgroundColor: backgroundColor});
        });
        $(".dialog-area-panel-settings input#opacity").change(function () {
            backgroundColor = hex2rgba($(".dialog-area-panel-settings input#backgroundColor").val(), parseFloat($(this).val()));
            panel.css({backgroundColor: backgroundColor});
        });
        $(".dialog-area-panel-settings input#borderWidth").change(function () {
            panel.css({borderWidth: $(this).val()});
        });
        $(".dialog-area-panel-settings select#borderStyle").change(function () {
            panel.css({borderStyle: $(this).val()});
        });
        $(".dialog-area-panel-settings input#borderColor").on('input', function () {
            panel.css({borderColor: hex2rgba($(this).val(), 1)});
        });
        //narożniki
        $(".dialog-area-panel-settings input#borderRadiusTL").change(changeTL).mousemove(changeTL);
        $(".dialog-area-panel-settings input#borderRadiusTR").change(changeTR).mousemove(changeTR);
        $(".dialog-area-panel-settings input#borderRadiusBL").change(changeBL).mousemove(changeBL);
        $(".dialog-area-panel-settings input#borderRadiusBR").change(changeBR).mousemove(changeBR);
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


    }
}
//TEXT PANEL SETTINGS
function createDialogTextPanelSettings(id) {
    return $("div.dialog-text-panel-settings").dialog({
        autoOpen: false,
        width: 900,
        height: 700,
        modal: true,
        buttons: [
            {
                text: "Zapisz",
                click: function () {
                    var textarea = $(".dialog-text-panel-settings textarea");
                    var data = {
                        panel_id: id,
                        content: textarea.val(),
                        topPosition: $("div.dialog-text-panel-settings input#topPosition").val(),
                        leftPosition: $("div.dialog-text-panel-settings input#leftPosition").val(),
                        width: textarea.css("width"),
                        height: textarea.css("height"),
                        textAlign: textarea.css("textAlign"),
                        fontWeight: textarea.css("fontWeight"),
                        textDecoration: textarea.css("textDecoration"),
                        fontStyle: textarea.css("fontStyle"),
                        fontFamily: textarea.css("fontFamily"),
                        fontSize: textarea.css("fontSize"),
                        fontColor: textarea.css("color")
                    };
                    ajaxEditTextPanel(data);
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
            setDialog(id);
        },
        close: function () {
            $(this).dialog("close");
        }
    });
    function setDialog(id) {
        var panel = $("div#" + id + ".text-panel");
        var text = panel.children("span.content").text();
        var textarea = $(".dialog-text-panel-settings textarea");
        textarea.focus().val(text.trim()).css({
            width: panel.css("width"),
            height: panel.css("height"),
            textAlign: panel.css("textAlign"),
            fontWeight: panel.css("fontWeight"),
            textDecoration: panel.css("textDecoration"),
            fontStyle: panel.css("fontStyle"),
            fontFamily: panel.css("fontFamily"),
            fontSize: panel.css("fontSize"),
            color: panel.css("color"),
            lineHeight: panel.css("lineHeight")
        });

        //Position
        $("input#topPosition").val(parseInt(panel.css("top")));
        $("input#leftPosition").val(parseInt(panel.css("left")));
        //Size
        $("input#width").val(parseInt(panel.css("width")));
        $("input#height").val(parseInt(panel.css("height")));
        //Kolor
        $("input#fontColor").val(rgb2hex(panel.css("color")));
        $("select.font-family").val(textarea.css("fontFamily"));
        $("select.font-size").val(parseInt(textarea.css("fontSize")));
        textarea.css("fontWeight") === "700" ? $(".btn-bold").addClass("active") : $(".btn-bold").removeClass("active");
        textarea.css("textDecoration") === "underline" ? $(".btn-underline").addClass("active") : $(".btn-underline").removeClass("active");
        textarea.css("fontStyle") === "italic" ? $(".btn-italic").addClass("active") : $(".btn-italic").removeClass("active");
        setDialogButtons();
    }

    function setDialogButtons() {
        $("div.dialog-text-panel-settings div.edit-controls div").children().unbind("click");
        var textarea = $("div.dialog-text-panel-settings textarea");

        setAlign(textarea.css("text-align"));

        //pogrubienie
        $(".btn-bold").click(function () {
            $(this).hasClass("active") ? textarea.css({fontWeight: "initial"}) : textarea.css({fontWeight: "bold"});
            $(this).toggleClass("active");
        });
        //podkreślenie
        $(".btn-underline").click(function () {
            $(this).hasClass("active") ? textarea.css({textDecoration: "initial"}) : textarea.css({textDecoration: "underline"});
            $(this).toggleClass("active");
        });
        //pochylenie
        $(".btn-italic").click(function () {
            $(this).hasClass("active") ? textarea.css({fontStyle: "initial"}) : textarea.css({fontStyle: "italic"});
            $(this).toggleClass("active");
        });
        //wyrównanie do lewej
        $(".btn-align-left").click(function () {
            $(this).hasClass("active") ? textarea.css({textAlign: "auto"}) : textarea.css({textAlign: "left"});
            setAlign("left");
        });
        //wyrównanie do środka
        $(".btn-align-center").click(function () {
            $(this).hasClass("active") ? textarea.css({textAlign: "auto"}) : textarea.css({textAlign: "center"});
            setAlign("center");
        });
        //wyrównanie do prawej
        $(".btn-align-right").click(function () {
            $(this).hasClass("active") ? textarea.css({textAlign: "auto"}) : textarea.css({textAlign: "right"});
            setAlign("right");
        });
        //styl czcionki
        $("select.font-family").change(function () {

            textarea.css({fontFamily: $(this).val()});
        });
        //rozmiar czcionki
        $("select.font-size").change(function () {
            textarea.css({fontSize: $(this).val() + "px"});
        });
        //size
        $("input#width").change(function () {
            textarea.css({width: $(this).val() + "px"});
        });
        $("input#height").change(function () {
            textarea.css({height: $(this).val() + "px"});
            textarea.css({lineHeight: $(this).val() + "px"});
        });
        //Kolor
        $("input#fontColor").on('input', function () {
            textarea.css({color: hex2rgba($(this).val(), 1)});
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

    }

}
//IMAGE PANEL SETTINGS
function createDialogImagePanelEdit() {

}
function createDialogImagePanelSettings() {
    var canvas = document.getElementById('myCanvas');
    var context = canvas.getContext('2d');
    var imageObj = new Image();
    var id;
    var ar = canvas.width / canvas.height;
    return $("div.dialog-image-panel-settings").dialog({
        autoOpen: false,
        width: 1100,
        height: 700,
        modal: true,
        buttons: [
            {
                text: "Zapisz",
                click: function () {
                    var page_id = $(".label-page.active").attr("id");
                    var type = "image";
                    var data = {
                        type: type,
                        page_id: page_id,
                        file: canvas.toDataURL(),
                        fileName: $("input#imageName").val()
                    };
                    ajaxAddPanel(data);
                    $(this).dialog('destroy').remove();
                }
            },
            {
                text: "Usuń",
                click: function () {
                    $(this).dialog('destroy').remove();
                    $("div#" + id + ".bms-panel").remove();
                    var data = {
                        panel_id: id
                    };
                    ajaxDeletePanel(data);
                }
            }],
        open: function () {
            setDialog(id);
        },
        close: function () {
            $(this).dialog('destroy').remove();
        }
    });
    function setDialog(id) {
        var panelImg = $("div#" + id + ".image-panel").children("img").attr("src");
        loadCanvas(panelImg);
        $("input#topPosition").val(0);
        $("input#leftPosition").val(0);
        $("input#width").val(canvas.width);
        $("input#height").val(canvas.height);
        setEvents();
        function loadCanvas(dataURL) {

            imageObj.onload = function () {
                var pageW = $("div.main-row div.well").width();
                var pageH = $("div.main-row div.well").height();
                var aspectRatio = imageObj.naturalHeight / imageObj.naturalHeight;
                //obrazek mniejszy niż strona
                if (imageObj.naturalWidth <= pageW && imageObj.naturalHeight <= pageH) {
                    canvas.width = imageObj.naturalWidth;
                    canvas.height = imageObj.naturalHeight;
                    context.drawImage(this, 0, 0);
                    //obrazek szerszy
                } else if (imageObj.naturalWidth > pageW && imageObj.naturalHeight <= pageH) {

                    canvas.width = pageW;
                    var imageH = pageW * imageObj.naturalHeight / imageObj.naturalWidth;
                    canvas.height = imageH;
                    context.drawImage(this, 0, 0, pageW, imageH);
                    //obrazek wyższy
                } else if (imageObj.naturalWidth <= pageW && imageObj.naturalHeight > pageH) {

                    canvas.height = pageH;
                    var imageW = pageH * imageObj.naturalWidth / imageObj.naturalHeight;
                    canvas.width = imageW;
                    context.drawImage(this, 0, 0, imageW, pageH);
                    //obrazek szerszy i wyższy
                } else {
                    var w = $("canvas#myCanvas").parent().width();
                    canvas.width = w;
                    canvas.height = 450;
                    context.drawImage(this, 0, 0, w);
                }


                ar = canvas.width / canvas.height;
                $("input#width").val(canvas.width);
                $("input#height").val(canvas.height);
            };
            imageObj.src = dataURL;
        }
        function setEvents() {
            $("input#image").change(function (event) {
                var input = event.target;
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        loadCanvas(e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
                $("input#imageName").val(input.files[0].name.replace(/\..*$/, ""));
            });
            $("input#width").change(function () {
                console.log(ar);
                console.log($(this).val());
                console.log(canvas.height);
                console.log(canvas.width);
                canvas.width = $(this).val();
                canvas.height = canvas.width / ar;
                $("input#height").val(canvas.height);
            });
            $("input#height").change(function () {
                console.log($(this).val());
            });
        }
    }
}
//VARIABLE PANEL SETTINGS
function createDialogVariablePanelSettings(id, mode) {
    return $("div.dialog-variable-panel-settings").dialog({
        autoOpen: false,
        width: 900,
        height: 700,
        modal: true,
        buttons: [
            {
                text: "Zapisz",
                click: function () {
                    var dialog_panel = $(".dialog-variable-panel-settings div.dialog-panel");
                    var data = {
                        panel_id: id,
                        content: $("select.registers").val(),
                        topPosition: $("div.dialog-variable-panel-settings input#topPosition").val(),
                        leftPosition: $("div.dialog-variable-panel-settings input#leftPosition").val(),
                        width: dialog_panel.css("width"),
                        height: dialog_panel.css("height"),
                        textAlign: dialog_panel.css("textAlign"),
                        fontWeight: dialog_panel.css("fontWeight"),
                        textDecoration: dialog_panel.css("textDecoration"),
                        fontStyle: dialog_panel.css("fontStyle"),
                        fontFamily: dialog_panel.css("fontFamily"),
                        fontSize: dialog_panel.css("fontSize"),
                        fontColor: dialog_panel.css("color")
                    };
                    ajaxEditVariablePanel(data);
                    $(this).dialog('destroy').remove();
                }
            },
            {
                text: "Anuluj",
                click: function () {
                    $(this).dialog('destroy').remove();
                    if (mode === "add") {
                        $("div#" + id + ".bms-panel").remove();
                        var data = {
                            panel_id: id
                        };
                        ajaxDeletePanel(data);
                    }
                }
            }],
        open: function () {
            setDialog(id);
        },
        close: function () {
            $(this).dialog('destroy').remove();
        }
    });

    function setDialog(id) {
        var panel = $("div#" + id + ".variable-panel");
        var text = panel.children("span").clone().children().remove().end().text();
        var dialog_panel = $(".dialog-variable-panel-settings div.dialog-panel");

        dialog_panel.text(text).css({
            width: panel.css("width"),
            height: panel.css("height"),
            textAlign: panel.css("textAlign"),
            fontWeight: panel.css("fontWeight"),
            textDecoration: panel.css("textDecoration"),
            fontStyle: panel.css("fontStyle"),
            fontFamily: panel.css("fontFamily"),
            fontSize: panel.css("fontSize"),
            color: panel.css("color"),
            lineHeight: panel.css("lineHeight"),
            boxShadow: "0px 0px 10px #000",
            backgroundColor: "#FFF",
            display: "inline-block"
        });
        //Rejestr
        $("select.registers").val(parseInt(panel.children("span").attr("id")));
        //Pozycja
        $("input#topPosition").val(parseInt(panel.css("top")));
        $("input#leftPosition").val(parseInt(panel.css("left")));
        //Rozmiar
        $("input#width").val(parseInt(panel.css("width")));
        $("input#height").val(parseInt(panel.css("height")));
        //Kolor
        $("input#fontColor").val(rgb2hex(panel.css("color")));
        //Czcionka styl
        $("select.font-family").val(dialog_panel.css("fontFamily"));
        //Czcionka rozmiar
        $("select.font-size").val(parseInt(dialog_panel.css("fontSize")));
        //Czcionka pogrubienie 
        dialog_panel.css("fontWeight") === "700" ? $(".btn-bold").addClass("active") : $(".btn-bold").removeClass("active");
        //Czcionka podkreślenie
        dialog_panel.css("textDecoration") === "underline" ? $(".btn-underline").addClass("active") : $(".btn-underline").removeClass("active");
        //Czcionka pochylenie
        dialog_panel.css("fontStyle") === "italic" ? $(".btn-italic").addClass("active") : $(".btn-italic").removeClass("active");
        //Wyrównanie
        setAlign(dialog_panel.css("text-align"));

        setDialogButtons();
    }

    function setDialogButtons() {
        $("div.dialog-variable-panel-settings div.edit-controls div").children().unbind("click");
        var dialog_panel = $(".dialog-variable-panel-settings div.dialog-panel");

        //pogrubienie
        $(".btn-bold").click(function () {
            $(this).hasClass("active") ? dialog_panel.css({fontWeight: "initial"}) : dialog_panel.css({fontWeight: "bold"});
            $(this).toggleClass("active");
        });
        //podkreślenie
        $(".btn-underline").click(function () {
            $(this).hasClass("active") ? dialog_panel.css({textDecoration: "initial"}) : dialog_panel.css({textDecoration: "underline"});
            $(this).toggleClass("active");
        });
        //pochylenie
        $(".btn-italic").click(function () {
            $(this).hasClass("active") ? dialog_panel.css({fontStyle: "initial"}) : dialog_panel.css({fontStyle: "italic"});
            $(this).toggleClass("active");
        });
        //wyrównanie do lewej
        $(".btn-align-left").click(function () {
            $(this).hasClass("active") ? dialog_panel.css({textAlign: "auto"}) : dialog_panel.css({textAlign: "left"});
            setAlign("left");
        });
        //wyrównanie do środka
        $(".btn-align-center").click(function () {
            $(this).hasClass("active") ? dialog_panel.css({textAlign: "auto"}) : dialog_panel.css({textAlign: "center"});
            setAlign("center");
        });
        //wyrównanie do prawej
        $(".btn-align-right").click(function () {
            $(this).hasClass("active") ? dialog_panel.css({textAlign: "auto"}) : dialog_panel.css({textAlign: "right"});
            setAlign("right");
        });
        //styl czcionki
        $("select.font-family").change(function () {

            dialog_panel.css({fontFamily: $(this).val()});
        });
        //rozmiar czcionki
        $("select.font-size").change(function () {
            dialog_panel.css({fontSize: $(this).val() + "px"});
        });
        //size
        $("input#width").change(function () {
            dialog_panel.css({width: $(this).val() + "px"});
        });
        $("input#height").change(function () {
            dialog_panel.css({height: $(this).val() + "px"});
            dialog_panel.css({lineHeight: $(this).val() + "px"});
        });
        //Kolor
        $("input#fontColor").on('input', function () {
            dialog_panel.css({color: hex2rgba($(this).val(), 1)});
        });

    }

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
}
//NAVIGATION PANEL SETTINGS
function createDialogNavigationPanelSettings(id, mode) {
    return $("div.dialog-navigation-panel-settings").dialog({
        autoOpen: false,
        width: 900,
        height: 700,
        modal: true,
        buttons: [
            {
                text: "Zapisz",
                click: function () {
                    var dialog_panel = $(".dialog-navigation-panel-settings div.dialog-panel");
                    var br = dialog_panel.css("border-top-left-radius") + " " + dialog_panel.css("border-top-right-radius") + " " + dialog_panel.css("border-bottom-right-radius") + " " + dialog_panel.css("border-bottom-left-radius");
                    var data = {
                        panel_id: id,
                        content: $("select.pages").val(),
                        topPosition: $("div.dialog-navigation-panel-settings input#topPosition").val(),
                        leftPosition: $("div.dialog-navigation-panel-settings input#leftPosition").val(),
                        width: dialog_panel.css("width"),
                        height: dialog_panel.css("height"),
                        borderWidth: $("div.dialog-navigation-panel-settings input#borderWidth").val(),
                        borderStyle: $("div.dialog-navigation-panel-settings select#borderStyle option:selected").val(),
                        borderColor: hex2rgba($("div.dialog-navigation-panel-settings input#borderColor").val(), 1),
                        borderRadius: br,
                        backgroundColor: hex2rgba($("div.dialog-navigation-panel-settings input#backgroundColor").val(), parseFloat($("div.dialog-navigation-panel-settings input#opacity").val()))
                    };
                    ajaxEditNavigationPanel(data);
                    $(this).dialog('destroy').remove();
                }
            },
            {
                text: "Anuluj",
                click: function () {
                    $(this).dialog('destroy').remove();
                    if (mode === "add") {
                        $("div#" + id + ".bms-panel").remove();
                        var data = {
                            panel_id: id
                        };
                        ajaxDeletePanel(data);
                    }

                }
            }],
        open: function () {
            setDialog(id);
        },
        close: function () {
            $(this).dialog('destroy').remove();
        }
    });

    function setDialog(id) {
        var panel = $("div#" + id + ".navigation-panel");
        var dialog_panel = $(".dialog-navigation-panel-settings div.dialog-panel");
        //Strona
        $("select.pages").val(parseInt(panel.children("div").attr("id")));
        dialog_panel.text($("select.pages option:selected").text()).css({
            width: panel.css("width"),
            height: panel.css("height"),
            textAlign: panel.css("textAlign"),
            fontWeight: panel.css("fontWeight"),
            borderWidth: panel.css("border-top-width"),
            borderColor: panel.css("border-top-color"),
            borderStyle: panel.css("border-top-style"),
            borderTopLeftRadius: panel.css("border-top-left-radius"),
            borderTopRightRadius: panel.css("border-top-right-radius"),
            borderBottomRightRadius: panel.css("border-bottom-right-radius"),
            borderBottomLeftRadius: panel.css("border-bottom-left-radius"),
            backgroundColor: panel.css("background-color"),
            display: "inline-block"
        });

        //var text = panel.children("span").clone().children().remove().end().text();
        //Pozycja
        $("input#topPosition").val(parseInt(panel.css("top")));
        $("input#leftPosition").val(parseInt(panel.css("left")));
        //Rozmiar
        $("input#width").val(parseInt(panel.css("width")));
        $("input#height").val(parseInt(panel.css("height")));
        //Border
        $("input#borderWidth").val(parseInt(panel.css("border-top-width")));
        $("input#borderColor").val(rgb2hex(panel.css("border-top-color")));
        $("select#borderStyle").val(panel.css("border-top-style"));
        //Border Radius
        //TL
        $("input#borderRadiusTL").val(parseInt(panel.css("border-top-left-radius")));
        $("label#borderRadiusTL").empty().append($("input#borderRadiusTL").val());
        //TR
        $("input#borderRadiusTR").val(parseInt(panel.css("border-top-right-radius")));
        $("label#borderRadiusTR").empty().append($("input#borderRadiusTR").val());
        //BR
        $("input#borderRadiusBR").val(parseInt(panel.css("border-bottom-right-radius")));
        $("label#borderRadiusBR").empty().append($("input#borderRadiusBR").val());
        //BL
        $("input#borderRadiusBL").val(parseInt(panel.css("border-bottom-left-radius")));
        $("label#borderRadiusBL").empty().append($("input#borderRadiusBL").val());
        //Size
        $("input#width").val(parseInt(panel.css("width")));
        $("input#height").val(parseInt(panel.css("height")));
        //Background
        var bC = panel.css("background-color");
        if (bC === 'transparent') {
            bC = 'rgba(0,0,0,0)';
        }
        $("input#backgroundColor").val(rgb2hex(bC));
        $("input#opacity").val(parseFloat(getColorValues(bC)["alpha"]));
        setDialogButtons();
    }

    function setDialogButtons() {
        var dialog_panel = $(".dialog-navigation-panel-settings div.dialog-panel");

        //size
        $(".dialog-navigation-panel-settings input#width").change(function () {
            dialog_panel.css({width: $(this).val() + "px"});
        });
        $(".dialog-navigation-panel-settings input#height").change(function () {
            dialog_panel.css({height: $(this).val() + "px"});
            dialog_panel.css({lineHeight: $(this).val() + "px"});
        });
        //kolor
        var backgroundColor;
        $(".dialog-navigation-panel-settings input#backgroundColor").on('input', function () {
            backgroundColor = hex2rgba($(this).val(), parseFloat($(".dialog-navigation-panel-settings input#opacity").val()));
            dialog_panel.css({backgroundColor: backgroundColor});
        });

        $(".dialog-navigation-panel-settings input#opacity").change(function () {
            backgroundColor = hex2rgba($(".dialog-navigation-panel-settings input#backgroundColor").val(), parseFloat($(this).val()));
            dialog_panel.css({backgroundColor: backgroundColor});
        });

        $(".dialog-navigation-panel-settings input#opacity").mousemove(function () {
            backgroundColor = hex2rgba($(".dialog-navigation-panel-settings input#backgroundColor").val(), parseFloat($(this).val()));
            dialog_panel.css({backgroundColor: backgroundColor});
        });

        $(".dialog-navigation-panel-settings input#borderWidth").change(function () {
            dialog_panel.css({borderWidth: $(this).val()});
        });
        $(".dialog-navigation-panel-settings select#borderStyle").change(function () {
            console.log($(this).val());
            dialog_panel.css({borderStyle: $(this).val()});
        });
        $(".dialog-navigation-panel-settings input#borderColor").on('input', function () {
            dialog_panel.css({borderColor: hex2rgba($(this).val(), 1)});
        });
        //narożniki
        $(".dialog-navigation-panel-settings input#borderRadiusTL").change(changeTL).mousemove(changeTL);
        $(".dialog-navigation-panel-settings input#borderRadiusTR").change(changeTR).mousemove(changeTR);
        $(".dialog-navigation-panel-settings input#borderRadiusBL").change(changeBL).mousemove(changeBL);
        $(".dialog-navigation-panel-settings input#borderRadiusBR").change(changeBR).mousemove(changeBR);
        function changeTL() {
            dialog_panel.css({borderTopLeftRadius: $(this).val() + "px"});
        }
        function changeTR() {
            dialog_panel.css({borderTopRightRadius: $(this).val() + "px"});
        }
        function changeBL() {
            dialog_panel.css({borderBottomLeftRadius: $(this).val() + "px"});
        }
        function changeBR() {
            dialog_panel.css({borderBottomRightRadius: $(this).val() + "px"});
        }
    }
}
//****SETTING DIALOGS END


//****AJAX START
//PAGE ADD
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
//PAGE DELETE
function ajaxDeletePage(data) {
    $.ajax({
        type: "POST",
        url: Routing.generate('bms_visualization_delete_page'),
        data: data,
        success: function () {
            $(".main-row").children(".fa-spinner").remove();
//            var data = {
//                page_id: 1
//            };
//            ajaxChangePage(data);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
//PAGE EDIT
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

//PAGE CHANGE
function ajaxChangePage(data) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_change_page'),
        data: data,
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            createPage(ret["template"]);

            setVariables(ret["registers"]);
            ajaxLoadPanelList(data);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    function setVariables(registers) {
        $.each(registers, function (key, value) {
            $("div.variable-panel").children("span#" + key).append(value);
        });
    }
}
//PANEL ADD
function ajaxAddPanel(data) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_add_panel'),
        data: data,
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            $("div.main-row div.well").append(ret["template"]);
            $("div.panel-list-container").hide().empty();
            var d = {
                page_id: $("div.label-page.active").attr("id")
            };
            ajaxLoadPanelList(d);
            setPanelEvents();
            if (ret["type"] === "image") {
                data.panel_id = ret["panel_id"];
//                data.topPosition =
//                        data.leftPosition =
                ajaxEditImagePanel(data);
            } else if (ret["type"] === "variable") {
                ajaxLoadVariableSettingsPanel(ret["panel_id"], "add");
            } else if (ret["type"] === "navigation") {
                ajaxLoadNavigationSettingsPanel(ret["panel_id"], "add");
            }
//            return ret["panel_id"];
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
//PANEL EDIT
function ajaxEditPanel(data) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_edit_panel'),
        data: data,
        success: function () {
            $(".main-row").children(".fa-spinner").remove();
            $("div.panel-list-container").hide().empty();
            var d = {
                page_id: $("div.label-page.active").attr("id")
            };
            ajaxLoadPanelList(d);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
function ajaxEditAreaPanel(data) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_edit_area_panel'),
        data: data,
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            $("div#" + data["panel_id"] + ".area-panel").css(ret["css"]);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
function ajaxEditTextPanel(data) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_edit_text_panel'),
        data: data,
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            var panel = $("div#" + data["panel_id"] + ".text-panel");
            panel.removeClass("text-left text-center text-right").addClass(ret["textAlign"]).css(ret["css"]).children("span.content").text(ret["content"]);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
function ajaxEditImagePanel(data) {
    $.ajax({
        type: "POST",
        url: Routing.generate('bms_visualization_edit_image_panel'),
        data: data,
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            $("div#" + ret['panel_id'] + ".image-panel").css(ret['css']).children("img").attr("src", ret["content"]);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
function ajaxEditVariablePanel(data) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_edit_variable_panel'),
        data: data,
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            var panel = $("div#" + data["panel_id"] + ".variable-panel");
            panel.removeClass("text-left text-center text-right").addClass(ret["textAlign"]).css(ret["css"]).children("span").attr("id", ret['content']).empty().append(ret['fixedValue']);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
function ajaxEditNavigationPanel(data) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_edit_navigation_panel'),
        data: data,
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            var panel = $("div#" + data["panel_id"] + ".navigation-panel");
            panel.css(ret["css"]).children("div").attr("id", ret['content']);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
//PANEL COPY
function ajaxCopyPanel(data) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_copy_panel'),
        data: data,
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            $("div.main-row div.well").append(ret["template"]);
            $("div.panel-list-container").hide().empty();
            var d = {
                page_id: $("div.label-page.active").attr("id")
            };
            ajaxLoadPanelList(d);
            setPanelEvents();
            var type = ret["type"];
            var id = ret["panel_id"];
            switch (type) {
                case "area" :
                    createDialogAreaPanelSettings(id).dialog("open");
                    break;
                case "text" :
                    createDialogTextPanelSettings(id).dialog("open");
                    break;
                case "image" :
                    createDialogImagePanelSettings(id).dialog("open");
                    break;
                case "variable" :
                    ajaxLoadVariableSettingsPanel(id, "add");
                    break;
                case "navigation" :
                    ajaxLoadNavigationSettingsPanel(id, "add");
                    break;
            }

        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
//PANEL DELETE
function ajaxDeletePanel(data) {
    $.ajax({
        type: "POST",
        url: Routing.generate('bms_visualization_delete_panel'),
        data: data,
        success: function () {
            $(".main-row").children(".fa-spinner").remove();
            $("div#" + data["panel_id"] + ".panel-list").remove();
            $("div.panel-list-container").hide().empty();
            var d = {
                page_id: $("div.label-page.active").attr("id")
            };
            ajaxLoadPanelList(d);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
//OTHER
function ajaxLoadVariableSettingsPanel(panel_id, mode) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_load_variable_settings_panel'),
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            $("nav div.container-fluid").append(ret['template']);
            createDialogVariablePanelSettings(panel_id, mode).dialog("open");
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
function ajaxLoadNavigationSettingsPanel(panel_id, mode) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_load_navigation_settings_panel'),
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            $("nav div.container-fluid").append(ret['template']);
            createDialogNavigationPanelSettings(panel_id, mode).dialog("open");
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
function ajaxLoadImageSettingsPanel() {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate('bms_visualization_load_image_settings_panel'),
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            $(".container-fluid").append(ret['template']);
            createDialogImagePanelSettings().dialog("open");
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}

function ajaxLoadPanelList(data) {
    $("input#panel-list-area, input#panel-list-text, input#panel-list-image, input#panel-list-variable, input#panel-list-navigation").prop("checked",true).unbind("click, change");
    if ($("button.btn-panel-list span.toggler").hasClass("off") === true) {

    } else {
        $.ajax({
            type: "POST",
            datatype: "application/json",
            url: Routing.generate('bms_visualization_load_panel_list'),
            data: data,
            success: function (ret) {
                $(".main-row").children(".fa-spinner").remove();
                $("div.panel-list-container").empty().append(ret['template']).show();
                setSidebarPanelListEvents();
            }
        });
        $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
        function setSidebarPanelListEvents() {
            
            $('input#panel-list-area').change(function () {
                $(this).is(':checked') ? $("span.panel-list-area").parent("div.panel-list").show() : $("span.panel-list-area").parent("div.panel-list").hide();
            });
            $('input#panel-list-text').change(function () {
                $(this).is(':checked') ? $("span.panel-list-text").parent("div.panel-list").show() : $("span.panel-list-text").parent("div.panel-list").hide();
            });
            $('input#panel-list-image').change(function () {
                $(this).is(':checked') ? $("span.panel-list-image").parent("div.panel-list").show() : $("span.panel-list-image").parent("div.panel-list").hide();
            });
            $('input#panel-list-variable').change(function () {
                $(this).is(':checked') ? $("span.panel-list-variable").parent("div.panel-list").show() : $("span.panel-list-variable").parent("div.panel-list").hide();
            });
            $('input#panel-list-navigation').change(function () {
                $(this).is(':checked') ? $("span.panel-list-navigation").parent("div.panel-list").show() : $("span.panel-list-navigation").parent("div.panel-list").hide();
            });
            $('div.panel-list').hover(function () {
                $(this).children("i.fa").addClass("active");
                var id = $(this).attr("id");
                $("div#" + id + ".bms-panel").css({zIndex: "100", boxShadow: "0px 0px 15px #000"});
            }, function () {
                $(this).children("span").hasClass("active") ? $(this).children("i.fa").addClass("active") : $(this).children("i.fa").removeClass("active");
                var id = $(this).attr("id");
                var zi = $(this).children("span").attr("value");
                $("div#" + id + ".bms-panel").css({zIndex: zi, boxShadow: ""});
            });
            $("div.panel-list span").click(function () {
                $(this).toggleClass("active");
                var id = $(this).parent().attr('id');
                $("div#" + id + ".bms-panel").toggleClass("active");
            });
            //edycja kolejności w górę
            $("div.panel-list i.fa-arrow-up").click(function () {
                var id = $(this).parent().attr("id");
                var panel = $("div#" + id + ".bms-panel");
                var zIndex = $(this).parent().children("span").attr("value");
                zIndex++;
                $("div#" + id + ".bms-panel").css({zIndex: zIndex});
                var data = {
                    panel_id: id,
                    topPosition: panel.css("top"),
                    leftPosition: panel.css("left"),
                    width: panel.css("width"),
                    height: panel.css("height"),
                    zIndex: panel.css("zIndex")
                };
                ajaxEditPanel(data);
            });
            //edycja kolejności w dół
            $("div.panel-list i.fa-arrow-down").click(function () {
                var id = $(this).parent().attr("id");
                var panel = $("div#" + id + ".bms-panel");
                var zIndex = $(this).parent().children("span").attr("value");
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
                ajaxEditPanel(data);
            });
            //kopiowanie
            $("div.panel-list i.fa-clone").click(function () {
                var id = $(this).parent().attr("id");
                var data = {
                    panel_id: id
                };
                ajaxCopyPanel(data);
            });
            //ustawienia
            $("div.panel-list i.fa-cogs").click(function () {
                var id = $(this).parent().attr("id");
                if ($("div#" + id + ".bms-panel").hasClass("text-panel")) {
                    createDialogTextPanelSettings(id).dialog("open");
                } else if ($("div#" + id + ".bms-panel").hasClass("image-panel")) {
                    ajaxLoadImageSettingsPanel(id);
                } else if ($("div#" + id + ".bms-panel").hasClass("variable-panel")) {
                    ajaxLoadVariableSettingsPanel(id, "edit");
                } else if ($("div#" + id + ".bms-panel").hasClass("navigation-panel")) {
                    ajaxLoadNavigationSettingsPanel(id, "edit");
                } else {
                    createDialogAreaPanelSettings(id).dialog("open");
                }
            });
            //usuwanie
            $("div.panel-list i.fa-remove").click(function () {
                var id = $(this).parent().attr("id");
                if (confirm("Na pewno chcesz to usunąć?")) {
                    $("div#" + id + ".bms-panel").remove();
                    var data = {
                        panel_id: id
                    };
                    ajaxDeletePanel(data);
                }
            });
        }
    }

}
//****AJAX END

//utworzenie strony
function createPage(content) {
    $(".main-row div.col-md-12").children().remove();
    $(".main-row div.col-md-12").append(content).fadeIn("slow");
    setPatternNet($("input#pattern-net-size").val());
    setPageLabelsEvent();

    setPanelEvents();
}

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
        }).hover(function () {
            $(this).not(".active").children().toggleClass("hidden");
        }, function () {
            $(this).not(".active").children().toggleClass("hidden");
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
//dodanie obsługi zdarzeń na każdym panelu na stronie
function setPanelEvents() {
    var panels = $(".bms-panel");

    panels.each(function () {
        //pobranie id panelu 
        var id = $(this).attr("id");
        var aR;
        if ($(this).hasClass("image-panel")) {
            aR = true;
        } else {
            aR = false;
        }

        //usunięcie starych eventów
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
                ajaxEditPanel(data);
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
                var data = {
                    panel_id: id,
                    topPosition: ui.helper.css("top"),
                    leftPosition: ui.helper.css("left"),
                    width: ui.element.css("width"),
                    height: ui.element.css("height"),
                    zIndex: ui.element.css("zIndex")
                };
                ajaxEditPanel(data);
                ui.element.removeClass("hover");
            }
        });
        $(this).hover(function(){
            $(".panel-list-container div#" + id + " span.label").addClass("active");
        },function(){
            $(".panel-list-container div#" + id + " span.label").removeClass("active");
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
        //obsługa edycji kolejności
        $(label + " i.fa-arrow-up").click(function () {
            var panel = $("div#" + id + ".bms-panel");
            var zIndex = $("div#" + id + ".bms-panel").css("zIndex");
            zIndex++;
            $("div#" + id + ".bms-panel").css({zIndex: zIndex});
            var data = {
                panel_id: id,
                topPosition: panel.css("top"),
                leftPosition: panel.css("left"),
                width: panel.css("width"),
                height: panel.css("height"),
                zIndex: panel.css("zIndex")
            };
            ajaxEditPanel(data);
        });
        $(label + " i.fa-arrow-down").click(function () {
            var panel = $("div#" + id + ".bms-panel");
            var zIndex = $("div#" + id + ".bms-panel").css("zIndex");
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
            ajaxEditPanel(data);
        });
        //kopiowania
        $(label + " i.fa-clone").click(function () {
            var panel = $("div#" + id + ".bms-panel");
            var data = {
                panel_id: id
            };
            ajaxCopyPanel(data);
        });
        //ustawienia
        $(label + " i.fa-cogs").click(function () {
            if ($("div#" + id + ".bms-panel").hasClass("text-panel")) {
                createDialogTextPanelSettings(id).dialog("open");
            } else if ($("div#" + id + ".bms-panel").hasClass("image-panel")) {
                //ajaxLoadImageSettingsPanel(id);
            } else if ($("div#" + id + ".bms-panel").hasClass("variable-panel")) {
                ajaxLoadVariableSettingsPanel(id, "edit");
            } else if ($("div#" + id + ".bms-panel").hasClass("navigation-panel")) {
                ajaxLoadNavigationSettingsPanel(id, "edit");
            } else {
                createDialogAreaPanelSettings(id).dialog("open");
            }
        });
        //usuwanie
        $(label + " i.fa-remove").click(function () {
            if (confirm("Na pewno chcesz to usunąć?")) {
                $("div#" + id + ".bms-panel").remove();
                var data = {
                    panel_id: id
                };
                ajaxDeletePanel(data);
            }
        });
    }

}


//****TOOLS START
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