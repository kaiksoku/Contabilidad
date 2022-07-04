$(function () {
    Contabilidad.validacionGeneral("form-general");
    $(".select2").select2({
        language: "es",
        theme: "bootstrap4",
    });
});

$("#com_empresa").on("change", function (event) {
    llenarCuentascontables();
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

function llenarCuentascontables() {
    emp = $("#com_empresa").val();
    nivel= '1101';
    let Tpath = $("#ctaNivel4").val();
    Tpath = Tpath + "/" + emp + "/" + nivel;
    $("#movb_cuentacontable").empty();
    $.get(Tpath, function (response) {
        for (const i in response) {
            $("#movb_cuentacontable").append(
                "<option value='" +
                    response[i].cta_codigo +
                    "' " +
                    ">" +
                    response[i].cta_codigo +
                    " - " +
                    response[i].cta_descripcion +
                    "</option>"
            );
        }
    });
}
