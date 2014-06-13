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
            $('#modal-title').text($(event.target).closest('.actividad').find('.titulo-actividad').text());
            $('#modal-abstract').text($(event.target).closest('.actividad').find('.abstract-actividad').text());
            $('#modal-fecha').text($(event.target).closest('.actividad').find('.fecha-publicidad-actividad').text());
            $('#modal-image').attr("src", $(event.target).closest('.actividad').find('.imagen-actividad').attr("src"));
        });
    });
//    $('#inputFechaDatePicker .input-group.date').datepicker({
//        format: "dd/mm/yyyy",
//        weekStart: 1,
//        todayBtn: true,
//        language: "es",
//        multidate: true,
//        multidateSeparator: ";",
//        todayHighlight: true
//    });
    $('.actividad').on('click', '.enroll-button', function(event) {
        var $id = $(event.delegateTarget).data('activity-id');
        var $boton = $(this);
//        var $width = $boton.width();
//        var $height = $boton.height();
        var $img = $('#ajax-loading-image');
        //$boton.html('');
        $boton.html($img.clone());

        $.post(Routing.generate('uah_gestoractividades_enrollment_enroll', {activity_id: $id}))
                .done(function() {
                    console.log('Inscrito en la actividad:' + $id);
                    $boton.addClass('btn-success  already-enrolled').removeClass('btn-primary enroll-button');
                    $boton.html('<span class="texto">Inscrito!</span><span class="glyphicon glyphicon-ok"></span>');
                })
                .fail(function() {
                    $boton.html('<span class="texto">Inscribete!</span><span class="glyphicon glyphicon-pencil"></span>');
                    console.log('Error al inscribirse');
                });
    });
});
