
//dodawanie warunku
function setDialogButtonEvent(panel_id) {
    //add term
    $("button.add-term").click(function () {
        $.ajax({
            type: "POST",
            datatype: "application/json",
            url: Routing.generate('bms_visualization_load_event_manager'),
            success: function (ret) {
                var mr = $(".main-row");
                mr.children(".fa-spinner").remove();
                mr.append(ret['template']);
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
                var mr = $(".main-row");
                mr.children(".fa-spinner").remove();
                mr.append(ret["template"]);
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
                var mr = $(".main-row");
                mr.children(".fa-spinner").remove();
                mr.append(ret["template"]);
                createVariableManager("progress-bar-set").dialog("open");
            }
        });
        $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    });
}

function createVariableManager(fw) {
    return $("div.variable-manager").dialog({
        autoOpen: false,
        width: $(window).width() * 0.8,
        height: $(window).height() * 0.8,
        modal: true,
        buttons: [
            {
                text: "Zapisz",
                click: function () {
                    var panelForm = $("div#panel-form");
                    var value = $("div.variable-manager input#register").val();
                    var res = value.split("&");
                    var dialogCondition = $("div.dialog-condition");
                    if (fw === "data-source") {
                        panelForm.find("input#panel-source-content").val(res[0]);
                        panelForm.find("input#panel_contentSource").val(res[0]);
                        // $("div#panel-form input#panel_variableValue").val(res[1]);
                    } else if (fw === "term-register") {
                        dialogCondition.find("input#panel-term-register").val(res[0]);
                        dialogCondition.find("input#panel-term-register-value").val(res[1]);
                    } else if (fw === "progress-bar-value") {
                        $("input#progress-bar-value").val(res[0]);
                    } else if (fw === "progress-bar-set") {
                        $("input#progress-bar-set").val(res[0]);
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
        $("input#deviceSearch").keyup(function () {
            $("div.variable-manager input:not(#deviceSearch)").val("");
            var data = this.value.toUpperCase().split("&");
            var rows = $("div.register-choice").find("div#deviceName");
            if (this.value == "") {
                rows.parent().show();
                return;
            }
            rows.parent().hide();
            rows.filter(function () {
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
            rows.filter(function () {
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
            rows.filter(function () {
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
            rows.filter(function () {
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
            rows.filter(function () {
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
        width: $(window).width() * 0.8,
        height: $(window).height() * 0.8,
        modal: true,
        buttons: [
            {
                text: "Zapisz",
                click: function () {
                    var panelForm = $("div#panel-form");
                    var imageManager = $("div.image-manager");
                    var imgSource = imageManager.find("div.thumbnail-list div.selected img").attr("src");
                    if (fw === "data-source") {
                        panelForm.find("input#panel_width").val(imageManager.find("input#resolutionX").val());
                        panelForm.find("input#panel_height").val(imageManager.find("input#resolutionY").val());
                        panelForm.find("input#panel_borderWidth").val(0);
                        panelForm.find("input#panel_contentSource").val(imgSource);
                        panelForm.find("input#panel-source-content").val(imgSource);
                    } else if (fw === "effect") {
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
        var img = new Image();
        var imageManager = $("div.image-manager");
        //loading image from disk
        imageManager.find("input#image").change(function (event) {
            var src;
            input = event.target;
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    img.onload = function () {
                        src = e.target.result;
                        var data = new FormData();
                        data.append('file', input.files[0]);
                        data.append("fileName", input.files[0].name);
                        data.append("resolutionX", this.width);
                        data.append("resolutionY", this.height);
                        saveData(data);
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        });
        //choose image
        $("div.thumbnail-list div").click(function () {
            setClickOnImage(this);
        });
        //change size of image
        imageManager.find("input#resolutionX").change(function () {
            var ar = img.width / img.height;
            var h = $(this).val() / ar;
            $("div.image-manager input#resolutionY").val(Math.round(h));

        });
        imageManager.find("input#resolutionY").change(function () {
            var ar = img.width / img.height;
            var w = $(this).val() * ar;
            $("div.image-manager input#resolutionX").val(Math.round(w));
        });
    }

    function setClickOnImage(i) {
        var imageManager = $("div.image-manager");
        $("div.thumbnail-list div").removeClass("selected");
        $(i).addClass("selected");
        imageManager.find("input#imageName").val($(i).attr("id"));
        var img = new Image();
        img.onload = function () {
            imageManager.find("input#resolutionX").val(this.width);
            imageManager.find("input#resolutionY").val(this.height);
        };
        img.src = $(i).children("img").attr("src");
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
                var imageManager = $("div.image-manager");
                var imageName = ret["fileName"];
                imageManager.find("input#imageName").val(imageName);
                imageManager.find("input#resolutionX").val(ret['imageWidth']);
                imageManager.find("input#resolutionY").val(ret['imageHeight']);
                imageManager.find("div.thumbnail-list").append("<div id='" + imageName + "' class='text-center'>" +
                    "<img class='img-responsive' src='" + ret['url'] + "' />" +
                    "</div>");
                imageManager.find("div.thumbnail-list div").last().click(function () {
                    setClickOnImage(this);
                });
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
                var tr = $("div.dialog-panel-event table tbody tr");
                if (tr.length == 1) {
                    tr.remove().append(
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
                    var mr = $(".main-row");
                    mr.children(".fa-spinner").remove();
                    mr.append(ret["template"]);
                    createVariableManager("term-register").dialog("open");
                }
            });
            $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
        });
        setOpenEffectSrc();
        //zmiana typu efektu == zmiana przycisku managera efektów
        $("select#effect_type").change(function () {
            var effect_type = $(this).val();
            var effectButton = $(".input-group-btn button#effectManager");
            var effectValue = $("form#condition input#effect-value");
            effectButton.unbind("click");
            effectValue.val("");
            switch (effect_type) {
                case "css":
                    effectButton.prop('disabled', false);
                    effectValue.prop('disabled', true);
                    setOpenEffectCss();
                    break;
                case "src":
                    effectButton.prop('disabled', false);
                    effectValue.prop('disabled', true);
                    setOpenEffectSrc();
                    break;
                case "animation":
                    effectButton.prop('disabled', true);
                    effectValue.prop('disabled', false);
                    setOpenEffectAnimation();
                    break;
                case "text":
                    effectButton.prop('disabled', true);
                    effectValue.prop('disabled', false);
                    break;
                case "popup":
                    effectButton.prop('disabled', true);
                    effectValue.prop('disabled', false);
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
                        var mr = $(".main-row");
                        mr.children(".fa-spinner").remove();
                        mr.append(ret["template"]);
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
                        var mr = $(".main-row");
                        mr.children(".fa-spinner").remove();
                        mr.append(ret["template"]);
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
                var effectType;
                switch (term.effect_type) {
                    case "src":
                        effectType = "Wyświetl obraz";
                        break;
                    case "css":
                        effectType = "Właściwości formatu";
                        break;
                    case "animation":
                        effectType = "Animacja";
                        break;
                }
                var tbody = $("table tbody");
                if ($("table tr#no-data").length > 0) {
                    tbody.empty().append("<tr>\n\
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
                tbody.append(
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
                                <input name='checkedTermId[]' value='" + term.id + "' type='checkbox'/>\n\
                            </td>\n\
                        </tr>");
                setDeleteTerm();
            }
        });
        $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
    }

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

function setVariables(registers) {
    $.each(registers, function () {
        var key = this.name;
        var panel_id = this.panel_id;
        var randValue;
        var color;
        if (this.fixedValue !== null) {
            randValue = this.fixedValue;
            color = "initial";
        } else {
            randValue = (Math.floor(Math.random() * 10000) + 1) / 100;
            color = "#990000";
        }

        var displayPrecision = $("div#" + panel_id + ".bms-panel-variable").children("input#" + key).val();
        var roundValue = parseFloat(randValue).toFixed(displayPrecision);
        $("div#" + panel_id + ".bms-panel").children("span#" + key).empty().append(roundValue).css({"color": color});
    });
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
        if (!$(this).hasClass("active")) {
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
        panel.css({zIndex: zIndex});
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
        panel.css({zIndex: zIndex});
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
    // $("div.panel-list i.fa-clone").click(function () {
    //     var id = $(this).parent().parent().parent().attr("id");
    //     var data = {
    //         panel_id: id
    //     };
    //     copyPanel(data);
    // });
    // ustawienia
    $("div.panel-list i.fa-cogs").click(function () {
        var id = $(this).parent().parent().parent().attr("id");
        var panel = $("div#" + id + ".bms-panel");
        var data = {panel_id: id};
        editPanel(data);
        console.log("asdasd");
        panel.remove();
    });
    // usuwanie
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
