$(document).ready(function() {
    $('.activities, .activity-modal').on('click', '.enroll-button', function(event) {
        var $id = $(this).data('activity-id');
        var $boton = $(this);
        $boton.html($('#ajax-loading-image').clone());
        $.ajax({
            type: "POST",
            url: Routing.generate('uah_gestoractividades_enrollment_enroll', {activity_id: $id}),
            statusCode: {
                200: function(data) {
                    console.log('Inscrito en la actividad:' + $id);
                    $boton.addClass('btn-success  already-enrolled').removeClass('btn-primary enroll-button');
                    $boton.html('<span class="texto">Inscrito!</span><span class="fa fa-check fa-2x"></span>');
                    $('#modal-enrollment-button').data('enrolled-in', true);
                },
                401: function(data) {
                    $('#notification').removeClass('hide');
                    $('#notification').addClass('alert-info');
                    $('#notification #type').text('Atención');
                    $('#notification #message').html('Tienes que hacer  <a href="login" class="alert-link">login</a>!');
                    $boton.html('<span class="texto">Inscribete!</span><span class="glyphicon glyphicon-pencil"></span>');

                },
                403: function(data) {
                    console.log('Error al inscribirse');
                    $boton.html('<span class="texto">Inscribete!</span><span class="glyphicon glyphicon-pencil"></span>');

                    $('#notification').removeClass('hide');
                    switch (data.responseJSON.type) {
                        case 'notice':
                            $('#notification').addClass('alert-info');
                            $type = 'Notificación';
                            break;
                        case 'warning':
                            $('#notification').addClass('alert-warning');
                            $type = 'Atención';
                            break;
                        case 'error':
                            $('#notification').addClass('alert-danger');
                            $type = 'Error';
                            break;
                        default:
                            if (data.status === 401)
                                $('#notification').addClass('alert-danger');
                            $type = 'Error';

                            break;
                    }
                    $('#notification #type').text($type);
                    $('#notification #message').html(data.responseJSON.message);
                }
            }
        }
        );
    });
});