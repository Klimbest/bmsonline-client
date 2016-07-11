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
                $(".main-row").append(ret["template"]);
                createPanelDialog().dialog("open");
                $(".main-row").children(".fa-spinner").remove();
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
function createPanelDialog() {
    return $("div.dialog-panel-settings").dialog({
        autoOpen: false,
        width: $(window).width(),
        height: $(window).height(),
        modal: true,
        buttons: [{
                text: "Zapisz",
                click: function () {
                    saveData();
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
            setDialogButtonsData();
        },
        close: function () {
            $(this).dialog('destroy').remove();
        }
    });

    function saveData() {
        var form = $("form[name='panel']");
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            success: function (ret) {
                $(".main-row").children(".fa-spinner").remove();
                console.log("Success!!");
                //$("div.main-row div.well").append(ret['template']);
                //loadPanelList(ret["panelList"]);
                //setPanelEvents();
            }
        });
        $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    }

    function setDialogButtonsData() {
        //zmiana zakładek
        $("div.dialog-panel-settings form li a").click(function () {
            $("div.dialog-panel-settings form li").removeClass("active");
            $("div.row.dialog-panel-data, div.row.dialog-panel-format, div.row.dialog-panel-navigation, div.row.dialog-panel-event, div.row.dialog-panel-progress-bar").hide();
            var id = $(this).parent().attr("id");
            $(this).parent().addClass("active");
            $("div.row." + id).show();
        });
        //panel type
        $("select#panel_type").change(function () {
            var value = $(this).val();
            var buttonManager = $("button#manager");
            switch (value) {
                case "variable":
                    $("input#panel-source-content").val("").prop("disabled", true).prop("required", true).show();
                    buttonManager.removeClass("disabled").show().unbind("click");
                    $("li#dialog-panel-progress-bar").show();
                    $(".precision-group, .font-group, li#dialog-panel-format, li#dialog-panel-navigation, li#dialog-panel-event").hide();
                    setOpenVariableManager();
                    break;
                case "image":
                    $("input#panel-source-content").val("").prop("disabled", true).prop("required", false).show();
                    buttonManager.removeClass("disabled").show().unbind("click");
                    $(".precision-group, .font-group, li#dialog-panel-progress-bar").hide();
                    $("li#dialog-panel-format, li#dialog-panel-navigation, li#dialog-panel-event").show();
                    setOpenImageManager();
                    break;
                case "text":
                    $("input#panel-source-content").val("").removeAttr("disabled required").show();
                    buttonManager.addClass("disabled").show().unbind("click");
                    $(".precision-group, li#dialog-panel-progress-bar").hide();
                    $(".font-group, li#dialog-panel-format, li#dialog-panel-navigation, li#dialog-panel-event").show();
                    break;
                case "widget":
                    $("input#panel-source-content").val("").prop("required", false).hide();
                    buttonManager.addClass("disabled").hide().unbind("click");
                    $("li#dialog-panel-progress-bar").show();
                    $("li#dialog-panel-format, li#dialog-panel-navigation, li#dialog-panel-event").hide();
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
    /*
    function setDialogButtonEvent(panel_id) {
        //add term
        $("button.add-term").click(function () {
            $.ajax({
                type: "POST",
                datatype: "application/json",
                url: Routing.generate('bms_visualization_load_event_manager'),
                success: function (ret) {
                    $(".main-row").children(".fa-spinner").remove();
                    $(".main-row").append(ret['template']);
                    createCondition(panel_id).dialog("open");
                }
            });
            $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
        });
        //delete term
        setDeleteTerm();
    }
    function setDialogButtonProgressBar() {
        //zmiana kolorów
        $("input#color1").on('input', function () {
            $("div#pb1").css({backgroundColor: $(this).val()});
        });
        $("input#color2").on('input', function () {
            $("div#pb2").css({backgroundColor: $(this).val()});
        });
        $("input#color3").on('input', function () {
            $("div#pb3").css({backgroundColor: $(this).val()});
        });
        //dodanie zmiennych
        $("button#progress-bar-manager-value").click(function () {
            $.ajax({
                type: "POST",
                datatype: "application/json",
                url: Routing.generate('bms_visualization_load_variable_manager'),
                success: function (ret) {
                    $(".main-row").children(".fa-spinner").remove();
                    $(".main-row").append(ret["template"]);
                    createVariableManager("progress-bar-value").dialog("open");
                }
            });
            $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
        });
        $("button#progress-bar-manager-set").click(function () {
            $.ajax({
                type: "POST",
                datatype: "application/json",
                url: Routing.generate('bms_visualization_load_variable_manager'),
                success: function (ret) {
                    $(".main-row").children(".fa-spinner").remove();
                    $(".main-row").append(ret["template"]);
                    createVariableManager("progress-bar-set").dialog("open");
                }
            });
            $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
        });
    }*/
}
function setOpenVariableManager() {
    $("button#manager").click(function () {
        $.ajax({
            type: "POST",
            datatype: "application/json",
            url: Routing.generate('bms_visualization_load_variable_manager'),
            success: function (ret) {
                $(".main-row").children(".fa-spinner").remove();
                $(".main-row").append(ret["template"]);
                createVariableManager("data-source").dialog("open");
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
                createImageManager("data-source").dialog("open");
            }
        });
        $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    });
}

function createVariableManager(fw) {
    return $("div.variable-manager").dialog({
        autoOpen: false,
        width: $(window).width(),
        height: $(window).height(),
        modal: true,
        buttons: [
            {
                text: "Zapisz",
                click: function () {
                    if (fw === "data-source") {
                        var value = $("div.variable-manager input#register").val();
                        var res = value.split("&");
                        $("div.dialog-panel-settings form[name=panel] input#panel-source-content").val(res[0]);
                        $("div.dialog-panel-settings div.panel-preview span").empty().append(res[1]);
                        $("div.dialog-panel-settings input#panel-term-register-value").val(res[1]);
                        $(this).dialog('destroy').remove();
                    } else if (fw === "term-register") {
                        var value = $("div.variable-manager input#register").val();
                        var res = value.split("&");
                        $("div.dialog-condition input#panel-term-register").val(res[0]);
                        $("div.dialog-condition input#panel-term-register-value").val(res[1]);
                        $(this).dialog('destroy').remove();
                    } else if (fw === "progress-bar-value") {
                        var value = $("div.variable-manager input#register").val();
                        var res = value.split("&");
                        $("input#progress-bar-value").val(res[0]);
                        $(this).dialog('destroy').remove();
                    } else if (fw === "progress-bar-set") {
                        var value = $("div.variable-manager input#register").val();
                        var res = value.split("&");
                        $("input#progress-bar-set").val(res[0]);
                        $(this).dialog('destroy').remove();
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

            setDialogButtons();
        },
        close: function () {
            $(this).dialog('destroy').remove();
        }
    });

    function setDialogButtons() {
        $("input#deviceSearch").keyup(function () {
            $("div.variable-manager input:not(#deviceSearch)").val("");
            var data = this.value.toUpperCase().split("&");
            var rows = $("div.register-choice").find("div#deviceName");
            if (this.value == "") {
                rows.parent().show();
                return;
            }
            rows.parent().hide();
            rows.filter(function (i, v) {
                var $t = $(this);
                for (var d = 0; d < data.length; ++d) {
                    if ($t.text().toUpperCase().indexOf(data[d]) > -1) {
                        return true;
                    }
                }
                return false;
            }).parent().show();
        });
        $("input#functionSearch").keyup(function () {
            $("div.variable-manager input:not(#functionSearch)").val("");
            var data = this.value.toUpperCase().split("&");
            var rows = $("div.register-choice").find("div#function");
            if (this.value == "") {
                rows.parent().show();
                return;
            }
            rows.parent().hide();
            rows.filter(function (i, v) {
                var $t = $(this);
                for (var d = 0; d < data.length; ++d) {
                    if ($t.text().toUpperCase().indexOf(data[d]) > -1) {
                        return true;
                    }
                }
                return false;
            }).parent().show();
        });
        $("input#addressSearch").keyup(function () {
            $("div.variable-manager input:not(#addressSearch)").val("");
            var data = this.value.toUpperCase().split("&");
            var rows = $("div.register-choice").find("div#address");
            if (this.value == "") {
                rows.parent().show();
                return;
            }
            rows.parent().hide();
            rows.filter(function (i, v) {
                var $t = $(this);
                for (var d = 0; d < data.length; ++d) {
                    if ($t.text().toUpperCase().indexOf(data[d]) > -1) {
                        return true;
                    }
                }
                return false;
            }).parent().show();
        });
        $("input#registerSearch").keyup(function () {
            $("div.variable-manager input:not(#registerSearch)").val("");
            var data = this.value.toUpperCase().split("&");
            var rows = $("div.register-choice").find("div#registerName");
            if (this.value == "") {
                rows.parent().show();
                return;
            }
            rows.parent().hide();
            rows.filter(function (i, v) {
                var $t = $(this);
                for (var d = 0; d < data.length; ++d) {
                    if ($t.text().toUpperCase().indexOf(data[d]) > -1) {
                        return true;
                    }
                }
                return false;
            }).parent().show();
        });
        $("input#descriptionSearch").keyup(function () {
            $("div.variable-manager input:not(#descriptionSearch)").val("");
            var data = this.value.toUpperCase().split("&");
            var rows = $("div.register-choice").find("div#description");
            if (this.value == "") {
                rows.parent().show();
                return;
            }
            rows.parent().hide();
            rows.filter(function (i, v) {
                var $t = $(this);
                for (var d = 0; d < data.length; ++d) {
                    if ($t.text().toUpperCase().indexOf(data[d]) > -1) {
                        return true;
                    }
                }
                return false;
            }).parent().show();
        });

        $("div.register-choice").click(function () {
            var registerName = $(this).children("div#registerName").text();
            var registerValue = $(this).children("div#value").text();
            var registerId = $(this).attr('id');
            $("input#register").val(registerName + "&" + registerValue + "&" + registerId);
            $("div.register-choice").removeClass("selected");
            $(this).addClass("selected");
        });

    }
}
function createImageManager(fw) {
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
                    if (fw === "data-source") {
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
                        var imgSource = $("div.image-manager div.thumbnail-list div.selected img").attr("src");
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
                    } else if (fw === "effect") {
                        var imgSource = $("div.image-manager div.thumbnail-list div.selected img").attr("src");
                        $("form#condition input#effect-value").val(imgSource);
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
        var dp = $("div.image-manager div#new-image");
        var img = new Image();
        if ($("input#panel-source-content").val()) {
            var src = $("input#panel-source-content").val();
            $("div.image-manager input#imageName").val(src);

            dp.children("img").attr("src", src);
        }
        //loading image from disk
        $("div.image-manager input#image").change(function (event) {
            dp.empty().append("<img class='img-responsive' src='' />");
            input = event.target;
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    img.onload = function () {
                        dp.children("img").attr("src", e.target.result);
                        $("div.image-manager input#resolutionX").val(this.width);
                        $("div.image-manager input#resolutionY").val(this.height);
                    };
                    img.src = e.target.result;

                };
                reader.readAsDataURL(input.files[0]);
            }
            var imgName = input.files[0].name;
            dp.attr("id", imgName);
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
        $("div.image-manager div.image-list i.fa-trash-o").click(function () {
            var name = $(this).parent().children("span.label").text();
            var data = {
                image_name: name.replace(" ", "")
            };
            ajaxDeleteImage(data);
            $(this).parent().remove();
        });
        //choose image
        $("div.thumbnail-list div").click(function () {
            $("div.thumbnail-list div").removeClass("selected");
            $(this).addClass("selected");
            var url = $(this).children("img").attr("src");
            var img = new Image();
            img.onload = function () {
                $("div.image-manager input#resolutionX").val(this.width);
                $("div.image-manager input#resolutionY").val(this.height);
            };
            img.src = url;
            var name = $(this).attr("id");
            $("div.image-manager input#imageName").val(name);
        });
        //change size of image
        $("div.image-manager input#resolutionX").change(function () {
            var ar = img.width / img.height;
            var h = $(this).val() / ar;
            $("div.image-manager input#resolutionY").val(Math.round(h));

        });
        $("div.image-manager input#resolutionY").change(function () {
            var ar = img.width / img.height;
            var w = $(this).val() * ar;
            $("div.image-manager input#resolutionX").val(Math.round(w));
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
function createEffectCssManager() {
    return $("div.effect-css-manager").dialog({
        autoOpen: false,
        width: $(window).width(),
        height: $(window).height(),
        modal: true,
        buttons: [
            {
                text: "Zapisz",
                click: function () {

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
            setDialogButtons();
        },
        close: function () {
            $(this).dialog('destroy').remove();
        }
    });
    function setDialog() {

    }
    function setDialogButtons() {

    }

}
function setDeleteTerm() {
    $("table .fa-trash-o").unbind("click").click(function () {
        var data = {
            term_id: $(this).attr("id")
        };
        $.ajax({
            type: "POST",
            datatype: "application/json",
            url: Routing.generate('bms_visualization_delete_term'),
            data: data,
            success: function (ret) {
                $(".main-row").children(".fa-spinner").remove();
                var id = parseInt(ret['term_id']);
                $("table i#" + id + ".fa-trash-o").parent().parent().remove();
                if ($("div.dialog-panel-event table tbody tr").length == 1) {
                    $("div.dialog-panel-event table tbody tr").remove().append(
                            "<tr id='no-data'>\n\
                                <td colspan='11' class='text-center'>\n\
                                    <h2><span class='label label-primary'> Brak warunków</span></h2>\n\
                                </td>\n\
                            </tr>");
                }
            }
        });
        $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    });
}
function createCondition(panel_id) {

    return $("div.dialog-condition").dialog({
        autoOpen: false,
        width: 1200,
        height: 400,
        modal: true,
        buttons: [
            {
                text: "Zapisz",
                click: function () {
                    var data = new FormData();
                    data.append("register_name", $("form#condition input#panel-term-register").val());
                    data.append("panel_id", panel_id);
                    data.append("condition_type", $("form#condition select#condition_type").val());
                    data.append("condition_value", $("form#condition input#condition_val").val());
                    data.append("effect_type", $("form#condition select#effect_type").val());
                    data.append("effect_content", $("form#condition input#effect-value").val());
                    var fail = false;
                    var fail_log = '';
                    $("form#condition input").each(function () {
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
            setDialogButtons();
        },
        close: function () {
            $(this).dialog('destroy').remove();
        }
    });

    function setDialog() {
    }
    function setDialogButtons() {
        //open variable manager
        $(".input-group-btn button#variableManager").click(function () {
            $.ajax({
                type: "POST",
                datatype: "application/json",
                url: Routing.generate('bms_visualization_load_variable_manager'),
                success: function (ret) {
                    $(".main-row").children(".fa-spinner").remove();
                    $(".main-row").append(ret["template"]);
                    createVariableManager("term-register").dialog("open");
                }
            });
            $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
        });
        setOpenEffectSrc();
        //zmiana typu efektu == zmiana przycisku managera efektów
        $("select#effect_type").change(function () {
            var effect_type = $(this).val();
            $(".input-group-btn button#effectManager").unbind("click");
            $("form#condition input#effect-value").val("");
            switch (effect_type) {
                case "css":
                    $(".input-group-btn button#effectManager").prop('disabled', false);
                    $("form#condition input#effect-value").prop('disabled', true);
                    setOpenEffectCss();
                    break;
                case "src":
                    $(".input-group-btn button#effectManager").prop('disabled', false);
                    $("form#condition input#effect-value").prop('disabled', true);
                    setOpenEffectSrc();
                    break;
                case "animation":
                    $(".input-group-btn button#effectManager").prop('disabled', true);
                    $("form#condition input#effect-value").prop('disabled', false);
                    setOpenEffectAnimation();
                    break;
                case "text":
                    $(".input-group-btn button#effectManager").prop('disabled', true);
                    $("form#condition input#effect-value").prop('disabled', false);
                    break;
                case "popup":
                    $(".input-group-btn button#effectManager").prop('disabled', true);
                    $("form#condition input#effect-value").prop('disabled', false);
                    break;
            }
        });

        function setOpenEffectCss() {
            $(".input-group-btn button#effectManager").click(function () {
                $.ajax({
                    type: "POST",
                    datatype: "application/json",
                    url: Routing.generate('bms_visualization_load_effect_css_manager'),
                    success: function (ret) {
                        $(".main-row").children(".fa-spinner").remove();
                        $(".main-row").append(ret["template"]);
                        createEffectCssManager().dialog("open");
                    }
                });
                $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
            });
        }
        function setOpenEffectSrc() {
            $(".input-group-btn button#effectManager").click(function () {
                $.ajax({
                    type: "POST",
                    datatype: "application/json",
                    url: Routing.generate('bms_visualization_load_image_manager'),
                    success: function (ret) {
                        $(".main-row").children(".fa-spinner").remove();
                        $(".main-row").append(ret["template"]);
                        createImageManager("effect").dialog("open");
                    }
                });
                $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
            });
        }
        function setOpenEffectAnimation() {
            $(".input-group-btn button#effectManager").click(function () {
                alert("Otwieranie Animacja");
            });
        }

    }
    function saveData(data) {
        $.ajax({
            type: "POST",
            data: data,
            contentType: false,
            processData: false,
            url: Routing.generate('bms_visualization_create_term'),
            success: function (ret) {
                $(".main-row").children(".fa-spinner").remove();
                var term = ret["term"][0];
                switch (term.effect_type) {
                    case "src":
                        var effectType = "Wyświetl obraz";
                        break;
                    case "css":
                        var effectType = "Właściwości formatu";
                        break;
                    case "animation":
                        var effectType = "Animacja";
                        break;
                }
                if ($("table tr#no-data").length > 0) {
                    $("table tbody").empty().append("<tr>\n\
                        <td></td>\n\
                        <td class='text-center'>Jeżeli żaden z poniższych warunków nie jest spełniony</td>\n\
                        <td class='text-center'></td>\n\
                        <td class=text-center'></td>\n\
                        <td class='text-center'>Właściwości formatu</td>\n\
                        <td class=''>display;none</td>\n\
                        <td class=''></td>\n\
                        <td></td>\n\
                    </tr>");
                }
                $("table tbody").append(
                        "<tr>\n\
                            <td>" + term.register_name + "</td>\n\
                            <td class='text-center'>" + term.fixedValue + "</td>\n\
                            <td class='text-center'>" + term.condition_type + "</td>\n\
                            <td class='text-center'>" + term.condition_value + "</td>\n\
                            <td class='text-center'>" + effectType + "</td>\n\
                            <td>" + term.effect_content + "</td>\n\
                            <td class='manage text-center'>\n\
                                <i id='" + term.id + "' class='fa fa-trash-o fa-fw fa-red'></i>\n\
                            </td>\n\
                            <td>\n\
                                <input name='checkedTermId[]' value='" + term.id + "' type='checkbox'></input>\n\
                            </td>\n\
                        </tr>");
                setDeleteTerm();
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
            createPanelDialog(ret["panel_id"]).dialog("open");
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
        $(this).show();
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
                    relX = relX - 116;
                } else {
                    relX = relX - 4;
                }
                if ((parseInt($(this).css("top"))) + relY > parseInt($(this).parent().css("height")) / 2) {
                    relY = relY - 21;
                } else {
                    relY = relY - 4;
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
                                <i class='fa fa-fw fa-trash-o fa-red'></i>\n\
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
            var data = {
                panel_id: id
            };
            $.ajax({
                type: "POST",
                datatype: "application/json",
                url: Routing.generate('bms_visualization_load_panel_dialog'),
                data: data,
                success: function (ret) {
                    $(".main-row").children(".fa-spinner").remove();
                    $(".main-row").append(ret["template"]);


                    createPanelDialog(id, ret["register"]).dialog("open");

                }
            });
            $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
        });
        //usuwanie
        $(label + " i.fa-trash-o").click(function () {
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
        height: 450,
        width: 450,
        modal: true,
        buttons: [
            {
                text: "Dodaj",
                click: function () {
                    var data = {
                        width: $("div.dialog-page-add-settings input#width").val(),
                        height: $("div.dialog-page-add-settings input#height").val(),
                        name: $("div.dialog-page-add-settings input#name").val(),
                        backgroundColor: $("div.dialog-page-add-settings input#backgroundColor").val()
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

        },
        close: function () {
            $(this).dialog("close");
        }
    });
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
            createPage(ret['page'], ret['panelList']);
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
            createPage(ret['page'], ret['panelList']);
            setVariables(ret['registers']);
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();

    function setVariables(registers) {
        $.each(registers, function (key, value) {
            if (value !== null) {
                var displayPrecision = parseInt($("div.bms-panel-variable").children("span#" + key).attr("value"));
                var roundValue = parseFloat(value).toFixed(displayPrecision);
            }
            $("div.bms-panel").children("span#" + key).empty().append(roundValue);

            if ($("div.bms-panel-widget").find("div#value" + key).length > 0) {
                var rangeMin = parseFloat($("div.bms-panel-widget").find("div#value" + key).parent().parent().find("div#rangeMin").text().trim());
                var rangeMax = parseFloat($("div.bms-panel-widget").find("div#value" + key).parent().parent().find("div#rangeMax").text().trim());

                var widgetValue = (value - rangeMin) / (rangeMax - rangeMin) * 100;
                if (widgetValue < 0) {
                    widgetValue = 0;
                    $("div.bms-panel-widget").find("div#value" + key).hide();
                }
                $("div.bms-panel-widget").find("div#value" + key).show().animate({
                    left: widgetValue + "%"
                }, 2000);
            }
            if ($("div.bms-panel-widget").find("div#set" + key).length > 0) {
                var rangeMin = parseFloat($("div.bms-panel-widget").find("div#set" + key).parent().parent().find("div#rangeMin").text().trim());
                var rangeMax = parseFloat($("div.bms-panel-widget").find("div#set" + key).parent().parent().find("div#rangeMax").text().trim());
                var widgetValue = (value - rangeMin) / (rangeMax - rangeMin) * 100;
                if (widgetValue < 0) {
                    widgetValue = 0;
                    $("div.bms-panel-widget").find("div#set" + key).hide();
                }
                $("div.bms-panel-widget").find("div#set" + key).show().animate({
                    left: widgetValue + "%"
                }, 2000);
            }

        });
    }

}
//utworzenie nowej strony
function createPage(page, panelList) {
    $(".main-row div.col-md-12").children().remove();
    $(".main-row div.col-md-12").append(page).fadeIn("slow");
    setPatternNet($("input#pattern-net-size").val());
    setPageLabelsEvent();
    setPanelEvents();
    loadPanelList(panelList);

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
            label.children("i.fa-trash-o").click(function () {
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
    //ukryj pokaż panele typu widget na liście
    $('input#panel-list-widget').change(function () {
        $(this).is(':checked') ? $("span.panel-list-widget").parent().parent().parent("div.panel-list").show() : $("span.panel-list-widget").parent().parent().parent("div.panel-list").hide();
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
        var data = {
            panel_id: id
        };
        $.ajax({
            type: "POST",
            datatype: "application/json",
            url: Routing.generate('bms_visualization_load_panel_dialog'),
            data: data,
            success: function (ret) {
                $(".main-row").children(".fa-spinner").remove();
                $(".main-row").append(ret["template"]);

                createPanelDialog(id, ret["register"]).dialog("open");

            }
        });
        $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    });
    //usuwanie
    $("div.panel-list i.fa-trash-o").click(function () {
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
