/* global parseInt, NULL */
Number.prototype.pad = function (size) {
    var s = String(this);
    while (s.length < (size || 2)) {
        s = "0" + s;
    }
    return s;
};

var $collectionHolder;
var $addTagLink = $('<a href="#" class="add_tag_link">Add a tag</a>');
var $newLinkLi = $('<li></li>').append($addTagLink);

$(document).ready(function () {

    setInterval(function () {
        refreshPage();
    }, 1000 * 60);

    formEvents();
    tableEvents();

    setActiveLevel($("div.active"));

    $(".target-level li div").click(function () {
        var url = Routing.generate('bms_configuration_index');
        window.location.href = url;
    });

    $(".communicationType-level li div:not(.device-level li div)").click(function () {

        var cid = $(this).attr("id");

        $(".main-row").children().remove();
        $(".communicationType-level li ul").hide().prev("div").removeClass("active").children("i.fa").removeClass("fa-angle-down");
        $(".register-level li div div").removeClass("active");
        $(".device-level li.new-item div").removeClass("active");
        $(".register-level li div.new-item").removeClass("active");
        $(this).toggleClass("active").next("ul.device-level").show();
        $(this).children("i.fa").addClass("fa-angle-down");

        var url = Routing.generate('bms_configuration_communication_type', {comm_id: cid});
        ajaxAppend(url);

    });

    $(".device-level li div:not(.register-level li div):not(li.new-item div)").click(function () {


        var cid = $(".communicationType-level div.active").attr("id");
        var did = $(this).attr("id");

        $(".main-row").children().remove();
        $(".device-level li ul").hide().prev("div").removeClass("active").children("i.fa:last-child").removeClass("fa-angle-down");
        $(".register-level li div div, div.new-item").removeClass("active");
        $(this).toggleClass("active").next("ul.register-level").show();
        $(this).children("i.fa-angle-left").addClass("fa-angle-down");

        var url = Routing.generate('bms_configuration_device', {comm_id: cid, device_id: did});
        ajaxAppend(url);
    });

    $(".register-level li div div").click(function () {


        var cid = $(".communicationType-level div.active").attr("id");
        var did = $(".device-level div.active").attr("id");
        var rid = $(this).attr("id");

        $(".main-row").children().remove();
        $(".register-level li div div, .register-level li div, .device-level li.new-item div, .register-level li div.new-item").removeClass("active");
        $(this).toggleClass("active");

        var url = Routing.generate('bms_configuration_register', {comm_id: cid, device_id: did, register_id: rid});
        ajaxAppend(url);
    });

    $(".device-level li.new-item div").click(function () {

        var cid = $(".communicationType-level div.active").attr("id");

        $(".main-row").children().remove();
        $(".device-level li ul").hide().prev("div").removeClass("active");
        $(".register-level li div.new-item").removeClass("active");
        $(this).toggleClass("active");

        var url = Routing.generate('bms_configuration_add_device', {comm_id: cid});
        ajaxAppend(url);
    });

    $(".register-level li div.new-item").click(function () {

        var cid = $(".communicationType-level div.active").attr("id");
        var did = $(".device-level div.active").attr("id");

        $(".main-row").children().remove();
        $(".register-level li div div").removeClass("active");
        $(".device-level li.new-item div").removeClass("active");
        $(this).toggleClass("active");

        var url = Routing.generate('bms_configuration_add_register', {comm_id: cid, device_id: did});
        ajaxAppend(url);
    });
});

function setActiveLevel(item) {
    var ctl, dl, tid, cid, did;
    ctl = item.parent().parent("ul.nav.communicationType-level");
    dl = item.parent().parent("ul.nav.device-level");
    if (dl.length > 0) {
        $(".main-row").children().remove();
        ctl.children().children("div.active").next("ul.device-level").show();
        ctl.children().children("div.active").children("i.fa").addClass("fa-angle-down");
        dl.children().children("div.active").next("ul.register-level").show();
        dl.children().children("div.active").children("i.fa-angle-left").addClass("fa-angle-down");
        cid = dl.prev("div.row.active").attr("id");
        did = dl.children().children("div.active").attr("id");

        var url = Routing.generate('bms_configuration_device', {comm_id: cid, device_id: did});
        ajaxAppend(url);
    } else if (ctl.length > 0) {
        $(".main-row").children().remove();
        ctl.children().children("div.active").next("ul.device-level").show();
        ctl.children().children("div.active").children("i.fa").addClass("fa-angle-down");
        cid = ctl.children().children("div.active").attr("id");
        var url = Routing.generate('bms_configuration_communication_type', {comm_id: cid});
        ajaxAppend(url);
    } else {
        $(".target-level").next("ul").children().children("ul").hide().prev("div").removeClass("active");

    }
}

function ajaxAppend(url) {

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        cache: false,
        success: function (ret) {
            $(".main-row").children().remove();
            $(".main-row").hide().append(ret["ret"]).fadeIn("slow");
            refreshPage();
            formEvents();
            tableEvents();
            updateAddressFormat();
        }
    });
    $(".main-row").addClass("text-center").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();

}

function formEvents() {
    //obsługa przycisku edytuj(odblokowanie pól formularza, zmiana przycisków obsługi)
    $(".btn-edit").click(function () {
        $(this).hide().parent().children().hide();
        $(this).parent().find(".btn-save, .btn-cancel").show();
        $(this).parent().parent().parent().find("input[type=text],\n\
                                                    input[type=number],\n\
                                                    input[type=checkbox],\n\
                                                    select,\n\
                                                    textarea").removeAttr('disabled');

    });
    //zmiana wyświetlania pól formularza w zależności od wyboru typu połączenia
    $("select#appbundle_communicationtype_type").change(function () {
        var id = $(this).parent().parent().parent().attr("id");
        var v = $(this).val();
        if (v === "RTU" || v === "ASCII") {
            $("form#" + id + " .rtuascii").val(null).show();
            $("form#" + id + " .tcpip").val(-1).hide();
        } else if (v === "TCP/IP") {

            $("form#" + id + " .rtuascii").val(-1).hide();
            $("form#" + id + " .tcpip").val(null).show();
        }
    });
    //obsługa przycisku anuluj(zablokowanie pól formularza, zmiana przycisków obsługi)
    $(".btn-cancel").click(function () {

        $(this).hide().parent().children().hide();
        $(this).parent().find(".btn-edit, .btn-delete").show();
        $(this).parent().parent().parent().find("input[type=text],\n\
                                                input[type=number],\n\
                                                input[type=checkbox],\n\
                                                select,\n\
                                                textarea").attr('disabled', true);
    });
    //obsługa przycisku usuń(wyświetlenie potwierdzenia usunięcia)
    $(".btn-delete").click(function () {
        if (confirm("Na pewno usunąć urządzenie razem z wszystkimi rejestrami do niego przypisanymi?")) {

        } else {
            return false;
        }
    });
    //ustawienie pól formularza jako zablokowanych
    $(".enabled input, .enabled select, .enabled textarea").removeAttr('disabled');
    //ustawienie wartości domyślnych
    if ($("div.new-item").hasClass("active")) {
        $("select#bmsconfigurationbundle_register_register_size").val(16);
        $("input#bmsconfigurationbundle_register_modificator_read").val(1);
        $("textarea#bmsconfigurationbundle_register_description2").val("Dodatkowe informacje");
        $("input#bmsconfigurationbundle_register_active").attr("checked", true);
        $("select#bmsconfigurationbundle_register_function").val("03");
    }
    //chowanie pokazywanie main-row
    $(".main-row span.hide-mainrow-label").click(function () {
        $(this).parent().parent().next("div.well").toggle();
        $(this).children("i.fa").toggleClass('fa-angle-down');
    });
    $("input#bmsconfigurationbundle_register_bit_register").unbind("change");
    $("input#bmsconfigurationbundle_register_bit_register").change(function () {
        if ($(this).is(':checked')) {
            $('div.bits').append("<div class='col-md-12'></div>");
            var bit_container = $('div.bits').children("div");
            for (var i = 0; i < $('select#bmsconfigurationbundle_register_register_size').val(); i++) {
                bit_container.append("<div class='row bit_registers'>\n\
                                        <div class='col-md-2'>\n\
                                            <div class='form-group'>\n\
                                                <input id='bmsconfigurationbundle_register_bit_registers_" + i + "_name' class='form-control'\n\
                                                        name='bmsconfigurationbundle_register[bit_registers][" + i + "][name]' required='required' maxlength='16' \n\
                                                        class='form-control' value='C1_B_8_B" + i + "' type='text'>\n\
                                                </input>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class='col-md-4'>\n\
                                            <div class='form-group'>\n\
                                                <input id='bmsconfigurationbundle_register_bit_registers_" + i + "_description' class='form-control'\n\
                                                        name='bmsconfigurationbundle_register[bit_registers][" + i + "][description]' type='text'>\n\
                                                </input>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class='col-md-1'>\n\
                                            <div class='form-group'>\n\
                                                <input id='bmsconfigurationbundle_register_bit_registers_" + i + "_bitValue' class='form-control'\n\
                                                        name='bmsconfigurationbundle_register[bit_registers][" + i + "][bitValue]'\n\
                                                        max='1' min='0' step='1' value='0' type='number'>\n\
                                                </input>\n\
                                            </div>\n\
                                        </div>\n\
                                        <input id='bmsconfigurationbundle_register_bit_registers_" + i + "_bitPosition'\n\
                                                name='bmsconfigurationbundle_register[bit_registers][" + i + "][bitPosition]'\n\
                                                value='" + i + "' type='hidden'>\n\
                                        </input>\n\
                                        <input id='bmsconfigurationbundle_register_bit_registers_" + i + "_register' class='form-control hidden-item'\n\
                                                name='bmsconfigurationbundle_register[bit_registers][" + i + "][register]'\n\
                                                value='" + parseInt($("ul.nav.register-level li div div.active").attr("id")) + "' type='number'>\n\
                                        </input>\n\
                                      </div>");
            }
        } else {
            $('div.bits').empty();
        }
    });

    $("input#read_mod_val").val($("input#bmsconfigurationbundle_register_modificator_read").val());

    $("select#read_mod_operator, input#read_mod_val").change(function () {
        var oper = $("select#read_mod_operator").val();
        var mod = $("input#read_mod_val").val();
        if (oper === "*") {
            $("#bmsconfigurationbundle_register_modificator_read").val(mod);
        } else if (oper === "/") {
            mod = 1 / mod;
            $("input#bmsconfigurationbundle_register_modificator_read").val(mod);
        }
    });

}

function tableEvents() {
    //przyciski ukrywające kolumny
    $("input.hidding").click(function () {
        var className = $(this).attr('id');
        $("." + className).toggle();
        if ($(this).hasClass("clicked"))
            $(this).removeClass("clicked");
        else
            $(this).addClass("clicked");
    });
    //przycisk zaznaczający wszystkie checkboxy
    $(".checkAll:button").click(function () {
        var state = $("td input[type='checkbox']").prop("checked");
        $("td input[type='checkbox']").prop("checked", !state);
        if (state) {
            $(this).val("Zaznacz wszystkie");
        } else {
            $(this).val("Odznacz wszystkie");
        }
    });
    //chowanie pokazywanie przycisków ukrywania kolumn
    $(".hide-button-label").click(function () {
        $(".hide-button-container").toggle();
        $(".hide-button-label i.fa").toggleClass('fa-angle-down');
    });
    //przekierowanie do edycji urządzenia z tabeli
    $("tr td.manage i.fa-edit.fa-device").click(function () {

        var cid = $(".communicationType-level div.active").attr("id");
        var did = $(this).attr("id");

        $(".main-row").children().remove();
        $(".device-level li div#" + did + ".row").toggleClass("active").next("ul.register-level").show();
        $(".device-level li div#" + did + ".row").children("i.fa:last-child").addClass("fa-angle-down");
        var url = Routing.generate('bms_configuration_device', {comm_id: cid, device_id: did});
        ajaxAppend(url);
    });
    //przekierowanie do edycji rejestru z tabeli
    $("tr td.manage i.fa-edit.fa-register").click(function () {

        var cid = $(".communicationType-level div.active").attr("id");
        var did = $(".device-level div.active").attr("id");
        var rid = $(this).attr("id");

        $(".main-row").children().remove();
        $(".register-level:visible li div.row div#" + rid + ".text-center").toggleClass("active");
        var url = Routing.generate('bms_configuration_register', {comm_id: cid, device_id: did, register_id: rid});
        ajaxAppend(url);
    });
    //formatowanie komórek z wartością hex
    $("td.modbus_address_hex").each(function () {
        var hexVal = parseInt($(this).text(), 10).toString(16).toUpperCase();

        switch (hexVal.length) {
            case 1:
                hexVal = "0" + hexVal;
                break;
            default:
                break;
        }

        $(this).empty().append(hexVal);
    });
    //formatiowanie komórek z wartością dec
    $("td.modbus_address_dec").each(function () {
        var decVal = parseInt($(this).text(), 10);
        $(this).empty().append(decVal.pad(3));
    });

    $("td.register_address_hex").each(function () {
        var hexVal = $(this).text().toUpperCase();

        switch (hexVal.length) {
            case 1:
                hexVal = "000" + hexVal;
                break;
            case 2:
                hexVal = "00" + hexVal;
                break;
            case 3:
                hexVal = "0" + hexVal;
                break;
            default:
                break;
        }

        $(this).empty().append(hexVal);
    });
    $("td.register_address_dec").each(function () {
        var decVal = parseInt($(this).text(), 16);
        $(this).empty().append(decVal.pad(5));
    });
    //odświeżenie rekordu
    $("button.refresh").click(function () {
        refreshPage();
    });
}

function updateAddressFormat() {
    modbusDecChange($(".address_modbus_dec input"));

    $(".address_modbus_hex input").change(function () {
        modbusHexChange($(this));
    });

    $(".address_modbus_dec input").change(function () {
        modbusDecChange($(this));
    });

    registerHexChange($(".register_address_hex input"));

    $(".register_address_hex input").change(function () {
        registerHexChange($(this));
    });

    $(".register_address_dec input").change(function () {
        registerDecChange($(this));
    });
}

function modbusHexChange(item) {
    if (item.length > 0) {
        //pobranie wartości i przekształcenie na wielkie litery
        var hexVal = item.val().toUpperCase();
        if (hexVal === "") {
            //zapisz zero, dodaj zera na początku
            item.val("00");
            $(".address_modbus_dec input").val((0).pad(3));
        } else {
            if (!(/(^[0-9A-F]{1}$)|(^[0-9A-F]{2}$)/i.test(hexVal))) {
                hexVal = "FF";
            }
            //dopełnienie zerami do odpowiedniej długości 
            switch (hexVal.length) {
                case 1:
                    hexVal = "0" + hexVal;
                    break;
                default:
                    break;
            }
            //zapisanie w nowym formacie
            item.val(hexVal);
            //wyliczenie nowej wartości dziesiętnej
            var decVal = parseInt(hexVal, 16);

            //zapisz nową wartość, dodaj zera na początku
            $(".address_modbus_dec input").val(decVal.pad(3));
        }
    }
}

function modbusDecChange(item) {
    if (item.length > 0) {
        //pobranie wartości i dodanie zer na początku
        var decVal = item.val();
        if (decVal === "") {
            item.val((0).pad(3));
            $(".address_modbus_hex input").val("00");
        } else {
            decVal = parseInt(decVal, 10);
            if (decVal > 255) {
                decVal = 255;
            }
            item.val(decVal.pad(3));
            var hexVal = decVal.toString(16).toUpperCase();
            switch (hexVal.length) {
                case 1:
                    hexVal = "0" + hexVal;
                    break;
                default:
                    break;
            }
            $(".address_modbus_hex input").val(hexVal);
        }
    }
}

function registerHexChange(item) {
    if (item.length > 0) {
        //pobranie wartości i przekształcenie na wielkie litery
        var hexVal = item.val().toUpperCase();
        if (hexVal === "") {
            //zapisz zero, dodaj zera na początku
            item.val("0000");
            $(".register_address_dec input").val((0).pad(5));
        } else {
            if (!(/(^[0-9A-F]{1}$)|(^[0-9A-F]{2}$)|(^[0-9A-F]{3}$)|(^[0-9A-F]{4}$)/i.test(hexVal))) {
                hexVal = "FFFF";
            }
            //dopełnienie zerami do odpowiedniej długości 
            switch (hexVal.length) {
                case 1:
                    hexVal = "000" + hexVal;
                    break;
                case 2:
                    hexVal = "00" + hexVal;
                    break;
                case 3:
                    hexVal = "0" + hexVal;
                    break;
                default:
                    break;
            }
            //zapisanie w nowym formacie
            item.val(hexVal);
            //wyliczenie nowej wartości dziesiętnej
            var decVal = parseInt(hexVal, 16);

            //zapisz nową wartość, dodaj zera na początku
            $(".register_address_dec input").val(decVal.pad(5));
        }
    }
}

function registerDecChange(item) {
    if (item.length > 0) {
        //pobranie wartości i dodanie zer na początku
        var decVal = item.val();
        if (decVal === "") {
            item.val((0).pad(5));
            $(".register_address_hex input").val("0000");
        } else {
            decVal = parseInt(decVal, 10);
            if (decVal > 65535) {
                decVal = 65535;
            }
            item.val(decVal.pad(5));
            var hexVal = decVal.toString(16).toUpperCase();
            switch (hexVal.length) {
                case 1:
                    hexVal = "000" + hexVal;
                    break;
                case 2:
                    hexVal = "00" + hexVal;
                    break;
                case 3:
                    hexVal = "0" + hexVal;
                    break;
                default:
                    break;
            }
            $(".register_address_hex input").val(hexVal);
        }
    }
}

function updateLastRead(lastRead, did) {
    lastRead = Date.parse(lastRead) / 1000 / 60;
    var now = new Date();
    now = Date.parse(now) / 1000 / 60;
    var elapseUTC = now - lastRead;
    var r = parseInt("33", 16);
    var g = parseInt("7A", 16);
    var b = parseInt("B7", 16);

    if (elapseUTC > 1)
    {
        r = r + elapseUTC * 13;
        if (r > 256) {
            r = 255;
        }
        g = g - elapseUTC * 8;
        if (g < 10) {
            g = 0;
        }
        b = b - elapseUTC * 12;
        if (b < 10) {
            b = 0;
        }

        r = r.toString(16);
        if (r.length < 2) {
            r = "0" + r;
        }
        g = g.toString(16);
        if (g.length < 2) {
            g = "0" + g;
        }
        b = b.toString(16);
        if (b.length < 2) {
            b = "0" + b;
        }

        $("span#" + did + ".label-last-read").css("background-color", "#" + r + g + b);
    } else
    {
        $("span#" + did + ".label-last-read").css("background-color", "#337AB7");
    }

    if (elapseUTC > 15) {
        $("ul.device-level div#" + did + " i.fa-gear.fa-green").removeClass("fa-gear fa-spin fa-green").addClass("fa-exclamation fa-red");
    } else {
        $("ul.device-level div#" + did + " i.fa-exclamation").removeClass("fa-exclamation fa-red").addClass("fa-gear fa-spin fa-green");
    }


}

function refreshPage() {


    var cid = $(".communicationType-level div.active").attr("id");
    var did = $(".device-level div.active").attr("id");
    var rid = $(".register-level div div.active").attr("id");
    if (cid === undefined) {
        cid = 0;
    }
    if (did === undefined) {
        did = 0;
    }
    if (rid === undefined) {
        rid = 0;
    }
    var url = Routing.generate('bms_configuration_refresh_page', {comm_id: cid, device_id: did, register_id: rid});
    $.ajax({
        type: "POST",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        url: url,
        cache: false,
        success: function (ret) {
            var times = ret["times_of_update"];
            $.each(times, function (key, value) {
                var time = new Date(value * 1000);
                if (value !== 0) {
                    $("span#" + key + ".label-last-read span").text($.formatDateTime('yy-mm-dd hh:ii', time));
                } else {
                    $("span#" + key + ".label-last-read span").text("-");
                }
                updateLastRead(time, key);
            });
            $("i.fa-refresh[id]").each(function () {
                var id = $(this).attr("id");
                if (ret[id] === null) {
                    ret[id] = "";
                }
                $(this).parent().parent().children("td.fixed_value").text(ret[id]);
            });
            $("i.fa-pulse").remove();

        },
        error: function (status) {
            console.log(status);
        }
    });
    $(".main-row").addClass("text-center").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
