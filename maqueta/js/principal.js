window.onload = function() {
    var $elemento = $("#cont-basico").html();
    for (i = 0; i < 2; i++) {
        $($elemento).appendTo("#cont-basico");
    }
};
$(document).ready(function() {
    $(function() {
        $('#actividadModal').modal({
            keyboard: true,
            backdrop: "static",
            show: false

        }).on('show.bs.modal', function() { //subscribe to show method
            var getNombrefromRow = $(event.target).data('id');
            var valorNombre = $(event.target).text();
            //alert('hola');
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
            var valorNombre = $(event.target).parent().children('h3').text();
            //alert('hola');
            $('#myModalLabel').text(valorNombre);
        });
    });

    $('#inputFechaDatePicker .input-group.date').datepicker({
        format: "dd/mm/yyyy",
        weekStart: 1,
        todayBtn: true,
        language: "es",
        multidate: true,
        multidateSeparator: ";",
        todayHighlight: true
    });
});
