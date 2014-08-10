/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    $('#right-block').css('height', $('#left-block').css('height'));
    $(window).resize(function() {
        $('#right-block').css('height', $('#left-block').css('height'));
    });
    $("#tbl_activities").on("click", ".unenroll", function() {
//enviar ajax a unenroll en el enrollmentController
    });
    $('#btn_generate_application').on('click', function(evt) {
        $ids = getSelectedIds();
        if ($ids.length > 0) {
            bootbox.confirm("¿Estás seguro que quieres incluir estas actividades en un justificante?", function(result) {
                if (result) {
                    $.ajax({
                        type: "POST",
                        url: Routing.generate('uah_gestoractividades_application_create'),
                        data: JSON.stringify($ids),
                        statusCode: {
                            200: function(data) {
                                bootbox.alert("Justificante generado!", function() {
                                    location.replace(Routing.generate("uah_gestoractividades_application_show", {id: data.message}));
                                });
                            },
                            400: function(data) {
                                bootbox.alert("Error al generar el justificante: <br>" + data.responseJSON.message, function() {
                                    location.reload(true);
                                });
                            },
                        }
                    });
                }
            });
        }
    });
    $('.unenroll').on('click', function(evt) {
        evt.preventDefault();
        $enrollmentId = $(this).data('enrollmentId');
        $fila = $(this).closest('tr');
        bootbox.confirm('¿Estás seguro que quieres desinscribirte de esta actividad?',
                function(result) {
                    if (result) {
                        $.ajax({
                            type: "POST",
                            url: Routing.generate('uah_gestoractividades_enrollment_unenroll', {enrollment_id: $enrollmentId}),
                            success: function(data) {

                                $tbody = $fila.closest('tbody');
                                $fila.remove();
                                $filas = $tbody.find('tr');
                                if ($filas.length === 1) {
                                    location.reload(true);
                                }
                                $.each($filas, function(index, value) {
                                    $(value).find('.index').text('#' + (index + 1));
                                });
                                bootbox.alert('Desinscrito de la actividad!');
                            },
                            error: function(data) {
                                bootbox.alert('Ha habido un error :S <br> ' + data.responseJSON.message, function() {
                                    location.reload(true);
                                });
                            }

                        });
                    }
                });
    });
    function getSelectedIds() {
        var $checked_rows = [];
        $filas_seleccionadas = $('.tbl_activities input[type=checkbox]:checked').closest('tr');
        $filas_seleccionadas.map(function(index, value) {
            $id_enrollment = $(value).find("input[type=checkbox]").val();
            $checked_rows.push($id_enrollment);
        });
        return $checked_rows;
    }
});