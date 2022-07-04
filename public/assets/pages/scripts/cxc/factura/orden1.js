$("#ven_moneda").on("change", function (event) {
    $(".rotMoneda").empty();
    $(".rotMoneda").append(
        "(" + $("#ven_moneda option:selected").data("simbolo") + ")"
    );

    if ($(this).val() == 1) {
        $("label[for=ven_tipoCambio]").prop("hidden", true);
        $("#ven_tipoCambio").prop("hidden", true);
        $("#ven_tipoCambio").val("1");

    } else {
        $("label[for=detv_tipoCambio]").prop("hidden", false);
        $("#ven_tipoCambio").prop("hidden", false);
        $("#ven_tipoCambio").val("");
    }
});


var mostrarValor = function(x) {
    document.getElementById('ven_referencia').value=x;
}





$("#agregar").on("click", function () {
    error = 0;
    if ($("#detv_producto").find(":selected").length == 0) {
        $("#detv_producto").addClass("is-invalid");
        $("label[for='detv_producto']").addClass("text-danger");
        error = 1;
    }
    else
    {
        $("#detv_producto").removeClass("is-invalid");
    }
    if ($("#detv_precioU").val() == "") {
        $("#detv_precioU").addClass("is-invalid");
        $("label[for='detv_precioU']").addClass("text-danger");
        error = 1;
    }
    else
    {
        $("#detv_precioU").removeClass("is-invalid");
    }

    if ($("#detv_cantidad").val() == "") {
        $("#detv_cantidad").addClass("is-invalid");
        $("label[for='detv_cantidad']").addClass("text-danger");
        error = 1;
    }
    else
    {
        $("#detv_cantidad").removeClass("is-invalid");
    }


    if(error==0)
    {
    let detv_producto = $("#detv_producto option:selected").val();
    let detv_producto_text = $("#detv_producto option:selected").text();
    let detv_precioU = $("#detv_precioU").val();
    let detv_cantidad = $("#detv_cantidad").val();
    var subtotaldetalle = $("#detv_precioU").val() * $("#detv_cantidad").val();
    var iva = subtotaldetalle * 0.12;
    var totald = subtotaldetalle + iva;
    var totalq = totald * $("#ven_tipoCambio").val();


    linea = $("#linea").val();
    linea = +linea + 1;
    $("#linea").val(linea);


    ct = $("#totalcontenedores").val();
    ct = +ct + +detv_cantidad;
    $("#totalcontenedores").val(ct);

    tiva = $("#totaliva").val();
    tiva = ((+tiva + +iva).toFixed(2));
    $("#totaliva").val(tiva);

    tsiva = $("#totalsiniva").val();
    tsiva = ((+tsiva + +subtotaldetalle).toFixed(2));
    $("#totalsiniva").val(tsiva);

    totiva = $("#civa").val();
    totiva = ((+totiva + +subtotaldetalle).toFixed(2));
    $("#civa").val(totiva);

    totaltdd = $("#totaldolar").val();
    totaltdd= ((+totaltdd + +totald).toFixed(2));
    $("#totaldolar").val(totaltdd);


    totaltqq = $("#totalquetzal").val();
    totaltqq= ((+totaltqq + +totalq).toFixed(2));
    $("#totalquetzal").val(totaltqq);

    total =$("#ven_total").val();
    total =totaltqq;
    $("#ven_total").val(total);



        $("#tblInsumos").append(`
                <tr id="tr-${linea}">
                    <td>
                        <input type="hidden" name="detv_producto[]" value="${detv_producto}"/>
                        <input type="hidden" name="detv_cantidad[]" value="${detv_cantidad}"/>
                        <input type="hidden" name="detv_precioU[]" value="${detv_precioU}"/>
                        <input type="hidden" name="subtotaldetlle[]" value="${subtotaldetalle}"/>
                        <input type="hidden" name="iva[]" value="${iva}"/>
                        <input type="hidden" name="totald[]" value="${totald}"/>
                        <input type="hidden" name="totalq[]" value="${totalq}"/>


                        ${detv_producto_text}
                    </td>
                    <td>${detv_cantidad}</td>
                    <td>${detv_precioU}</td>
                    <td>${subtotaldetalle.toFixed(2)}</td>
                    <td>${iva.toFixed(2)}</td>
                    <td>${totald.toFixed(2)}</td>
                    <td>${totalq.toFixed(2)}</td>

                    <td><button type="button" class="btn-danger" onclick="eliminar_insumo(${linea},${detv_producto}, ${detv_cantidad},
                     ${detv_precioU},${subtotaldetalle}, ${iva}, ${totald}, ${totalq}, ${total})">
                     <i class="far fa-trash-alt"></button></td>
                </tr>
            `);
        }

        });



function eliminar_insumo(linea,dof_producto, detv_cantidad, detv_precioU,subtotaldetalle,iva,totald,totalq,total) {

      ct = $("#totalcontenedores").val();
    ct = +ct -+detv_cantidad;
    $("#totalcontenedores").val(ct);

    tiva = $("#totaliva").val();
    tiva = ((+tiva - +iva).toFixed(2));
    $("#totaliva").val(tiva);

    tsiva = $("#totalsiniva").val();
    tsiva = ((+tsiva - +subtotaldetalle).toFixed(2));
    $("#totalsiniva").val(tsiva);

    totaltdd = $("#totaldolar").val();
    totaltdd= ((+totaltdd - +totald).toFixed(2));
    $("#totaldolar").val(totaltdd);


    totaltqq = $("#totalquetzal").val();
    totaltqq= ((+totaltqq - +totalq).toFixed(2));
    $("#totalquetzal").val(totaltqq);

    total =$("#ven_total").val();
    total =totaltqq;
    $("#ven_total").val(total);

    totiva = $("#civa").val();
    totiva = ((+totiva -+subtotaldetalle).toFixed(2));
    $("#civa").val(totiva);

    $("#tr-" + linea).remove();

}



