$("input[data-bootstrap-switch]").each(function() {
    $(this).bootstrapSwitch("state", $(this).prop("checked"));
});
$(function () {
    Contabilidad.validacionGeneral("form-general");

    $(".select2").select2({
        language: "es",
        theme: "bootstrap4",
    });

    $("#tipoCombustible").next(".select2-container").hide();

    $("input[type='number']").inputSpinner();

    $("#facturaActivo").next(".select2-container").hide();
    $('label[for="facturaActivo"]').hide();

    $("#numeroRetencion").hide();
    $("#gfechaRetencion").hide();
    $('label[for="numeroRetencion"]').hide();
    $('label[for="fechaRetencion"]').hide();

    $("#com_excento").hide();
    $('label[for="com_ctaExcento"]').hide();
    $("#com_ctaExcento").next(".select2-container").hide();

    $("#nomProveedor").val($("#com_persona option:selected").data("nombre"));

    if ($("#com_persona option:selected").data("tipo") == 1) {
        $("#com_retencion").attr("disabled", true);
        $("#com_retencion").prop("checked", false);
        $("#com_peqcontribuyente").prop("checked", true);
    }

    const empCod = $("#empCod").val();
    const terCod = $("#terCod").val();
    if (empCod != "") {
        const emp = $("#empPath").val() + "/" + empCod + "/Auth";
        const cta1 = $("#ctaNivel1").val();
        llenarTer(emp, terCod);
        llenarCtaCon(cta1, terCod);
    }

    $("#com_fecha").daterangepicker({
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
    $("#fechaRetencion").daterangepicker({
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

$("#com_empresa").on("change", function (event) {
    const terCod = $("#ctaCod").val();
    const cta1 = $("#ctaNivel1").val();
    const ctrcsts = $("#ctrcsts").val();
    llenarCtaCon(cta1, terCod);
    llenarCentrocostos(ctrcsts, terCod);
});

$("#com_persona").on("change", function (event) {
    $("#nomProveedor").val($("#com_persona option:selected").data("nombre"));
    if ($("#com_persona option:selected").data("tipo") == 1) {
        $("#com_retencion").attr("disabled", true);
        $("#com_retencion").prop("checked", false);
        $("#com_peqcontribuyente").prop("checked", true);
    } else {
        $("#com_retencion").attr("disabled", false);
        $("#com_retencion").prop("checked", false);
        $("#com_peqcontribuyente").prop("checked", false);
    }
});

$("#Nivel1").on("change", function (event) {
    $(this).removeClass("is-invalid");
    let Nivel = $("#Nivel1").val();
    const cta2 = $("#ctaNivel2").val();
    if ($(this).val() != null) {
        llenarCtaCon2(cta2, Nivel);
    }
});

$("#Nivel2").on("change", function (event) {
    $(this).removeClass("is-invalid");
    let Nivel = $("#Nivel2").val();
    const cta3 = $("#ctaNivel3").val();
    if ($(this).val() != null) {
        llenarCtaCon3(cta3, Nivel);
    }
});
$("#Nivel3").on("change", function (event) {
    $(this).removeClass("is-invalid");
    let Nivel = $("#Nivel3").val();
    const cta4 = $("#ctaNivel4").val();
    if ($(this).val() != null) {
        llenarCtaCon4(cta4, Nivel);
    }
});
$("#Nivel4").on("change", function (event) {
    $(this).removeClass("is-invalid");
});

$("#aplicaActivo").on("change", function (event) {
    if ($(this).is(":checked")) {
        $("#facturaActivo").next(".select2-container").show();
        $('label[for="facturaActivo"]').show();
        $("#facturaActivo").next(".select2-container").attr("required", true);
        $('label[for="facturaActivo"]').addClass("requerido");
    } else {
        $("#facturaActivo").next(".select2-container").hide();
        $('label[for="facturaActivo"]').hide();
        $("#facturaActivo").next(".select2-container").attr("required", false);
        $('label[for="facturaActivo"]').removeClass("requerido");
    }
});

$("#aplicaExcento").on("change", function (event) {
    if ($(this).is(":checked")){
        $("#com_excento").show();
        $('label[for="com_ctaExcento"]').show();
        $("#com_ctaExcento").next(".select2-container").show();
        $('label[for="aplicaExcento"]').addClass("requerido");
        $('label[for="com_ctaExcento"]').addClass("requerido");
        $("#com_ctaExcento").next(".select2-container").attr("required", true);
        $("#com_excento").attr("required",true);
    } else {
        $("#com_excento").hide();
        $('label[for="com_ctaExcento"]').hide();
        $("#com_ctaExcento").next(".select2-container").hide();
        $('label[for="aplicaExcento"]').removeClass("requerido");
        $('label[for="com_ctaExcento"]').removeClass("requerido");
        $("#com_ctaExcento").next(".select2-container").attr("required", false);
        $("#com_excento").attr("required",false);
    }
});

$("#com_tipo").on("change", function (event) {
    $("#tipoCombustible").next(".select2-container").hide();
    $('label[for="tipoCombustible"]').hide();
    $('label[for="precioU"]').text("Precio Unitario");
    $("#precioU").attr("placeholder", "Precio Unitario");
    $('label[for="tipoCombustible"]').removeClass("requerido");
    $("#tipoCombustible").next(".select2-container").attr("required", false);
    if ($(this).val() == 3) {
        $("#tipoCombustible").next(".select2-container").show();
        $('label[for="tipoCombustible"]').show();
        $('label[for="tipoCombustible"]').addClass("requerido");
        $("#totalComb").attr("required", true);
        $("#totalComb").show();
        $('label[for="totalComb"]').show();
        $('label[for="totalComb"]').addClass("requerido");
        $("#tipoCombustible").next(".select2-container").attr("required", true);
    }
});

$("#com_descripcion").on("change", function (event) {
    $("#descripcion").val($(this).val());
});

$("#com_fecha").on("change", function () {
    meses = [
        "ENERO",
        "FEBRERO",
        "MARZO",
        "ABRIL",
        "MAYO",
        "JUNIO",
        "JULIO",
        "AGOSTO",
        "SEPTIEMBRE",
        "OCTUBRE",
        "NOVIEMBRE",
        "DICIEMBRE",
    ];
    $("#com_mesReportar").empty();
    fecha = $(this).val().split("/");
    if (fecha[1] >= new Date().getMonth() - 1) {
        inicio = new Date(fecha[1] + "/" + fecha[0] + "/" + fecha[2]);
        for (i = new Date().getMonth(); i <= inicio.getMonth() + 2; i++) {
            j = i + 1;
            $("#com_mesReportar").append(
                "<option value='" + j + "'>" + meses[i] + "</option>"
            );
        }
    } else {
        $("#com_mesReportar").append(
            "<option value='0'>NO SE PUEDE REPORTAR</option>"
        );
    }
});


$("#empresa").on("blur", function (event) {
    $("#com_terminal").empty();
    $("#nom_empresa").empty();
    var val = $("#empresa").val();
    var obj = $("#lst_empresa").find("option[value='" + val + "']");
    if (obj != null && obj.length > 0) {
        $("#com_empresa").val(obj.data("id"));
        $("#nom_empresa").append(obj.data("nombre"));
        $("#com_empresa").trigger("change");
    } else {
        $("#empresa").val("");
        var self = this;
        setTimeout(function () {
            self.focus();
        }, 10);
    }
});

$("#agregar").on("click", function () {
    error = 0;
    if ($("#descripcion").val() == "") {
        $("#descripcion").addClass("is-invalid");
        $("label[for='descripcion']").addClass("text-danger");
        error = 1;
    }
    if ($("#precioU").val() == "") {
        $("#precioU").addClass("is-invalid");
        $("label[for='precioU']").addClass("text-danger");
        error = 1;
    }
    if ($("#cantidad").val() == "") {
        $("#cantidad").addClass("is-invalid");
        $("label[for='cantidad']").addClass("text-danger");
        error = 1;
    }
    if ($("#Nivel1").find(":selected").length == 0) {
        $("#Nivel1").addClass("is-invalid");
        $("label[for='Nivel1']").addClass("text-danger");
        error = 1;
    }
    if ($("#com_tipo").val() == 3) {
        if ($("#tipoCombustible").find(":selected").val() == 0) {
            $("#tipoCombustible").addClass("is-invalid");
            $("label[for='tipoCombustible']").addClass("text-danger");
            error = 1;
        }
        if ($("#totalComb").val() == "") {
            $("#totalComb").addClass("is-invalid");
            $("label[for='totalComb']").addClass("text-danger");
            error = 1;
        }
    }
    if (error == 0) {
        $("#submit").removeClass("disabled");
        if ($("#com_tipo").val() == 3) {
            cant = $("#totalComb").val() / $("#precioU").val();
        } else {
            cant = parseFloat($("#cantidad").val());
        }
        desc = $("#descripcion").val();
        preu = parseFloat($("#precioU").val());
        vtG = $("#Nivel1 option:selected").val();
        stG = $("#Nivel1 option:selected").text();
        vtCom = $("#tipoCombustible option:selected").val();
        stCom = $("#tipoCombustible option:selected").text();
        IDP = $("#tipoCombustible option:selected").data("IDP");
        gt = $("#com_monto").val();
        linea = $("#linea").val();
        linea = +linea + 1;
        $("#linea").val(linea);
        tot = cant * preu;
        gt = +gt + +tot;
        $("#com_monto").val(gt);
        $("#detCompra").append(`<tr id="tr-${linea}">
                            <td>
                            <input type="hidden" name="detCantidad[]" value="${cant}">
                            <input type="hidden" name="detDescripcion[]" value="${desc}">
                            <input type="hidden" name="detprecioU[]" value="${preu}">
                            <input type="hidden" name="detNivel1[]" value="${vtG}">
                            <input type="hidden" name="dettipoComb[]" value="${vtCom}">
                            <input type="hidden" name="detIDP[]" value="${IDP}">
                            </td>
                            <td>${cant.toFixed(2)}</td>
                            <td>${desc}</td><td>${preu.toFixed(
            2
        )}</td><td>${tot.toFixed(2)}</td>
                            <td>${stG}</td>
                            <td><button type="button" class="btn-sm btn-danger" onclick="eliminar_insumo(${linea},${tot})">
                            <i class="far fa-trash-alt"></button></td>
                            </tr>
    `);
    }
});

function llenarCentrocostos(path) {
    emp = $("#com_empresa").val();
    path = path + "/" + emp;
    $("#Centrocostos").empty();
    $.get(path, function (response) {
        for (const i in response) {
            $("#Centrocostos").append(
                "<option value='" +
                    response[i].cco_codigo +
                    "' >" +
                    response[i].cco_codigo +
                    " - " +
                    response[i].cco_descripcion +
                    "</option>"
            );
        }
    });
}

function llenarCtaCon(path, cuenta) {
    emp = $("#com_empresa").val();
    path = path + "/" + emp;
    $("#Nivel1").empty();
    $.get(path, function (response) {
        for (const i in response) {
            if (response[i].cta_id == cuenta) {
                selected = "selected";
            } else {
                selected = "";
            }
            $("#Nivel1").append(
                "<option value='" +
                    response[i].cta_codigo +
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
    let cta2 = $("#ctaNivel2").val();
    $("#Nivel1").removeClass('requerido');
    setTimeout(llenarCtaCon2, 100, cta2);
}

function llenarCtaCon2(path) {
    emp = $("#com_empresa").val();
    nivel= $("#Nivel1").val();
    if(nivel==null)nivel=1;
    path = path + "/" + emp + "/" + nivel;
    $("#Nivel2").empty();
    $.get(path, function (response) {
        $("#Nivel2").append(
            "<option value='0' selected>Seleccinar Cuenta</option>"
        );
        for (const i in response) {
            $("#Nivel2").append(
                "<option value='" +
                    response[i].cta_codigo +
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
    const cta3 = $("#ctaNivel3").val();
    llenarCtaCon3(cta3, terCod);
}

function llenarCtaCon3(path, cuenta) {
    emp = $("#com_empresa").val();
    ter = $("#com_terminal").val();
    nivel= $("#Nivel2").val();
    path = path + "/" + emp + "/" + nivel;
    $("#Nivel3").empty();
    $.get(path, function (response) {
        $("#Nivel3").append(
            "<option value='0' selected>Seleccinar Cuenta</option>"
        );
        for (const i in response) {
            $("#Nivel3").append(
                "<option value='" +
                    response[i].cta_codigo +
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
    const cta4 = $("#ctaNivel4").val();
    llenarCtaCon4(cta4, terCod);
}

function llenarCtaCon4(path, cuenta) {
    emp = $("#com_empresa").val();
    ter = $("#com_terminal").val();
    nivel= $("#Nivel3").val();
    path = path + "/" + emp + "/" + nivel;
    $("#Nivel4").empty();
    $.get(path, function (response) {
        $("#Nivel4").append(
            "<option value='0' selected>Crear Cuenta</option>"
        );
        for (const i in response) {
            $("#Nivel4").append(
                "<option value='" +
                    response[i].cta_codigo +
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

function llenarExcento(path) {
    emp = $("#com_empresa").val();
    ter = $("#com_terminal").val();
    path = path + "/" + emp + "/" + ter;
    $("#com_ctaExcento").empty();
    $.get(path, function (response) {
        for (const i in response) {
            $("#com_ctaExcento").append(
                "<option value='" +
                    response[i].cta_codigo +
                    "'>" +
                    response[i].cta_codigo +
                    " - " +
                    response[i].cta_descripcion +
                    "</option>"
            );
        }
    });
}

/*

const cta2 = $("#ctaNivel2").val();
        llenarCtaCon2(cta2, terCod);
*/
