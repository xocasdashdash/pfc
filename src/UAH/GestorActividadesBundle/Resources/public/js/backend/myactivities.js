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
        $.ajax({
            type: "POST",
            url: Routing.generate('uah_gestoractividades_activity_askapproval'),
            statusCode: {
                200: function(data) {
                    bootbox.alert("Solicitud realizada", function() {
                        location.reload(true);
                    });
                },
                403: function(data) {
                    bootbox.alert("Ha habido un problema :S", function() {
                        location.reload(true);
                    });
                }
            }
        });
    });
    $('#btn_create').on('click',function(){
        location.href = Routing.generate('uah_gestoractividades_activity_create');
    });
});