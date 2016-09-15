/* global parseInt, NULL */
Number.prototype.pad = function (size) {
    var s = String(this);
    while (s.length < (size || 2)) {
        s = "0" + s;
    }
    return s;
};

$(document).ready(function () {
    var url;
    refreshPage();
    interval = setInterval(function () {
        refreshPage();
    }, 1000 * 10);

    setInterval(counter, 1000);
    tableEvents();

    setActiveLevel($("div.active"));

    $(".target-level li div").click(function () {
        url = Routing.generate('bms_configuration_index');
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

        url = Routing.generate('bms_configuration_communication_type', {comm_id: cid});
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

        url = Routing.generate('bms_configuration_device', {comm_id: cid, device_id: did});
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

    $("button#synchronizeDatabase").click(function () {
        var internetStatus = $("span#noInternetConnection img").attr("src").split("/");

        if (internetStatus[internetStatus.length - 1].split(".")[0] === "ethernetOn") {
            $(".main-row").addClass("text-center").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
            $.ajax({
                type: "POST",
                datatype: "application/json",
                async: false,
                url: Routing.generate('bms_configuration_synchronize_database'),
                success: function (ret) {
                    $(".main-row").children(".fa-spinner, div#loading").remove();
                    if (ret['sync'] === 0) {
                        $("button#synchronizeDatabase").remove();
                    }
                }
            });
        } else {
            alert("Urządzenie nie ma dostępu do internetu");
        }
    });
});

function setActiveLevel(item) {
    var ctl, dl, cid, did, url;
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

        url = Routing.generate('bms_configuration_device', {comm_id: cid, device_id: did});
        ajaxAppend(url);
    } else if (ctl.length > 0) {
        $(".main-row").children().remove();
        ctl.children().children("div.active").next("ul.device-level").show();
        ctl.children().children("div.active").children("i.fa").addClass("fa-angle-down");
        cid = ctl.children().children("div.active").attr("id");
        url = Routing.generate('bms_configuration_communication_type', {comm_id: cid});
        ajaxAppend(url);
    } else {
        $(".target-level").next("ul").children().children("ul").hide().prev("div").removeClass("active");

    }
}

function ajaxAppend(url) {
    $.ajaxq.abort("Main");
    $.ajaxq("Main", {
        type: "POST",
        dataType: "json",
        url: url,
        cache: false,
        success: function (ret) {
            var mainRow = $(".main-row");
            mainRow.children().remove();
            mainRow.hide().append(ret["ret"]).fadeIn("slow");
            refreshPage();
            formEvents();
            tableEvents();
            updateAddressFormat();
        }
    });
    $(".main-row").addClass("text-center").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();

}

function formEvents() {
    var inputRegisterSize = $("select#bmsconfigurationbundle_register_register_size");
    var inputModificatorRead = $("input#bmsconfigurationbundle_register_modificator_read");
    var inputModificatorWrite = $("input#bmsconfigurationbundle_register_modificator_write");

    $("div.bit_registers").each(function () {
        var name = $(this).children("input[name*='name']").val();
        $(this).find("span.bitName").empty().append(name);
        var value = $(this).children("input[name*='bitValue']").val();
        $(this).find("span.bitValue").empty().append(value);
    });
    $(".btn-edit").click(function () {
        $(this).hide().parent().children().hide();
        $(this).parent().find(".btn-save, .btn-cancel").show();
        $(this).parent().parent().parent().find("input[type=text]:not(div.permanentDisabled input),\n\
                                                    input[type=number],\n\
                                                    input[type=checkbox],\n\
                                                    select,\n\
                                                    textarea").removeAttr('disabled');

    });
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
    $(".btn-cancel").click(function () {

        $(this).hide().parent().children().hide();
        $(this).parent().find(".btn-edit, .btn-delete").show();
        $(this).parent().parent().parent().find("input[type=text],\n\
                                                input[type=number],\n\
                                                input[type=checkbox],\n\
                                                select,\n\
                                                textarea").attr('disabled', true);
    });
    $(".btn-delete").click(function () {
        if (confirm("Na pewno usunąć urządzenie razem z wszystkimi rejestrami do niego przypisanymi?")) {

        } else {
            return false;
        }
    });
    $(".enabled input, .enabled select, .enabled textarea").removeAttr('disabled');
    if ($("div.new-item").hasClass("active")) {
        inputRegisterSize.val(16);
        inputModificatorRead.val(1);
        inputModificatorWrite.val(1);
        $("textarea#bmsconfigurationbundle_register_description2").val("Dodatkowe informacje");
        $("input#bmsconfigurationbundle_register_active").attr("checked", true);
        $("select#bmsconfigurationbundle_register_read_function").val("03");
        $("select#bmsconfigurationbundle_register_write_function").val("06");
    }
    $(".main-row span.hide-mainrow-label").click(function () {
        $(this).parent().parent().next("div.well").toggle();
        $(this).children("i.fa").toggleClass('fa-angle-down');
    });
    inputRegisterSize.change(function () {
        if ($("input#bmsconfigurationbundle_register_bit_register").is(':checked')) {
            setBits();
        }
    });

    $("input#bmsconfigurationbundle_register_bit_register").unbind("change").change(function () {
        if ($(this).is(':checked')) {
            $('div.bits-label').removeClass("hidden-item");
            $('div.bits').empty().append("<div class='col-md-12'></div>");
            setBits();
        } else {
            $('div.bits').empty();
            $('div.bits-label').addClass("hidden-item");
        }
    });

    $("input#read_mod_val").val(inputModificatorRead.val());
    $("input#write_mod_val").val(inputModificatorWrite.val());
    $("input#write_limit_min").val($("input#bmsconfigurationbundle_register_write_limit_min").val());
    $("input#write_limit_max").val($("input#bmsconfigurationbundle_register_write_limit_max").val());
    $("input#step").val($("input#bmsconfigurationbundle_register_write_step").val());

    $("select#read_mod_operator, input#read_mod_val").change(function () {
        var oper = $("select#read_mod_operator").val();
        var mod = $("input#read_mod_val").val();
        if (oper === "*") {
            inputModificatorRead.val(mod);
        } else if (oper === "/") {
            mod = 1 / mod;
            inputModificatorRead.val(mod);
        }
    });

    $("select#write_mod_operator, input#write_mod_val").change(function () {
        var oper = $("select#write_mod_operator").val();
        var mod = $("input#write_mod_val").val();
        if (oper === "*") {
            inputModificatorWrite.val(mod);
        } else if (oper === "/") {
            mod = 1 / mod;
            inputModificatorWrite.val(mod);
        }
    });

    function pad(n, width, z) {
        z = z || '0';
        n = n + '';
        return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
    }

    function setBits() {
        var bit_container = $('div.bits div');
        bit_container.empty();
        var registerValue = $("span.registerValue").text();
        var registerSize = $('select#bmsconfigurationbundle_register_register_size').val();
        var registerName = $("input#bmsconfigurationbundle_register_name").val();
        for (var i = 0; i < registerSize; i++) {
            bit_container.append("<div class='row bit_registers'>\n\
                                        <div class='col-md-2 text-center'>\n\
                                            <span class='bitName'>" + registerName + "_B" + i + "</span>\n\
                                        </div>\n\
                                        <div class='col-md-5'>\n\
                                            <div class='form-group'>\n\
                                                <input id='bmsconfigurationbundle_register_bit_registers_" + i + "_description' class='form-control'\n\
                                                        name='bmsconfigurationbundle_register[bit_registers][" + i + "][description]' type='text'>\n\
                                                </input>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class='col-md-1 text-center'>\n\
                                            <span class='bitValue'>" + pad((registerValue >>> 0).toString(2), registerSize, 0)[registerSize - 1 - i] + "</span>\n\
                                        </div>\n\
                                        <input id='bmsconfigurationbundle_register_bit_registers_" + i + "_bitPosition'\n\
                                                name='bmsconfigurationbundle_register[bit_registers][" + i + "][bitPosition]'\n\
                                                value='" + i + "' type='hidden'>\n\
                                        </input>\n\
                                        <input id='bmsconfigurationbundle_register_bit_registers_" + i + "_register'\n\
                                                name='bmsconfigurationbundle_register[bit_registers][" + i + "][register]'\n\
                                                value='" + parseInt($("ul.nav.register-level li div div.active").attr("id")) + "' type='hidden'>\n\
                                        </input>\n\
                                        <input id='bmsconfigurationbundle_register_bit_registers_" + i + "_name'\n\
                                                name='bmsconfigurationbundle_register[bit_registers][" + i + "][name]' required='required'\n\
                                                value='" + registerName + "_B" + i + "' type='hidden'>\n\
                                        </input>\n\
                                        <input id='bmsconfigurationbundle_register_bit_registers_" + i + "_bitValue'\n\
                                                name='bmsconfigurationbundle_register[bit_registers][" + i + "][bitValue]'\n\
                                                value=" + pad((registerValue >>> 0).toString(2), registerSize, 0)[registerSize - 1 - i] + " type='hidden'>\n\
                                        </input>\n\
                                      </div>");
        }
    }
}

function tableEvents() {
    //przycisk zaznaczający wszystkie checkboxy
    $(".checkAll:button").click(function () {
        var stateCheckbox = $("td input[type='checkbox']");
        var state = stateCheckbox.prop("checked");
        stateCheckbox.prop("checked", !state);
        if (state) {
            $(this).val("Zaznacz wszystkie");
        } else {
            $(this).val("Odznacz wszystkie");
        }
    });
    //przekierowanie do edycji urządzenia z tabeli
    $("tr td.manage i.fa-edit.fa-device").click(function () {

        var cid = $(".communicationType-level div.active").attr("id");
        var did = $(this).attr("id");

        $(".main-row").children().remove();
        $(".device-level li div#" + did + ".row").toggleClass("active").next("ul.register-level").show().children("i.fa:last-child").addClass("fa-angle-down");
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

    $("tr td.manage i.fa-pencil").click(function () {
        var rid = $(this).attr("id");
        $("#write-form").dialog({
            autoOpen: false,
            modal: true,
            buttons: [{
                text: "Zapisz do urządzenia",
                click: function () {
                    var data = {
                        value: $("div#write-form form input#value").val(),
                        register_id: rid
                    };
                    $.ajax({
                        type: "POST",
                        datatype: "application/json",
                        url: Routing.generate('write_register'),
                        data: data,
                        success: function () {
                            $(".main-row").children(".fa-spinner").remove();
                            refreshPage();
                        }
                    });
                    $(".main-row").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
                    $(this).dialog('close');
                }
            },
                {
                    text: "Anuluj",
                    click: function () {
                        $(this).dialog('close');
                    }
                }],
            open: function () {
            },
            close: function () {
                $(this).dialog('close');
            }
        }).dialog("open");
    });
}

function updateAddressFormat() {
    var decInput = $(".address_modbus_dec input");
    var hexInput = $(".register_address_hex input");
    modbusDecChange(decInput);

    $(".address_modbus_hex input").change(function () {
        modbusHexChange($(this))
    });

    decInput.change(function () {
        modbusDecChange($(this));
    });

    registerHexChange(hexInput);

    hexInput.change(function () {
        registerHexChange($(this));
    });

    $(".register_address_dec input").change(function () {
        registerDecChange($(this));
    });
}

function modbusHexChange(item) {
    if (item.length > 0) {
        var decInput = $(".address_modbus_dec input");
        var hexVal = item.val().toUpperCase();
        if (hexVal === "") {
            item.val("00");
            decInput.val((0).pad(3));
        } else {
            if (!(/(^[0-9A-F]{1}$)|(^[0-9A-F]{2}$)/i.test(hexVal))) {
                hexVal = "FF";
            }
            switch (hexVal.length) {
                case 1:
                    hexVal = "0" + hexVal;
                    break;
                default:
                    break;
            }
            item.val(hexVal);
            var decVal = parseInt(hexVal, 16);
            decInput.val(decVal.pad(3));
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
        var hexVal = item.val().toUpperCase();
        if (hexVal === "") {
            item.val("0000");
            $(".register_address_dec input").val((0).pad(5));
        } else {
            if (!(/(^[0-9A-F]{1}$)|(^[0-9A-F]{2}$)|(^[0-9A-F]{3}$)|(^[0-9A-F]{4}$)/i.test(hexVal))) {
                hexVal = "FFFF";
            }
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
            item.val(hexVal);
            var decVal = parseInt(hexVal, 16);
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
    var nowTime = new Date();
    var now = Date.parse(nowTime) / 1000 / 60;
    var elapseUTC = now - lastRead;
    var r = parseInt("33", 16);
    var g = parseInt("7A", 16);
    var b = parseInt("B7", 16);

    if (elapseUTC > 1) {
        r = r + elapseUTC * 13;
        g = g - elapseUTC * 8;
        b = b - elapseUTC * 12;
        r > 256 ? r = 255 : r;
        g < 10 ? g = 0 : g;
        b < 10 ? b = 0 : b;

        r = r.toString(16);
        g = g.toString(16);
        b = b.toString(16);
        r.length < 2 ? r = "0" + r : r;
        g.length < 2 ? g = "0" + g : g;
        b.length < 2 ? b = "0" + b : b;
        $("span#" + did + ".label-last-read").css("background-color", "#" + r + g + b);
    } else {
        $("span#" + did + ".label-last-read").css("background-color", "#337AB7");
    }
}

function refreshPage() {
    var cid = $(".communicationType-level div.active").attr("id");
    var did = $(".device-level div.active").attr("id");
    var rid = $(".register-level div div.active").attr("id");
    cid === undefined ? cid = 0 : cid;
    did === undefined ? did = 0 : did;
    rid === undefined ? rid = 0 : rid;
    $.ajax({
        type: "POST",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        url: Routing.generate('bms_configuration_refresh_page', {comm_id: cid, device_id: did, register_id: rid}),
        cache: false,
        success: function (ret) {
            $(".fa-spinner").remove();
            $.each(ret["times_of_update"], function () {
                var time = new Date(this.time);
                var timeLabel = $("span#" + this.id + ".label-last-read span");
                timeLabel.text($.formatDateTime('yy-mm-dd hh:ii', time));
                updateLastRead(time, this.id);

            });
            $("i.fa-refresh[id]").each(function () {
                var id = $(this).attr("id");
                if (ret[id] === null) {
                    ret[id] = "";
                }
                $(this).parent().parent().children("td.fixed_value").text(ret[id]);
            });
            $.each(ret['devicesStatus'], function () {
                if (this.status > 0) {
                    $("ul.device-level div#" + this.device_id + " i.fa-gear.fa-green").removeClass("fa-gear fa-spin fa-green").addClass("fa-exclamation fa-red");
                } else if (this.status === 0) {
                    $("ul.device-level div#" + this.device_id + " i.fa-exclamation").removeClass("fa-exclamation fa-red").addClass("fa-gear fa-spin fa-green");
                }

            });
            setState(ret['state'], ret['devicesStatus']);
        }
    });
    countToRefresh = 0;
    $(".main-row").addClass("text-center").append("<i class='fa fa-spinner fa-pulse fa-4x'></i>").show();
}
