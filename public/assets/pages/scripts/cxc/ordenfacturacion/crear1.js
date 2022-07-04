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
        const cta = $("#prodPath").val();
        llenarTer(emp, terCod);
        llenarCtaCon(cta, terCod);
    }

    $("#empresa").on("blur", function (event) {
        $("#ordf_terminal").empty();
        $("#nom_empresa").empty();
        var val = $("#empresa").val();
        var obj = $("#lst_empresa").find("option[value='" + val + "']");
        if (obj != null && obj.length > 0) {
            $("#ordf_empresa").val(obj.data("id"));
            $("#nom_empresa").append(obj.data("nombre"));
            $("#ordf_empresa").trigger("change");
        } else {
            $("#empresa").val("");
            var self = this;
            setTimeout(function () {
                self.focus();
            }, 10);
        }
    });


    $("#ordf_eta").daterangepicker({
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


    $("#ven_fechaCert").daterangepicker({
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

$("#ordf_empresa").on("change", function (event) {
    const emp = $("#empPath").val() + "/" + event.target.value + "/Auth";
    const terCod = $("#terCod").val();
    const cta = $("#prodPath").val();
    llenarCtaCon(cta);
    llenarTer(emp, terCod);

});

$("#ordf_terminal").on("change", function (event) {
    $("#terCod").val($(this).val());
    const terCod = $("#ctaCod").val();
    const cta = $("#prodPath").val();
    if ($(this).val() != null) {
        llenarCtaCon(cta, terCod);

    }

});

function llenarTer(empresa, terminal) {
    var selected = "";
    $.get(empresa, function (response) {
        var i = 0;
        $("#ordf_terminal").empty();
        for (const i in response) {
            if (response[i].ter_id == terminal) {
                selected = "selected";
            } else {
                selected = "";
            }
            $("#ordf_terminal").append(
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
        $("#ordf_terminal").val(null).trigger("change");
    });
}



function llenarCtaCon(path, cuenta) {

    emp = $("#ordf_empresa").val();
    ter = $("#ordf_terminal").val();

    path = path + "/" + emp + "/"+ ter + "/";
    $("#dof_producto").empty();
    $.get(path, function (response) {
        for (const i in response) {
            if (response[i].prod_id == cuenta) {
                selected = "selected";
            } else {
                selected = "";
            }
            $("#dof_producto").append(
                "<option value='" +
                    response[i].prod_id +
                    "' " +
                    selected +
                    ">" +
                    response[i].prod_codigo +
                    "  " +
                    selected +
                    " " +
                    response[i].prod_desc_lg +



                    "</option>"
            );
        }
    });
}


$("#nomCliented").val($("#ordf_cliente option:selected").data('nombre'));



