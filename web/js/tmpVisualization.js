
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
