$(function() {
  $('.activities, .activity-modal, .activity').on('click', '.enroll-button', function(event) {
    var $id = $(this).data('activity-id');
    var $boton = $(this);
    $boton.html($('#ajax-loading-image').clone());
    $.ajax({
      type: "POST",
      url: Routing.generate('uah_gestoractividades_enrollment_enroll', {
        activity_id: $id
      })
    }).done(function(data) {
      $boton.addClass('btn-success  already-enrolled').removeClass('btn-primary enroll-button');
      $boton.html('<span class="texto">Inscrito!</span><span class="fa fa-check fa-2x"></span>');
      $('#modal-enrollment-button').data('enrolled-in', true);
    }).
    fail(function(data) {
      if (data.status === 401) {
        $('#notification').removeClass('hide');
        $('#notification').addClass('alert-info');
        $('#notification #type').text('Atención');
        $('#notification #message').html('Tienes que hacer  <a href="' + Routing.generate('fp_openid_security_check', {
          openid_identifier: 'http://yo.rediris.es/soy/@uah.es'
        }) + '" class="alert-link">login</a>!');
        $boton.html('<span class="texto">Inscribete!</span><span class="glyphicon glyphicon-pencil"></span>');
      } else if (data.status === 403) {
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
            $('#notification').addClass('alert-danger');
            $type = 'Error';
            break;
        }
        $('#notification #type').text($type);
        $('#notification #message').html(data.responseJSON.message);
      }
    });
  });
});
