/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function() {
    $('#btn_reconocer').on('click', function(evt) {
        $filas_seleccionadas = $('#tbl_inscritos :checked:enabled').closest('tr');
        $filas_values = [];
        $filas_seleccionadas.map(function(index, value) {
            $id_enrollment = $(value).find("input[type=checkbox]").val();
            $number_of_credits = $(value).find("input[type=number]").val();
            $fila = new Object();
            $fila.id = $id_enrollment;
            $fila.numero_de_creditos = $number_of_credits;
            $filas_values.push($fila);
        });
        console.log(JSON.stringify($filas_values));
        if ($filas_values.length >= 0) {
            $.ajax({
                type: "POST",
                url: Routing.generate('uah_gestoractividades_enrollment_recognize', {activity_id: $(this).data('activity-id')}),
                data: JSON.stringify($filas_values),
                statusCode: {
                    200: function(data) {
                        console.log('Inscrito en la actividad:' + data);
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
});