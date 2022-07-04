$("#ordf_moneda").on("change", function (event) {
    $(".rotMoneda").empty();
    $(".rotMoneda").append(
        "(" + $("#ordf_moneda option:selected").data("simbolo") + ")"
    );

    if ($(this).val() == 1) {
        $("label[for=ordf_tipoCambio]").prop("hidden", true);
        $("#ordf_tipoCambio").prop("hidden", true);
        $("#ordf_tipoCambio").val("1");

    } else {
        $("label[for=dof_tipoCambio]").prop("hidden", false);
        $("#ordf_tipoCambio").prop("hidden", false);
        $("#ordf_tipoCambio").val("");
    }
});


$("#ven_moneda").on("change", function (event) {
    $(".rotMoneda").empty();
    $(".rotMoneda").append(
        "(" + $("#ven_moneda option:selected").data("simbolo") + ")"
    );

    if ($(this).val() == 1) {
        $("label[for=ven_tipoCambio]").prop("hidden", false);
        $("#ven_tipoCambio").prop("hidden", false);
        $("#ven_tipoCambio").val("");

    } else {
        $("label[for=ven_tipoCambio]").prop("hidden", true);
        $("#ven_tipoCambio").prop("hidden", true);
        $("#ven_tipoCambio").val("1");
    }
});



var mostrarValor = function(x) {
    document.getElementById('ven_referencia').value=x;
}


$("#agregar").on("click", function () {
    let error = 0;
    if ($("#dof_producto").find(":selected").length == 0) {
        $("#dof_producto").addClass("is-invalid");
        $("label[for='dof_producto']").addClass("text-danger");
        error = 1;
    }
    else
    {
        $("#dof_producto").removeClass("is-invalid");
    }
    if ($("#dof_tarifa").val() == "") {
       $("#dof_tarifa").addClass("is-invalid");
        $("label[for='dof_tarifa']").addClass("text-danger");
        error = 1;
    }
    else
    {
        $("#dof_tarifa").removeClass("is-invalid");
    }
    if ($("#dof_cantidad").val() == "") {
        $("#dof_cantidad").addClass("is-invalid");
        $("label[for='dof_cantidad']").addClass("text-danger");
        error = 1;
    }
    else
    {
        $("#dof_cantidad").removeClass("is-invalid");
    }


    if(error==0)
    {

        let dof_producto = $("#dof_producto option:selected").val();
        let dof_producto_text = $("#dof_producto option:selected").text();
        let dof_tarifa = $("#dof_tarifa").val();
        let dof_cantidad = $("#dof_cantidad").val();
        var subtotaldetalle = $("#dof_tarifa").val() * $("#dof_cantidad").val();
        var iva = subtotaldetalle * 0.12;
        var totald = subtotaldetalle + iva;
        var totalq = totald * $("#ordf_tipoCambio").val();


        linea = $("#linea").val();
        linea = +linea + 1;
        $("#linea").val(linea);


        ct = $("#totalcontenedores").val();
        ct = +ct + +dof_cantidad;
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

        total =$("#ordf_total").val();
        total =totaltqq;
        $("#ordf_total").val(total);



            $("#tblInsumos").append(`
                    <tr id="tr-${linea}">
                        <td>
                            <input type="hidden" name="dof_producto[]" value="${dof_producto}"/>
                            <input type="hidden" name="dof_cantidad[]" value="${dof_cantidad}"/>
                            <input type="hidden" name="dof_tarifa[]" value="${dof_tarifa}"/>
                            <input type="hidden" name="subtotaldetlle[]" value="${subtotaldetalle}"/>
                            <input type="hidden" name="iva[]" value="${iva}"/>
                            <input type="hidden" name="totald[]" value="${totald}"/>
                            <input type="hidden" name="totalq[]" value="${totalq}"/>


                            ${dof_producto_text}
                        </td>
                        <td>${dof_cantidad}</td>
                        <td>${dof_tarifa}</td>
                        <td>${subtotaldetalle.toFixed(2)}</td>
                        <td>${iva.toFixed(2)}</td>
                        <td>${totald.toFixed(2)}</td>
                        <td>${totalq.toFixed(2)}</td>

                        <td><button type="button" class="btn-danger" onclick="eliminar_insumo(${linea},${dof_producto}, ${dof_cantidad},
                         ${dof_tarifa},${subtotaldetalle}, ${iva}, ${totald}, ${totalq}, ${total})">
                         <i class="far fa-trash-alt"></button></td>
                    </tr>
                `);
    }

        });



function eliminar_insumo(linea,dof_producto,dof_cantidad,dof_tarifa,subtotaldetalle,iva,totald,totalq,total) {

    ct = $("#totalcontenedores").val();
    ct = +ct -+dof_cantidad;
    $("#totalcontenedores").val(ct);

    tiva = $("#totaliva").val();
    tiva = ((+tiva - +iva).toFixed(5));
    $("#totaliva").val(tiva);

    tsiva = $("#totalsiniva").val();
    tsiva = ((+tsiva - +subtotaldetalle).toFixed(5));
    $("#totalsiniva").val(tsiva);

    totaltdd = $("#totaldolar").val();
    totaltdd= ((+totaltdd - +totald).toFixed(5));
    $("#totaldolar").val(totaltdd);


    totaltqq = $("#totalquetzal").val();
    totaltqq= ((+totaltqq - +totalq).toFixed(5));
    $("#totalquetzal").val(totaltqq);

    total =$("#ordf_total").val();
    total =totaltqq;
    $("#ordf_total").val(total);

    totiva = $("#civa").val();
    totiva = ((+totiva -+subtotaldetalle).toFixed(5));
    $("#civa").val(totiva);

    $("#tr-" + linea).remove();

}



