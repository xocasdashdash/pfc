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

        }).on('show.bs.modal', function(event) { //subscribe to show method
            var getNombrefromRow = $(event.relatedtarget).data('id');
            $('#modal-title').html($(event.relatedTarget).closest('.activity').find('.titulo-actividad').html());
            $('#modal-abstract').html($(event.relatedTarget).closest('.activity').find('.abstract-actividad').html());
            $('#modal-date').html($(event.relatedTarget).closest('.activity').find('.activity-date').html());
            $('#modal-image a').attr("href", $(event.relatedTarget).closest('.activity').find('.right-column a').attr("href"));
            $('#modal-image a img').attr("src", $(event.relatedTarget).closest('.activity').find('.img-activity').attr("src"));
            $('#modal-enrollment-button').html($(event.relatedTarget).closest('.activity').find('.enrollment-button').html());
            $('.modal-content.activity').data('activity-id', $(event.relatedTarget).closest('.activity').data('activity-id'));
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

    $('#notification').bind('close.bs.alert', function(evt) {
        evt.preventDefault();
        $('#notification').addClass('hide');
        $('#notification').removeClass('alert-info alert-warning alert-danger');
    });
    $('.img-activity').click(function(e) {
        $('#imgmodal img').attr('src', $(this).attr('data-img-src'));
    });
});
