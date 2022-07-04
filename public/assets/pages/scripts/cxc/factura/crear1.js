
$("input[data-bootstrap-switch]").each(function() {
    $(this).bootstrapSwitch("state", $(this).prop("checked"));
});

$("#ven_empresa").on("change", function (event) {
    $("#ven_empresa").next(".select2-container").show();
    getCertificador();
});

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
        $("#ven_terminal").empty();
        $("#nom_empresa").empty();
        var val = $("#empresa").val();
        var obj = $("#lst_empresa").find("option[value='" + val + "']");
        if (obj != null && obj.length > 0) {
            $("#ven_empresa").val(obj.data("id"));
            $("#nom_empresa").append(obj.data("nombre"));
            $("#ven_empresa").trigger("change");
        } else {
            $("#empresa").val("");
            var self = this;
            setTimeout(function () {
                self.focus();
            }, 10);
        }
    });



    $("#ven_fecha").daterangepicker({
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

$("#ven_empresa").on("change", function (event) {
    const emp = $("#empPath").val() + "/" + event.target.value + "/Auth";
    const terCod = $("#terCod").val();
    const cta = $("#prodPath").val();
    llenarCtaCon(cta);
    llenarTer(emp, terCod);


});

$("#ven_terminal").on("change", function (event) {
    $("#terCod").val($(this).val());
    const terCod = $("#ctaCod").val();
    const cta = $("#prodPath").val();
    if ($(this).val() != null) {
        llenarCtaCon(cta, terCod);

    }

});



function PasarValor()
    {
    document.getElementById("bienoservicio").value = document.getElementById("BIENOSERVICIO").value;

    }


function llenarTer(empresa, terminal) {
var selected = "";
$.get(empresa, function (response) {
    var i = 0;
    $("#ven_terminal").empty();
    for (const i in response) {
        if (response[i].ter_id == terminal) {
            selected = "selected";
        } else {
            selected = "";
        }
        $("#ven_terminal").append(
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
    $("#ven_terminal").val(null).trigger("change");
});
}


function getCertificador(){
    emp = $("#ven_empresa").val();
    Tpath = 'certificador/' + emp;
    $.get(Tpath, function (response)
    {
        switch(response){
            case 'INFILE':
                $('label[for="ven_iiud"]').hide();
                $("#ven_iiud").hide();
                $('label[for="ven_fechaCert"]').hide();
                $("#ven_fechaCert").hide();
                $('div[for="ven_fechaCert"]').hide();
                $("#ven_fechaCert").hide();
                $('label[for="ven_serie"]').hide();
                $("#ven_serie").val("");
                $("#ven_serie").hide();
                $('label[for="ven_numDoc"]').hide();
                $("#ven_numDoc").val("");
                $("#ven_numDoc").hide();
                $('label[for="ven_enlacefactura"]').hide();
                $("#ven_enlacefactura").val("");
                $("#ven_enlacefactura").hide();

                break;
            case 'SAT':
                $('label[for="ven_iiud"]').show();
                $("#ven_iiud").show();
                $('label[for="ven_fechaCert"]').show();
                $("#ven_fechaCert").show();
                $('div[for="ven_fechaCert"]').show();
                $("#ven_fechaCert").show();
                $('label[for="ven_serie"]').show();
                $("#ven_serie").show();
                $('label[for="ven_numDoc"]').show();
                $("#ven_numDoc").show();
                $('label[for="ven_enlacefactura"]').show();
                $("#ven_enlacefactura").show();
                $('label[for="ven_tipoCambio1"]').hide();
                $("#ven_tipoCambio1").hide();
                $('label[for="ven_moneda1"]').hide();
                $('#tipofactura').hide();


                break;
            case 'PAPEL':
                $('label[for="ven_iiud"]').hide();
                $("#ven_iiud").hide();
                $('label[for="ven_fechaCert"]').hide();
                $("#ven_fechaCert").hide();
                $('div[for="ven_fechaCert"]').hide();
                $("#ven_fechaCert").hide();
                $('#tipofactura').hide();
                break;
        }
    });
}

function llenarCtaCon(path, cuenta) {

emp = $("#ven_empresa").val();
ter = $("#ven_terminal").val();

path = path + "/" + emp + "/"+ ter + "/";
$("#detv_producto").empty();
$.get(path, function (response) {
    for (const i in response) {
            $("#detv_producto").append($(
            "<option>",{
                value: response[i].prod_id,
                text: response[i].prod_codigo +
                " " +
               response[i].prod_desc_lg,
                data: {
                    BIENOSERVICIO: response[i].prod_tipo,
                  }}));
    }
    $("#BIENOSERVICIO").val($("#detv_producto option:selected").data('BIENOSERVICIO'));
  });
}



