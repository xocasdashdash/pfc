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
        $.post(Routing.generate('uah_gestoractividades_enrollment_enroll', {activity_id: $id}))
                .done(function() {
                    console.log('Inscrito en la actividad:' + activity_id);
                })
                .fail(function() {
                    console.log('Error al inscribirse');
                });
    });
});
