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



$(function() {
    Contabilidad.validacionGeneral('form-general');

    $(".select2").select2({
        language: "es",
        theme: "bootstrap4"
    });

    $("#inputcuentabancariacre").select2({
        language: "es",
        theme: "bootstrap4"
    });

    $("#tipodeterceros").select2({
        language: "es",
        theme: "bootstrap4"
    });

    const codigocuenta = $("#codigocuenta").val();
    const codigoterminal = $("#codigoterminal").val();


    if (codigocuenta != "") {
        const path = $("#empPath").val() + "/" + codigocuenta + "/Auth";
        TerminalesCuentaBancariaAuth(path, codigoterminal);
    }
});

$("#inputcuentabancariacre").on("change", function (event) {
    const path = $("#empPath").val() + "/" + event.target.value + "/Auth";
    TerminalesCuentaBancariaAuth(path, codigoterminal);
});


function TerminalesCuentaBancariaAuth(path, id_terminal, terminales) {
    var selected = "";
    $.get(path, function (response) {
        var i = 0;
        $("#inputterminal").empty();
        for (const i in response) {
            if (response[i].ter_id == terminales) {
                selected = " selected";
            } else {
                selected = "";
            }
            $("#inputterminal").append(
                "<option value='" + response[i].ter_id + "' " + selected + ">" + response[i].ter_nombre + "</option>"
            );
        }
    });
}
