
$(function () {
    Contabilidad.validacionGeneral("form-general");

    $(".select2").select2({
        language: "es",
        theme: "bootstrap4",
    });
    $("input[type='number']").inputSpinner();


    const empCod = $("#empCod").val();
    const terCod = $("#terCod").val();
    if (empCod != "") {
        const emp = $("#empPath").val() + "/" + empCod + "/Auth";
        const cta = $("#polPath").val();
        llenarCtaCon(cta, terCod);
    }

    $("#pol_fecha").daterangepicker({
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

$("#pol_empresa").on("blur", function (event) {
    $("#nom_empresa").empty();
    var val = $("#pol_empresa").val();
    var obj = $("#lst_empresa").find("option[value='" + val + "']");
    if (obj != null && obj.length > 0) {
        $("#com_empresa").val(obj.data("id"));
        $("#nom_empresa").append(obj.data("nombre"));
        $("#com_empresa").trigger("change");
    } else {
        $("#pol_empresa").val("");
        var self = this;
        setTimeout(function () {
            self.focus();
        }, 10);
    }
});

    $("#pol_empresa").on("change", function (event) {
        const cta = $("#polPath").val();
        llenarCtaCon(cta);
    });

    $("#ven_terminal").on("change", function (event) {
        $("#terCod").val($(this).val());
        const terCod = $("#ctaCod").val();

    });

function llenarCtaCon(path, cuenta) {

    emp = $("#pol_empresa").val();

    path = path + "/" + emp + "/";
    $("#dpol_ctaContable").empty();
    $.get(path, function (response) {
        for (const i in response) {
            if (response[i].cta_id == cuenta) {
                selected = "selected";
            } else {
                selected = "";
            }
            $("#dpol_ctaContable").append(
                "<option value='" +
                    response[i].cta_id +
                    "' " +
                    selected +
                    ">" +
                    response[i].cta_codigo +
                    " - " +
                    response[i].cta_descripcion +
                    "</option>"
            );
        }
    });
}













