var defaultPatternNetSize = 50;

$(document).ready(function () {

    setPatternNet(defaultPatternNetSize);
    setPanelEvents();
    $("button.btn-pattern-net").click(togglePatternNet);
    $("input#pattern-net-size").change(function () {
        setPatternNet($(this).val());
    });
});

function togglePatternNet() {
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
}

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

function setPanelEvents() {
    var panels = $("div.bms-panel");
    panels.each(function () {
        //pobranie id panelu
        var id = $(this).attr("id");
        var element_type = setElementType($(this));

        var aR = $(this).children("img").length > 0;
        $(this).removeAttr("onclick").unbind("mouseenter mouseleave");
        $(this).click(function () {
            $(this).find("span.label-bms-panel").show();
        });
        $(this).mouseleave(function () {

            $(this).find("span.label-bms-panel").hide();
        });
        //draggable and resizable
        $(this).draggable({
            containment: "parent",
            snap: ".pattern-net",
            snapTolerance: 10,
            snapMode: "both",
            distance: 5,
            stop: function (event, ui) {
                var data = {
                    element_type: element_type,
                    element_id: id,
                    topPosition: ui.helper.css("top"),
                    leftPosition: ui.helper.css("left"),
                    width: ui.helper.css("width"),
                    height: ui.helper.css("height")
                };
                ajaxMoveElement(data);
            }
        }).resizable({
            containment: "parent",
            snap: ".pattern-net",
            snapTolerance: 10,
            snapMode: "both",
            aspectRatio: aR,
            handles: "se",
            minWidth: 15,
            minHeight: 15,
            resize: function (event, ui) {
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

                if (ui.element.hasClass("bms-panelimage")) {
                    var image = $(this).children("img");
                    var mW = image[0].naturalWidth;
                    if (ui.size.width > mW) {
                        ui.size.width = mW;
                    }
                    var mH = image[0].naturalHeight;
                    if (ui.size.height > mH) {
                        ui.size.height = mH;
                    }
                }
                ui.element.addClass("hover");
            },
            stop: function (event, ui) {
                ui.element.removeClass("hover");
                var data = {
                    element_type: element_type,
                    element_id: id,
                    topPosition: ui.helper.css("top"),
                    leftPosition: ui.helper.css("left"),
                    width: ui.element.css("width"),
                    height: ui.element.css("height")
                };

                ajaxMoveElement(data);
            }
        });

    });
}

function ajaxMoveElement(data) {
    $.ajax({
        type: "POST",
        datatype: "application/json",
        url: Routing.generate("element_move"),
        data: data,
        success: function () {
            $(".main-row").children(".fa-spinner").remove();
        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}

function setElementType(element) {
    var type = element.attr('class').split(" ");
    switch (type[1]) {
        case 'bms-panelimage' :
            return 'PanelImage';
            break;
        case 'bms-paneltext' :
            return 'PanelText';
            break;
        case 'bms-panelvariable' :
            return 'PanelVariable';
            break;
        case 'bms-inputbutton' :
            return 'InputButton';
            break;
        case 'bms-inputnumber' :
            return 'InputNumber';
            break;
        case 'bms-inputrange' :
            return 'InputRange';
            break;
        case 'bms-gadgetclock' :
            return 'GadgetClock';
            break;
        case 'bms-gadgetprogressbar' :
            return 'GadgetProgressBar';
            break;
    }
}

function updateTextAlign(align, type) {
    var form = $("form");
    var buttonCenter = form.find("button.btn-align-center");
    var buttonLeft = form.find("button.btn-align-left");
    var buttonRight = form.find("button.btn-align-right");
    form.find("input#panel_" + type + "_textAlign").val(align);
    switch (align) {
        case "left":
            buttonLeft.addClass("active");
            buttonCenter.removeClass("active");
            buttonRight.removeClass("active");
            break;
        case "center":
            buttonLeft.removeClass("active");
            buttonCenter.addClass("active");
            buttonRight.removeClass("active");
            break;
        case "right":
            buttonLeft.removeClass("active");
            buttonCenter.removeClass("active");
            buttonRight.addClass("active");
            break;
    }
}

function updateFontWeight(type) {
    var form = $("form");
    var button = form.find("button#panel_fontWeight");
    var input = form.find("input#panel_" + type + "_fontWeight");
    button.toggleClass("active");
    button.hasClass("active") ? input.val("bold") : input.val("normal");
}

function updateTextDecoration(type) {
    var form = $("form");
    var button = form.find("button#panel_textDecoration");
    var input = form.find("input#panel_" + type + "_textDecoration");
    button.toggleClass("active");
    button.hasClass("active") ? input.val("underline") : input.val("none");
}

function updateFontStyle(type) {
    var form = $("form");
    var button = form.find("button#panel_fontStyle");
    var input = form.find("input#panel_" + type + "_fontStyle");
    button.toggleClass("active");
    button.hasClass("active") ? input.val("italic") : input.val("normal");

}

function updateBorderRadius(type) {
    var form = $("form");
    form.find("input#panel_" + type + "_borderRadiusLeftTop").val(form.find("input#borderRadiusTL").val());
    form.find("input#panel_" + type + "_borderRadiusLeftBottom").val(form.find("input#borderRadiusBL").val());
    form.find("input#panel_" + type + "_borderRadiusRightTop").val(form.find("input#borderRadiusTR").val());
    form.find("input#panel_" + type + "_borderRadiusRightBottom").val(form.find("input#borderRadiusBR").val());
}

function updateBackgroundColor(type){
    var form = $("form");
    var inputBackground = form.find("input#panel_" + type + "_backgroundColor");
    var inputOpacity = form.find("input#panel_" + type + "_backgroundOpacity");
    var backgroundColor = inputBackground.val();
    $("div#color-hex").empty().append(backgroundColor);
    var rgba = "rgba(" + hexToR(backgroundColor) + ", " + hexToG(backgroundColor) + ", " + hexToB(backgroundColor) + ", " + inputOpacity.val() + ")";
    $("div#color-rgba").empty().append(rgba);
}

function initForm(type) {
    var form = $("form");
    form.find("input#panel_" + type + "_borderRadiusLeftTop").bind("input", function () {
        form.find("input#borderRadiusTL").val($(this).val());
    });
    form.find("input#panel_" + type + "_borderRadiusLeftBottom").bind("input", function () {
        form.find("input#borderRadiusBL").val($(this).val());
    });
    form.find("input#panel_" + type + "_borderRadiusRightTop").bind("input", function () {
        form.find("input#borderRadiusTR").val($(this).val());
    });
    form.find("input#panel_" + type + "_borderRadiusRightBottom").bind("input", function () {
        form.find("input#borderRadiusBR").val($(this).val());
    });
    form.find("input#borderRadiusTL").val(form.find("input#panel_" + type + "_borderRadiusLeftTop").val());
    form.find("input#borderRadiusBL").val(form.find("input#panel_" + type + "_borderRadiusLeftBottom").val());
    form.find("input#borderRadiusTR").val(form.find("input#panel_" + type + "_borderRadiusRightTop").val());
    form.find("input#borderRadiusBR").val(form.find("input#panel_" + type + "_borderRadiusRightBottom").val());
    if (form.find("input#panel_" + type + "_fontWeight").val() == "bold") {
        form.find("button#panel_fontWeight").addClass("active");
    }
    if (form.find("input#panel_" + type + "_textDecoration").val() == "underline") {
        form.find("button#panel_textDecoration").addClass("active");
    }
    if (form.find("input#panel_" + type + "_fontStyle").val() == "italic") {
        form.find("button#panel_fontStyle").addClass("active");
    }
    var align = form.find("input#panel_" + type + "_textAlign").val();
    updateTextAlign(align);
    form.find("select#panel_source").val(form.find("input#panel_" + type + "_source").val());
}

function updatePanelVariableSource() {
    var form = $("form");
    var select = form.find("select#panel_source");
    var input = form.find("input#panel_variable_source");
    input.val(select.val());
}

function setSelectedImage(element) {
    var form = $("form");
    var inputWidth = form.find("input#panel_image_width");
    var inputHeight = form.find("input#panel_image_height");
    form.find("div.thumbnail-list div").removeClass("selected");
    $(element).addClass("selected");
    var source = $(element).children("img").attr("src");
    form.find("span#image_name").empty().append(source);
    form.find("input#panel_image_source").val(source);
    var img = new Image();
    img.onload = function () {
        var ar = img.width / img.height;
        inputWidth.val(this.width).attr("max", this.width);
        inputWidth.change(function () {
            var h = form.find("input#panel_image_width").val() / ar;
            form.find("input#panel_image_height").val(Math.round(h));
        });
        inputHeight.val(this.height).attr("max", this.height);
        inputHeight.change(function () {
            var w = $(this).val() * ar;
            form.find("input#panel_image_width").val(Math.round(w));
        });
    };
    img.src = source;
}

function setAddingNewImage() {
    var form = $("form");
    form.find("input#image").change(function (event) {
        var src;
        var img = new Image();
        var input = event.target;
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                img.onload = function () {
                    src = e.target.result;
                    if ($("div.thumbnail-list").last().find("div#" + input.files[0].name.replace(".", "\\.")).length == 0) {
                        var data = new FormData();
                        data.append('file', input.files[0]);
                        data.append("fileName", input.files[0].name);
                        sendImageToServer(data);
                    } else {
                        alert("Obrazek o tej nazwie istnieje w systemie.");
                    }
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    });
}

function sendImageToServer(data) {
    $.ajax({
        type: "POST",
        url: Routing.generate('send_image_to_server'),
        data: data,
        contentType: false,
        processData: false,
        success: function (ret) {
            $(".main-row").children(".fa-spinner").remove();
            var list = $("div.thumbnail-list").last();
            list.append(
                "<div id='" + ret['fileName'] + "' class='text-center' onclick='setSelectedImage(this)'>" +
                "<img class='img-responsive' src='" + ret['url'] + "' />" +
                "<a href='" + ret['href'] + "' >" +
                "<i class='fa fa-remove fa-red'></i></a>" +
                "</div>");

            list.children("div").hover(function () {
                $(this).children("a").show();
            }, function () {
                $(this).children("a").hide();
            });

            list.find("a").click(function () {
                return confirm("Na pewno usunąć obrazek z systemu?")
            });

        }
    });
    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
