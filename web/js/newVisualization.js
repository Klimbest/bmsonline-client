var defaultPatternNetSize = 50;

$(document).ready(function () {


    setPatternNet(defaultPatternNetSize);
    setPanelEvents();
    //ON/OFF siatka pomocnicza
    $("button.btn-pattern-net").click(togglePatternNet);
    //ustaw domyślny rozmiar siatki i zmianę rozmiaru
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

//dodanie obsługi zdarzeń na każdym panelu na stronie
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

                ajaxMoveElement(data, url);
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
        success: function (ret) {
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