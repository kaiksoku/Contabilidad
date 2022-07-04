$(function () {
    Contabilidad.validacionGeneral('form-general');

    $(".select2").select2({
        language: "es",
        theme: "bootstrap4"
    });

    $('#monedacambio').hide()

    $("#inputcuentabancariadeb").change(function() {
        let prueba=$('#inputcuentabancariadeb :selected').text().split(' - ').pop()
        if(prueba==='DOLAR'){
            $('#monedacambio').show()
        }else{
            $('#monedacambio').hide()
        }
    });

    const codigocuentadeb = $("#codigocuentadeb").val();
    const codigoterminaldeb = $("#codigoterminaldeb").val();
    const codigocuentacre = $("#codigocuenta").val();
    const codigoterminalcre = $("#codigoterminal").val();
    const codigoempresados = $("#codigoempresados").val();


    if (codigocuentadeb != "") {
        const pathCuenta = $("#ctaPath").val() + "/" + $("#codigoempdeb").val()
        llenarCtaBan(pathCuenta, 'inputcuentabancariadeb', codigocuentadeb)
    }
    if (codigocuentacre != "") {
        const pathCuenta = $("#ctaPath").val() + "/" + $("#codigoempcre").val()
        llenarCtaBan(pathCuenta, 'inputcuentabancariacre',codigocuentacre)
    }
    if (codigoterminaldeb != "") {
        const path = $("#terPath").val() + "/" +$("#codigoempdeb").val()+ "/Auth";
        llenarTer(path, 'inputterminaldeb',codigoterminaldeb);
    }
    if (codigoempresados != "") {
        const pathEmpresa = $("#empPath").val() + "/" +$("#codigoempdeb").val()+ "/Auth";
        llenarEmpresa(pathEmpresa, 'id_empresa', codigoempresados);

    }
    if (codigoterminalcre != "") {
        const path = $("#terPath").val() + "/" +$("#codigoempcre").val()+ "/Auth";
        llenarTer(path, 'inputterminalcre',codigoterminaldeb);
    }
});

$("#inputempresadeb").on("change", function (event) {
    if (!!event.target.value) {
        const pathCuenta = $("#ctaPath").val() + "/" + event.target.value
        const pathEmpresa = $("#empPath").val() + "/" + event.target.value
        llenarCtaBan(pathCuenta, 'inputcuentabancariadeb', $("#codigocuentadeb").val());
        llenarCtaBan(pathCuenta, 'inputcuentabancariacre', $("#codigocuentacre").val());
        $('#inputempresados option').attr("disabled", false);
        $('#inputempresados option[value="'+event.target.value+'"]').attr("disabled", true);
    }

});
$("#inputempresados").on("change", function (event) {
    if (!!event.target.value) {
        $('#inputempresadeb option').attr("disabled", false);
        $('#inputempresadeb option[value="'+event.target.value+'"]').attr("disabled", true);

    }

});
$("#inputempresacre").on("change", function (event) {
    if (!!event.target.value) {
        const path = $("#terPath").val() + "/" + event.target.value + "/Auth";
        llenarTer(path, 'inputterminalcre', $("#codigoterminalcre").val());
        const pathCuenta = $("#ctaPath").val() + "/" + event.target.value
        llenarCtaBan(pathCuenta, 'inputcuentabancariacre', $("#codigocuentacre").val())
    }

});

function llenarCtaBan(path, id_cuenta, codigocuentadeb) {
    let selected = ''
    let inputCuenta = $("#" + id_cuenta);
    inputCuenta.empty();
    $.get(path, function (response) {
        inputCuenta.append("<option value=''>Seleccione una Cuenta</option>");
        for (const i in response) {
            if (response[i].ctab_id == codigocuentadeb) {
                selected = "selected";
            } else {
                selected = "";
            }
            inputCuenta.append(
                "<option value='" + response[i].ctab_id + "' " + selected + ">"
                 + response[i].ctab_numero + " - " + response[i].emp_siglas + " - " + response[i].ban_siglas + " - " + response[i].mon_nombre +"</option>"
            );
        }
    });
}

function llenarEmpresa(pathEmpresa, id_empresa, codigoempresados) {
    let selected = ''
    let inputEmpresa = $("#" + id_empresa);
    inputEmpresa.empty();
    $.get(path, function (response) {
        inputEmpresa.append("<option value=''>Seleccione una Empresa</option>");
        for (const i in response) {
            if (response[i].emp_id == codigoempresados) {
                selected = "selected";
            } else {
                selected = "";
            }
            inputEmpresa.append(
                "<option value='" + response[i].emp_id + "' " + selected + ">"
                + response[i].emp_siglas + "</option>"
            );
        }
    });
}

function llenarTer(path, id_terminal, terminal) {
    var selected = "";
    let inputCuenta = $("#" + id_terminal);
    inputCuenta.empty();

    $.get(path, function (response) {
        inputCuenta.append("<option value=''>Seleccione una terminal</option>");
        for (const i in response) {
            if (response[i].ter_id == terminal) {
                selected = " selected";
            } else {
                selected = "";
            }
            inputCuenta.append(
                "<option value='" + response[i].ter_id + "' " + selected + ">" + response[i].ter_nombre + "</option>"
            );
        }
    });
}

$('#inputfecha').daterangepicker({
    "singleDatePicker": true,
    "showDropdowns": true,
    "showWeekNumbers": true,
    "autoApply": true,
    "locale": {
        "format": "DD/MM/YYYY",
        "separator": " / ",
        "applyLabel": "OK",
        "cancelLabel": "Cancelar",
        "fromLabel": "Desde",
        "toLabel": "Hasta",
        "customRangeLabel": "Personalizado",
        "weekLabel": "S",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
        "firstDay": 1
    },
    "linkedCalendars": false,
    "showCustomRangeLabel": false,
    "drops": "up"
});

$("input[data-bootstrap-switch]").each(function() {
    $(this).bootstrapSwitch("state", $(this).prop("checked"));
});

$("#inputcuentabancariadeb").on("change", function (event) {
    if (!!event.target.value) {
        $('#inputcuentabancariacre option').attr("disabled", false);
        $('#inputcuentabancariacre option[value="'+event.target.value+'"]').attr("disabled", true);
    }
});

$("#inputcuentabancariacre").on("change", function (event) {
    if (!!event.target.value) {
        $('#inputcuentabancariadeb option').attr("disabled", false);
        $('#inputcuentabancariadeb option[value="'+event.target.value+'"]').attr("disabled", true);
    }
});


