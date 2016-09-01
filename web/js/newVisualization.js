var defaultPatternNetSize = 50;

$(document).ready(function () {


    setPatternNet(defaultPatternNetSize);
    //setPanelEvents();
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

// //dodanie obsługi zdarzeń na każdym panelu na stronie
// function setPanelEvents() {
//     var panels = $("div.bms-panel");
//     panels.each(function () {
//         //pobranie id panelu
//         var id = $(this).attr("id");
//         var aR = $(this).children("img").length > 0;
//         $(this).show().removeAttr("onclick").unbind("mouseenter mouseleave");
//         //draggable and resizable
//         $(this).draggable({
//             containment: "parent",
//             snap: ".pattern-net",
//             snapTolerance: 10,
//             snapMode: "both",
//             distance: 5,
//             stop: function (event, ui) {
//                 var data = {
//                     panel_id: id,
//                     topPosition: ui.helper.css("top"),
//                     leftPosition: ui.helper.css("left"),
//                     width: ui.helper.css("width"),
//                     height: ui.helper.css("height"),
//                     zIndex: ui.helper.css("z-index")
//                 };
//                 ajaxMovePanel(data);
//             }
//         }).resizable({
//             containment: "parent",
//             snap: ".pattern-net",
//             snapTolerance: 10,
//             snapMode: "both",
//             aspectRatio: aR,
//             handles: "se",
//             minWidth: 15,
//             minHeight: 15,
//             resize: function (event, ui) {
//                 var bw = ui.element.css("border-top-width");
//                 bw = parseInt(bw);
//                 var delta_x = ui.size.width - (ui.originalSize.width + 2 * bw);
//                 var delta_y = ui.size.height - (ui.originalSize.height + 2 * bw);
//                 if (delta_x !== 0) {
//                     ui.size.width += 2 * bw;
//                 }
//                 if (delta_y !== 0) {
//                     ui.size.height += 2 * bw;
//                 }
//                 ui.element.css({lineHeight: ui.element.height() + "px"});
//
//                 if (ui.element.hasClass("bms-panel-image")) {
//                     var image = $(this).children("img");
//                     var mW = image[0].naturalWidth;
//                     if (ui.size.width > mW) {
//                         ui.size.width = mW;
//                     }
//                     var mH = image[0].naturalHeight;
//                     if (ui.size.height > mH) {
//                         ui.size.height = mH;
//                     }
//                 }
//
//
//                 ui.element.addClass("hover");
//             },
//             stop: function (event, ui) {
//                 ui.element.removeClass("hover");
//                 var data = {
//                     panel_id: id,
//                     topPosition: ui.helper.css("top"),
//                     leftPosition: ui.helper.css("left"),
//                     width: ui.element.css("width"),
//                     height: ui.element.css("height"),
//                     zIndex: ui.element.css("zIndex")
//                 };
//                 ajaxMovePanel(data);
//             }
//         });
//         //hover
//         $(this).hover(function () {
//             $(".panel-list-container div#" + id + " span.label").css({backgroundColor: "#FF0000"});
//         }, function () {
//             if ($("div#" + id + ".panel-list").hasClass("active")) {
//
//             } else {
//                 $(".panel-list-container div#" + id + " span.label").css({backgroundColor: ""});
//             }
//         });
//         //click
//         $(this).click(function (e) {
//             var panelLabel = $("span.label-bms-panel");
//             $(this).css({zIndex: 100});
//             if (panelLabel.length > 0) {
//                 var relX = parseInt(panelLabel.css("left"));
//                 var relY = parseInt(panelLabel.css("top"));
//             } else {
//                 var relX = e.pageX - $(this).offset().left;
//                 var relY = e.pageY - $(this).offset().top;
//                 if ((parseInt($(this).css("left"))) + relX > parseInt($(this).parent().css("width")) / 2) {
//                     relX = relX - 116;
//                 } else {
//                     relX = relX - 4;
//                 }
//                 if ((parseInt($(this).css("top"))) + relY > parseInt($(this).parent().css("height")) / 2) {
//                     relY = relY - 21;
//                 } else {
//                     relY = relY - 4;
//                 }
//             }
//             panelLabel.remove();
//             var label = "<span id=" + id + " class='label label-bms-panel' \n\
//                                style='top: " + relY + "px; \n\
//                                       left: " + relX + "px;\n\
//                                       font-size: initial;\n\
//                                       font-weight: initial;\n\
//                                       text-decoration: none;\n\
//                                       font-style: initial;'>\n\
//                             <div>\n\
//                                 " + id + "\n\
//                                 <i class='fa fa-fw fa-clone fa-blue'></i>\n\
//                                 <i class='fa fa-fw fa-cogs fa-yellow'></i>\n\
//                                 <i class='fa fa-fw fa-trash-o fa-red'></i>\n\
//                             </div>\n\
//                         </span>";
//             $(this).append(label);
//             setPanelLabelEvents(id);
//         });
//         //mouseleave
//         $(this).mouseleave(function () {
//             var zI = $(".panel-list-container div#" + id + " span.label").attr("value");
//             $(this).css({zIndex: zI});
//             $("span.label-bms-panel").remove();
//         });
//     });
//
//
// }
//
// function setPanelLabelEvents(id) {
//     var label = "span#" + id + ".label-bms-panel";
//     $(label).unbind("click");
//     $(label + " i.fa-clone").click(function () {
//
//     });
//     $(label + " i.fa-cogs").click(function () {
//
//     });
//     $(label + " i.fa-trash-o").click(function () {
//
//     });
// }