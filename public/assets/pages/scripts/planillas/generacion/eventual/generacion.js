    function forzarSeptimo(salario,planilla) {

        let tipo =$('#checkbox' + salario).prop('checked')? 1: 0
        var path = $("#authPath").val() + '/' + salario+"/"+planilla+"/"+tipo
        $.get(path, function () {
            if ($('#checkbox' + salario).prop('checked')) {
                Contabilidad.notificacion('Septimo forzado con exito','Planillas','success');
            }else{
                Contabilidad.notificacion('Septimo no forzado con exito','Planillas','error');
            }
        });
    }
