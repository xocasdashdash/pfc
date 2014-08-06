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
    $('#btn_ask_approval').on('click', function() {
        $selected_activities = getSelectedIds();
        if ($selected_activities.length > 0) {
            $.ajax({
                type: "POST",
                url: Routing.generate('uah_gestoractividades_activity_askapproval'),
                data: JSON.stringify($selected_activities),
                success: function(data) {
                    bootbox.alert("Solicitud realizada", function() {
                        //location.reload(true);
                        $.each($selected_activities,function(index,value){
                            $('#activity-'+value).find('.status').text(data.message.status);
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
    $('#btn_update_publish_date').on('click', function() {
        $selected_activities = getSelectedIds();
        if ($selected_activities.length > 0) {
            $.ajax({
                type: "POST",
                url: Routing.generate('uah_gestoractividades_activity_updatepublishdate'),
                data: JSON.stringify($selected_activities),
                success: function(data) {
                    bootbox.alert("Fechas actualizadas", function() {
                        location.reload(true);
                    });
                },
                error: function(data) {
                    bootbox.alert("Ha habido un problema :S", function() {
                        location.reload(true);
                    });
                }
            });
        }
    });
});