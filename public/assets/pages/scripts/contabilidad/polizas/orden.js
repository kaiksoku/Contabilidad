let TotalDebe=0;
let TotalHaber=0;

function agregar_insumo() {
    $('#BotonGuardar').prop('disabled', true);
    let dpol_ctaContable = $("#dpol_ctaContable option:selected").val();
    let dpol_ctaContable_text = $("#dpol_ctaContable option:selected").text();
    let dpol_monto = $("#dpol_monto").val();
    let debemonto=0;
    let habermonto=0;
    var flexRadioDefault = $("input:radio[name=flexRadioDefault]:checked").val();

    if (flexRadioDefault == 'D') {
        debemonto=dpol_monto;
        TotalDebe =Number(TotalDebe)+Number(debemonto);
      
    } else if (flexRadioDefault =='H') {
        habermonto=dpol_monto;
        TotalHaber =Number(TotalHaber)+ Number(dpol_monto);
    }

    $("#debe").val(TotalDebe);
    $("#haber").val(TotalHaber);

    if(TotalDebe==TotalHaber)$('#BotonGuardar').prop('disabled', false);

    linea = $("#linea").val();
    linea = +linea + 1;
    $("#linea").val(linea);
    if(debemonto==0)debemonto="";
    if(habermonto==0)habermonto="";

    $("#tblInsumos").append(`
                <tr id="tr-${linea}">
                    <td>
                        <input type="hidden" name="dpol_ctaContable[]" value="${dpol_ctaContable}"/>
                        <input type="hidden" name="dpol_monto[]" value="${dpol_monto}"/>
                        <input type="hidden" name="flexRadioDefault[]" value="${flexRadioDefault}"/>
                        ${dpol_ctaContable_text}
                    </td>
                    <td style="text-align: right;">${debemonto}</td>
                    <td style="text-align: right;">${habermonto}</td>
                    <td><button type="button" class="btn-danger" onclick="eliminar_insumo(${linea},'${flexRadioDefault}',${dpol_monto})">
                     <i class="far fa-trash-alt"></button></td>
                </tr>
            `);
}

function eliminar_insumo(linea, flexRadioDefault, dpol_monto) {
    $('#BotonGuardar').prop('disabled', true);

    if (flexRadioDefault == 'D') {
        debemonto=dpol_monto;
        TotalDebe =Number(TotalDebe)-Number(debemonto);
    } else if (flexRadioDefault =='H') {
        habermonto=dpol_monto;
        TotalHaber =Number(TotalHaber)-Number(dpol_monto);
    }

    $("#debe").val(TotalDebe);
    $("#haber").val(TotalHaber);
    if(TotalDebe==TotalHaber)$('#BotonGuardar').prop('disabled', false);

    $("#tr-" + linea).remove();
}

$("input[data-bootstrap-switch]").each(function() {
    $(this).bootstrapSwitch("state", $(this).prop("checked"));
});
