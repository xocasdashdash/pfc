$(document).ready(function() {
    $(function() {
        $('#actividadModal').modal({
            keyboard: true,
            backdrop: "static",
            show: false

        }).on('show.bs.modal', function() { //subscribe to show method
            var getNombrefromRow = $(event.target).data('id');
            var valorNombre = $(event.target).text();
            $('#myModalLabel').text(valorNombre);
        });
    });
    $(function() {
        $('#actividadModalPrincipal').modal({
            keyboard: true,
            backdrop: "static",
            show: false

        }).on('show.bs.modal', function() { //subscribe to show method
            var getNombrefromRow = $(event.target).data('id');
            $('#modal-title').html($(event.target).closest('.activity').find('.titulo-actividad').html());
            $('#modal-abstract').html($(event.target).closest('.activity').find('.abstract-actividad').html());
            $('#modal-date').html($(event.target).closest('.activity').find('.activity-date').html());
            $('#modal-image a').attr("href", $(event.target).closest('.activity').find('.right-column a').attr("href"));
            $('#modal-image a img').attr("src", $(event.target).closest('.activity').find('.img-activity').attr("src"));
            $('#modal-enrollment-button').html($(event.target).closest('.activity').find('.enrollment-button').html());
            $('.modal-content.activity').data('activity-id', $(event.target).closest('.activity').data('activity-id'));
        }).on('hide.bs.modal', function() {
            if ($('.modal-content.activity').data('enrolled-in')) {
                var activity_id = $('.modal-content.activity').data('activity-id');
                $('.activity').each(function(index, value) {
                    if ($(value).data('activity-id') === activity_id) {
                        $boton = $(value).find('.enroll-button');
                        $boton.addClass('btn-success  already-enrolled').removeClass('btn-primary enroll-button');
                        $boton.html('<span class="texto">Inscrito!</span><span class="fa fa-check fa-2x"></span>');
                    }
                });
            }
        });
    });
    $('.activity').on('click', '.enroll-button', function(event) {
        var $id = $(event.delegateTarget).data('activity-id');
        var $boton = $(this);
//        var $width = $boton.width();
//        var $height = $boton.height();
        var $img = $('#ajax-loading-image');
        //$boton.html('');
        $boton.html($img.clone());
        $.ajax({
            type: "POST",
            url: Routing.generate('uah_gestoractividades_enrollment_enroll', {activity_id: $id}),
            statusCode: {
                200: function(data) {
                    console.log('Inscrito en la actividad:' + $id);
                    $boton.addClass('btn-success  already-enrolled').removeClass('btn-primary enroll-button');
                    $boton.html('<span class="texto">Inscrito!</span><span class="fa fa-check fa-2x"></span>');
                    $(event.delegateTarget).data('enrolled-in', true);
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
                    $('#notification #message').text(data.responseJSON.message);
                }
            }
        }
        );
    });
    $('#notification').bind('close.bs.alert', function(evt) {
        evt.preventDefault();
        $('#notification').addClass('hide');
        $('#notification').removeClass('alert-info alert-warning alert-danger');
    });
    $('.img-activity').click(function(e) {
        $('#imgmodal img').attr('src', $(this).attr('data-img-src'));
    });
});
