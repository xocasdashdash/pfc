/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(window).load(function() {
    $('#btn_show_all').on('click', function() {
        window.location.href = Routing.generate('uah_gestoractividades_application_index', {filter: 'all'});
    });
    $('#btn_show_pending').on('click', function() {
        window.location.href = Routing.generate('uah_gestoractividades_application_index');
    });
    $('#btn_erase').on('click', function(evt) {
        $id = getSelectedIds();
        if ($id.length === 1) {
            bootbox.confirm("¿Estás seguro que quieres eliminar este justificante?", function(result) {
                if (result) {
                    $.ajax({
                        type: "POST",
                        url: Routing.generate('uah_gestoractividades_application_delete', {"id": $id[0]}),
                        statusCode: {
                            200: function(data) {
                                bootbox.alert("Justificante borrado!", function() {
                                    location.reload(true);
                                });
                            },
                            400: function(data) {
                                bootbox.alert("Error al borrar el justificante: <br>" + data.responseJSON.message, function() {
                                    location.reload(true);
                                });
                            },
                        }
                    });
                }
            });
        } else {
            bootbox.alert("Selecciona un justificante a la vez");
        }
    });

    $('#btn_archive').on('click', function(evt) {
        $id = getSelectedIds();
        if ($id.length === 1) {
            bootbox.confirm("¿Estás seguro que quieres archivar este justificante?", function(result) {
                if (result) {
                    $.ajax({
                        type: "POST",
                        url: Routing.generate('uah_gestoractividades_application_archive', {"id": $id[0]}),
                        statusCode: {
                            200: function(data) {
                                bootbox.alert("Justificante archivado!", function() {
                                    location.reload(true);
                                });
                            },
                            400: function(data) {
                                bootbox.alert("Error al archivar el justificante: <br>" + data.responseJSON.message, function() {
                                    location.reload(true);
                                });
                            },
                        }
                    });
                }
            });
        } else {
            bootbox.alert("Selecciona un justificante a la vez");
        }
    });

    function getSelectedIds() {
        var $checked_rows = [];
        $filas_seleccionadas = $('.tbl_applications input[type=checkbox]:checked').closest('tr');
        $filas_seleccionadas.map(function(index, value) {
            $id_application = $(value).find("input[type=checkbox]").val();
            $checked_rows.push($id_application);
        });
        return $checked_rows;
    }
});
