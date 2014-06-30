/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function() {
    function getSelectedIds(type) {
        var $checked_rows = [];
        $filas_seleccionadas = $('#tbl_enrolled ' + type + ' input[type=checkbox]:checked').closest('tr');
        $filas_seleccionadas.map(function(index, value) {
            $id_enrollment = $(value).find("input[type=checkbox]").val();
            $checked_rows.push($id_enrollment);
        });
        return $checked_rows;
    }
//    var config = {
//        id: 'sampletooltip',
//        content: {
//            text: 'Hi. I am a sample tooltip!',
//            title: 'Sample tooltip'
//        }
//    };
    $('[title!=""]').qtip({
        content: {
            title: 'Para que sirve'
        },
        position: {
            my: 'top center', // Position my top left...
            at: 'bottom center', // at the bottom right of...
            //target: $('.selector') // my target
        },
        //style: {classes: 'qtip-light'},
        adjust: {
            x: 10,
            y: 1000
        }

    });

    $('#btn_not_present').on('click', function(evt) {
        $activity_id = $(this).data('activity-id');
        $checked_rows = getSelectedIds('.reconocer');
    });

    $('#btn_not_present').on('click', function(evt) {
        $activity_id = $(this).data('activity-id');
        $checked_rows = getSelectedIds('.reconocer');
    });

    $('#btn_unrecognize').on('click', function(evt) {
        $activity_id = $(this).data('activity-id');
        $checked_rows = getSelectedIds('.unrecognize');
        bootbox.confirm("¿Estás seguro que quieres eliminar estos reconocimientos?", function(result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: Routing.generate('uah_gestoractividades_enrollment_unrecognize', {activity_id: $activity_id}),
                    data: JSON.stringify($checked_rows),
                    statusCode: {
                        200: function(data) {
                            bootbox.alert("Reconocimiento eliminado", function() {
                                location.reload(true);
                            });
                        },
                        401: function(data) {
                            bootbox.alert("No tienes permiso para realizar esta acción", function() {
                                location.reload(true);
                            });
                        },
                        403: function(data) {
                            bootbox.alert("No se ha podido ejecutar la solicitud.");
                        }
                    }
                })
            }
        });
    });

    $('#btn_reconocer').on('click', function(evt) {
        $filas_seleccionadas = $('#tbl_enrolled :checked:enabled').closest('tr');
        $checked_rows = [];
        var valid_data = true;
        $filas_seleccionadas.map(function(index, value) {
            if ($(value).find("input[type=number]:invalid").length !== 0 ||
                    $(value).find("input[type=number]").val().length === 0) {
                valid_data = false;
                return false;
            } else {
                $id_enrollment = $(value).find("input[type=checkbox]").val();
                $number_of_credits = $(value).find("input[type=number]").val();
                $fila = new Object();
                $fila.id = $id_enrollment;
                $fila.number_of_credits = $number_of_credits;
                $checked_rows.push($fila);
            }
        });
        if (!valid_data || $checked_rows.length === 0) {
            var str = "";
            if (!valid_data)
                str = "Alguno de los rangos de créditos es inválido. Compruebalo";
            else {
                str = "No has marcado ninguno para reconocer";
            }
            bootbox.alert(str);
        } else {
            $activity_id = $(this).data('activity-id');
            bootbox.confirm("¿Estás seguro que quieres reconocer estos créditos a estos participantes?", function(result) {
                if (result) {
                    $.ajax({
                        type: "POST",
                        url: Routing.generate('uah_gestoractividades_enrollment_recognize', {activity_id: $activity_id}),
                        data: JSON.stringify($checked_rows),
                        statusCode: {
                            200: function(data) {
                                location.reload(true);
                            },
                            401: function(data) {
                                /*
                                 $('#notification').removeClass('hide');
                                 $('#notification').addClass('alert-info');
                                 $('#notification #type').text('Atención');
                                 $('#notification #message').html('Tienes que hacer  <a href="login" class="alert-link">login</a>!');
                                 $boton.html('<span class="texto">Inscribete!</span><span class="glyphicon glyphicon-pencil"></span>');
                                 */
                            },
                            403: function(data) {
                                console.log('Error al inscribirse');
//                    $boton.html('<span class="texto">Inscribete!</span><span class="glyphicon glyphicon-pencil"></span>');
//
//                    $('#notification').removeClass('hide');
//                    switch (data.responseJSON.type) {
//                        case 'notice':
//                            $('#notification').addClass('alert-info');
//                            $type = 'Notificación';
//                            break;
//                        case 'warning':
//                            $('#notification').addClass('alert-warning');
//                            $type = 'Atención';
//                            break;
//                        case 'error':
//                            $('#notification').addClass('alert-danger');
//                            $type = 'Error';
//                            break;
//                        default:
//                            if (data.status === 401)
//                                $('#notification').addClass('alert-danger');
//                            $type = 'Error';
//
//                            break;
//                    }
//                    $('#notification #type').text($type);
//                    $('#notification #message').text(data.responseJSON.message);
                            }
                        }
                    }
                    );
                }

            });
        }
    });

    $('.reconocer input[type=number]').on('change', function(evt) {
        $fila = $(this).closest('tr');
        $fila.find("input[type=checkbox]").prop('checked', this.checkValidity());
    });
});