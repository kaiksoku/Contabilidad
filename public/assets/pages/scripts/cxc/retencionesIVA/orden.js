$("#agregar").on("click", function () {
    error = 0;
    if ($("#detr_factura").find(":selected").length == 0) {
        $("#detr_factura").addClass("is-invalid");
        $("label[for='detr_factura']").addClass("text-danger");
        error = 1;
    }
    else
    {
        $("#detr_factura").removeClass("is-invalid");
    }
    if ($("#detr_retencion").val() == "") {
        $("#detr_retencion").addClass("is-invalid");
        $("label[for='detr_retencion']").addClass("text-danger");
        error = 1;
    }
    else
    {
        $("#detr_retencion").removeClass("is-invalid");
    }

    if ($("#detr_tiporetencion").find(":selected").length == 0) {
        $("#detr_tiporetencion").addClass("is-invalid");
        $("label[for='detr_tiporetencion']").addClass("text-danger");
        error = 1;
    }
    else
    {
        $("#detr_tiporetencion").removeClass("is-invalid");
    }


    if(error==0)
    {
    let detr_factura = $("#detr_factura option:selected").val();
    let detr_factura_text = $("#detr_factura option:selected").text();
    let detr_tiporetencion = $("#detr_tiporetencion option:selected").val();
    let detr_tiporetencion_text = $("#detr_tiporetencion option:selected").text();
    let detr_retencion = $("#detr_retencion").val();

    linea = $("#linea").val();
    linea = +linea + 1;
    $("#linea").val(linea);

    cw = $("#retencion").val();
    cw = +cw + +detr_retencion;
    $("#retencion").val(cw);


    total =$("#docv_monto").val();
    total =cw;
    $("#docv_monto").val(total);

    $("#tblInsumos").append(`
                <tr id="tr-${detr_factura}">
                    <>
                    <input type="hidden" name="detr_tiporetencion[]" value="${detr_tiporetencion}"/>
                    <input type="hidden" name="detr_factura[]" value="${detr_factura}"/>
                    <input type="hidden" name="detr_retencion[]" value="${detr_retencion}"/>

                    ${detr_factura_text}
                    ${detr_tiporetencion_text}


                    </td>

                    <td>${detr_tiporetencion_text}</td>
                    <td>${detr_factura_text}</td>
                    <td>${detr_retencion}</td>

                    <td><button type="button" class="btn-danger" onclick="eliminar_insumo(${detr_tiporetencion},${detr_factura},
                     ${detr_retencion})">
                     <i class="far fa-trash-alt"></button></td>
                </tr>
            `);
}
});

function eliminar_insumo(dof_producto) {
    let detr_factura = $("#detr_factura option:selected").val();
    let detr_factura_text = $("#detr_factura option:selected").text();
    let detr_tarifa = $("#detr_tarifa").val();
    let detr_importe = $("#detr_importe").val();
    let detr_retencion = $("#detr_retencion").val();

    linea = $("#linea").val();
    linea = +linea + 1;
    $("#linea").val(linea);

    cw = $("#retencion").val();
    cw = +cw + -+detr_retencion;
    $("#retencion").val(cw);

    $("#tr-" + dof_producto).remove();
}
