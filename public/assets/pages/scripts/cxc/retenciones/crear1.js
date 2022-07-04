$(function () {
    Contabilidad.validacionGeneral("form-general");

    $(".select2").select2({
        language: "es",
        theme: "bootstrap4",
    });
    $("input[type='number']").inputSpinner();

    const empCod = $("#empCod").val();
    const terCod = $("#terCod").val();
    const cliCod = $("#cliCod").val();
    if (empCod != "") {
        const emp = $("#empPath").val() + "/" + empCod + "/Auth";
        const cta = $("#notPath").val();
        const cliCod = $("#cliCod").val();
        llenarTer(emp, terCod);
        llenarCtaCon(cta, terCod, cliCod);
    }


    $("#empresa").on("blur", function (event) {
        $("#docv_terminal").empty();
        $("#nom_empresa").empty();
        var val = $("#empresa").val();
        var obj = $("#lst_empresa").find("option[value='" + val + "']");
        if (obj != null && obj.length > 0) {
            $("#docv_empresa").val(obj.data("id"));
            $("#nom_empresa").append(obj.data("nombre"));
            $("#docv_empresa").trigger("change");
        } else {
            $("#empresa").val("");
            var self = this;
            setTimeout(function () {
                self.focus();
            }, 10);
        }
    });

    $("#docv_fecha").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        showWeekNumbers: true,
        autoApply: true,
        locale: {
            format: "DD/MM/YYYY",
            separator: " / ",
            applyLabel: "OK",
            cancelLabel: "Cancelar",
            fromLabel: "Desde",
            toLabel: "Hasta",
            customRangeLabel: "Personalizado",
            weekLabel: "S",
            daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            monthNames: [
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
                "Diciembre",
            ],
            firstDay: 1,
        },
        linkedCalendars: false,
        showCustomRangeLabel: false,
        startDate: $("#hemp_inicio").val(),
        minDate: "01/01/1970",
        drops: "up",
    });
});

$("#docv_empresa").on("change", function (event) {
    const emp = $("#empPath").val() + "/" + event.target.value + "/Auth";
    const terCod = $("#terCod").val();

    llenarTer(emp, terCod);
});

$("#docv_terminal").on("change", function (event) {
    $("#terCod").val($(this).val());
    const terCod = $("#ctaCod").val();
    const cta = $("#notPath").val();
    const cliCod = $("#cliCod").val();
    llenarCtaCon(cta, terCod, cliCod);
});

$("#docv_persona").on("change", function (event) {
    $("#cliCod").val($(this).val());
    const terCod = $("#ctaCod").val();
    const cta = $("#notPath").val();
    const cliCod = $("#cliCod").val();
    llenarCtaCon(cta, terCod, cliCod);
});



$("#factura").on("change", function (event) {
    $("#SERIE").val($("#factura option:selected").data("SERIE"));
});

function PasarValor() {
    document.getElementById("detr_factura").value =
        document.getElementById("SERIE").value;
}

function PasarValor1() {
    document.getElementById("docv_monto").value =
        document.getElementById("detr_retencion").value;
}



function llenarTer(empresa, terminal) {
    var selected = "";
    $.get(empresa, function (response) {
        var i = 0;
        $("#docv_terminal").empty();
        for (const i in response) {
            if (response[i].ter_id == terminal) {
                selected = "selected";
            } else {
                selected = "";
            }
            $("#docv_terminal").append(
                "<option value='" +
                    response[i].ter_id +
                    "' " +
                    selected +
                    ">" +
                    response[i].ter_nombre +
                    "</option>"
            );
        }
        if (terminal.length == 0)
        $("#docv_terminal").val(null).trigger("change");
    });
}

function llenarCtaCon(path, cuenta) {
    emp = $("#docv_empresa").val();
    ter= $("#docv_terminal").val();
    cli= $("#docv_persona").val();

    path = path + "/" + emp + "/"+ ter + "/"+ cli + "/";
    $("#factura").empty();
    $.get(path, function (response) {
        for (const i in response) {
            $("#factura").append(
                $("<option>", {
                    value: response[i].ven_id,
                    text:
                        response[i].ven_serie +
                        " - " +
                        response[i].ven_numDoc +
                        " - " +
                        response[i].ven_total,

                    data: {
                        SERIE: response[i].ven_id,
                    },
                })
            );
        }

        $("#SERIE").val($("#factura option:selected").data("SERIE"));
    });
}
