$(function() {
    Contabilidad.validacionGeneral('form-general');

    $('#excelsubido').hide()

    $("#excel").change(function() {
        $('#excelsubido').show()
    });

});
