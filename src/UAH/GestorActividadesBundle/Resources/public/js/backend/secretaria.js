$(document).ready(function() {

    $('#frm_code').on('submit', function(evt) {
        evt.preventDefault();
        $app_code = $(this).find('input[type=text]').val();
        $.ajax({
            type: "POST",
            url: Routing.generate('uah_gestoractividades_application_checkcode', {"applicationCode": $app_code}),
            statusCode: {
                200: function(data) {
                    window.location.href = data.message;
                },
                400: function(data) {
                    bootbox.alert("Error: Justificante no existe o no es válido.");
                },
            }
        });
    });
    $('#btn_validate_application').on('click', function() {
        $id = $(this).data('appId');
        bootbox.confirm("¿Estás seguro que quieres verificar este justificante?", function(result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: Routing.generate('uah_gestoractividades_application_verifyapp', {'id': $id}),
                    statusCode: {
                        200: function() {
                            bootbox.alert("Justificante verificado!");
                            location.reload;
                        },
                        400: function(data) {
                            bootbox.alert("Ha habido un error al verificar el justificante:" + data.responseJSON.message);
                        }
                    }
                });
            }
        });

    });
});        