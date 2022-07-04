$("input[data-bootstrap-switch]").each(function() {
    $(this).bootstrapSwitch("state", $(this).prop("checked"));
});

$(function () {
    Contabilidad.validacionGeneral('form-general');
    $(".select2").select2({
        language: "es",
        theme: "bootstrap4",
    });

    $('select[data-select2-id]').on('select2:opening', function (e) {
        if( $(this).attr('readonly') == 'readonly') {
            console.log( 'can not open : readonly' );
            e.preventDefault();
            $(this).select2('close');
            return false;
        }
    });

    const empCod = $("#empCod").val();
    const terCod = $("#terCod").val();
    const puesCod = $("#puesCod").val();

    if (empCod != "") {
        const emp = $("#empPath").val() + "/" + empCod + "/Auth";
        llenarTer(emp, terCod);
    }
    if (puesCod != "") {
        const puesPath = $("#empSalPath").val() + "/" + empCod;
        llenarPues(puesPath, puesCod);
    }
    $('#sal_inicio').daterangepicker({
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
    $('#sal_fin').daterangepicker({
        "singleDatePicker": true,
        "showDropdowns": true,
        "showWeekNumbers": true,
        "autoApply": false,
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
    let val = $("#sal_fin");
    val.val('');
    val.focusout( function (){
        $("#sal_fin").val('')
    });


});

$("#prod_empresa").on("change", function (event) {
    const emp = $("#empPath").val() + "/" + event.target.value + "/Auth";
    const puesPath = $("#empSalPath").val() + "/" + event.target.value;

    llenarTer(emp, terCod);
    llenarPues(puesPath, puesCod);


});

function llenarTer(empresa, terminal) {
    var selected = "";
    $.get(empresa, function (response) {
        var i = 0;
        $("#prod_terminal").empty();
        for (const i in response) {
            if (response[i].ter_id == terminal) {
                selected = " selected";
            } else {
                selected = "";
            }
            $("#prod_terminal").append(
                "<option value='" + response[i].ter_id + "' " + selected + ">" + response[i].ter_nombre + "</option>"
            );
        }
    });
}
function llenarPues(path, code) {
    var selected = "";
    $.get(path, function (response) {
        var i = 0;
        $("#sal_puesto").empty();
        for (const i in response) {
            if (response[i].ter_id == code) {
                selected = " selected";
            } else {
                selected = "";
            }
            $("#sal_puesto").append(
                "<option value='" + response[i].pues_id + "' " + selected + ">" + response[i].pues_desc_ct + "</option>"
            );
        }
    });
}

