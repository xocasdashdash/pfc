/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function getSelectedIds() {
    var $activities_id = [];
    $filas_seleccionadas = $('table input[type=checkbox]:checked');
    $filas_seleccionadas.map(function(index, value) {
        $activities_id.push($(value).val());
    });
    return $activities_id;
}
$(document).ready(function() {
    console.log('ready!' + new Date());
    $('#btn_ask_approval').on('click', function() {
        $selected_activity = getSelectedIds();
        if ($selected_activity.length > 0) {
            $.ajax({
                type: "POST",
                url: Routing.generate('uah_gestoractividades_activity_askapproval'),
                data: JSON.stringify($selected_activity),
                success: function(data) {
                    bootbox.alert("Solicitud realizada", function() {
                        //location.reload(true);
                        $.each($selected_activity, function(index, value) {
                            $('#activity-' + value).find('.status').text(data.message.status);
                        });
                    });
                },
                error: function(data) {
                    bootbox.alert("Ha habido un problema :S", function() {
//                        location.reload(true);
                    });
                }
            });
        }
    });
    $('#btn_create').on('click', function() {
        location.href = Routing.generate('uah_gestoractividades_activity_create');
    });
    $('#btn_close').on('click', function() {
        $selected_activity = getSelectedIds();
        if ($selected_activity.length === 1) {
            bootbox.confirm("¿Estás seguro que quieres cerrar esta actividad?", function(result) {
                $.ajax({
                    type: "POST",
                    url: Routing.generate('uah_gestoractividades_activity_close', {'activity_id': $selected_activity}),
                    data: $selected_activity,
                    success: function(data) {
                        bootbox.alert("Actividad cerrada.", function() {
                            $tbody = $('#activity-' + $selected_activity).closest('tbody');
                            $('#activity-' + $selected_activity).remove();
                            $filas = $tbody.find('tr');
                            if ($filas.length === 1) {
                                location.reload(true);
                            }
                            $.each($filas, function(index, value) {
                                $(value).find('.index').text('#' + index + 1);
                            });
                        });
                    },
                    error: function(data) {
                        bootbox.alert("Ha habido un problema :S", function() {
                            location.reload(true);
                        });
                    }
                });
            });

        } else if ($selected_activity.length > 1) {
            bootbox.alert("Solamente puedes cerrar una actividad cada vez");
        }
    });
    $('#btn_show_all').on('click', function() {
        location.href = Routing.generate('uah_gestoractividades_profile_myactivities', {filter: 'all'});
        //location.href = Routing.generate('aaaaaa', {filter: 'all'});
    });
    $('#btn_open').on('click', function() {
        $selected_activity = getSelectedIds();
        if ($selected_activity.length === 1) {

            bootbox.confirm("¿Estás seguro que quieres abrir esta actividad?", function(result) {
                $.ajax({
                    type: "POST",
                    url: Routing.generate('uah_gestoractividades_activity_open', {'activity_id': $selected_activity}),
                    data: $selected_activity,
                    success: function(data) {
                        bootbox.alert("Actividad abierta.", function() {
                            $('#activity-' + $selected_activity).find('.status').text(data.message.status);

                        });
                    },
                    error: function(data) {
                        bootbox.alert("Ha habido un problema :S", function() {
                            location.reload(true);
                        });
                    }
                });
            });
        } else if ($selected_activity.length > 1) {
            bootbox.alert("Solamente puedes abrir una actividad cada vez");

        }
    });
});