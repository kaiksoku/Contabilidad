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
        const cta = $("#ctaPath").val();
        const cta1 = $("#produPath").val();
        const cta2=  $("#productoPath").val();
        llenarTer(emp, terCod);

        setTimeout(llenarCategoria, 100, cta1, terCod);
        setTimeout(llenarSubCategoria, 200, cta2, cta1);
        setTimeout(llenarCtaCon, 300, cta, terCod);
    }
});


$("#empresa").on("blur", function (event) {
    $("#prod_terminal").empty();
    $("#nom_empresa").empty();
    var val = $("#empresa").val();
    var obj = $("#lst_empresa").find("option[value='" + val + "']");
    if (obj != null && obj.length > 0) {
        $("#prod_empresa").val(obj.data("id"));
        $("#nom_empresa").append(obj.data("nombre"));
        $("#prod_empresa").trigger("change");
    } else {
        $("#empresa").val("");
        var self = this;
        setTimeout(function () {
            self.focus();
        }, 10);
    }
});


$("#prod_empresa").on("change", function (event) {
    const emp = $("#empPath").val() + "/" + event.target.value + "/Auth";
    const ctaCod = $("#ctaCod").val();
    llenarTer(emp, terCod);
});

$("#prod_terminal").on("change", function (event) {
    $("#terCod").val($(this).val());
    const terCod = $("#ctaCod").val();
    const cta = $("#ctaPath").val();
    const cta1 = $("#produPath").val();
    const cta3 = $("#produPath").val();
    const cta2=  $("#productoPath").val();
    if ($(this).val() != null) {
        llenarCategoria(cta1, terCod);
        llenarSubCategoria(cta2, cta3);
        llenarCtaCon(cta, terCod);
    }
});


$("#prod_padre1").on("change", function (event) {
    const cta1 = $("#produPath").val();
    const cta2=  $("#productoPath").val();
    if ($(this).val() != null) {
        llenarSubCategoria(cta2, cta1);
    }
});

$("#prod_padre").on("change", function (event) {
    let Tcta = $("#ctaPath").val();
    let TterCod = $("#terCod").val();
    llenarCtaCon(Tcta, TterCod);
});

$("#prod_cuentacontable").on("change", function (event) {
    if ($("#prod_cuentacontable").val() === "1") {
        $("#prod_codigo").prop("disabled", true);
    } else {
        $("#prod_codigo").prop("disabled", false);
    }
});





function llenarTer(empresa, terminal) {
    var selected = "";
    $.get(empresa, function (response) {
        var i = 0;
        $("#prod_terminal").empty();
        for (const i in response) {
            if (response[i].ter_id == terminal) {
                selected = "selected";
            } else {
                selected = "";
            }
            $("#prod_terminal").append(
                "<option value='" +
                    response[i].ter_id +
                    "' " +
                    selected +
                    ">" +
                    response[i].ter_nombre +
                    "</option>"
            );
        }
        $("#prod_terminal").val(null).trigger("change");
    });
}

function llenarCategoria(path, cuenta) {
    emp = $("#prod_empresa").val();
    ter = $("#prod_terminal").val();
    path = path + "/" + emp + "/"+ ter + "/";
    $("#prod_padre1").empty();
    $("#prod_padre1").append(' <option value="0">SIN CATEGORIA</option> ');

    $.get(path, function (response) {
        for (const i in response) {
            if (response[i].prod_id == cuenta) {
                selected = "selected";
            } else {
                selected = "";
            }
            $("#prod_padre1").append(
                "<option value='" +
                    response[i].prod_id +
                    "' " +
                    selected +
                    ">" +
                    response[i].prod_desc_lg +
                    "</option>"
            );
        }

    });
}


function llenarSubCategoria(path, cuenta) {
  cta1=$("#prod_padre1").val();
    path = path + "/" + cta1 + "/";
    $("#prod_padre").empty();
    $("#prod_padre").append(' <option value="0">SIN  SUBCATEGORIA</option> ');
    if(cta1>0)
    {
        $.get(path, function (response) {
            for (const i in response) {
                  $("#prod_padre").append(
                    "<option value='" +
                        response[i].prod_id +
                        "' " +
                        selected +
                        ">" +
                        response[i].prod_desc_lg +
                        "</option>"
                );
            }
        });
    }
}

function llenarCtaCon(path, cuenta) {
    if ($("#prod_cuentacontable")) {
        nivel1 = "[4]";
    }
    cta7=$("#prod_padre").val();
    console.log(cta7);
    emp = $("#prod_empresa").val();
    ter = $("#prod_terminal").val();
    path = path + "/" + emp + "/" + ter + "/" + nivel1 + "/1";
    $("#prod_cuentacontable").empty();
    $("#prod_cuentacontable").append(' <option value="1">SIN CUENTA CONTABLE</option> ');
    if(cta7>0)
    {
        $.get(path, function (response) {
            for (const i in response) {
                if (response[i].cta_id == cuenta) {
                    selected = "selected";
                } else {
                    selected = "";
                }
                $("#prod_cuentacontable").append(
                    "<option value='" +
                        response[i].cta_id +
                        "' " +
                        selected +
                        ">" +
                        response[i].cta_codigo +
                        "  " +
                        selected +
                        " - " +
                        response[i].cta_descripcion +
                        "</option>"
                );
            }
        });
    }
}
