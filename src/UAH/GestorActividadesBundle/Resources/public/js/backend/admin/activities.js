/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(window).load(function() {
    function getSelectedIds() {
        var $activities_id = [];
        $filas_seleccionadas = $('table input[type=checkbox]:checked');
        $filas_seleccionadas.map(function(index, value) {
            $activities_id.push($(value).val());
        });
        return $activities_id;
    }
    $('#btn_show_all_activities').on('click', function() {
        $url = Routing.generate('uah_gestoractividades_admin_activities', {filter: 'all'});
        location.href = $url;
    });
    $('#btn_approve_selected').on('click', function() {
        $activities_id = getSelectedIds();
        $.ajax({
            type: "POST",
            url: Routing.generate('uah_gestoractividades_admin_approve'),
            data: JSON.stringify($activities_id),
            success: function(data) {
                bootbox.alert("Actividades aprobadas", function() {
                    //location.reload(true);
                    $tbody = $('#activity-' + $activities_id[0]).closest('tbody');
                    $filas = $tbody.find('tr');
                    if ($filas.length === 1) {
                        location.reload(true);
                    }
                    //Quito las filas
                    $.each($activities_id, function(index, value) {
                        $('#activity-' + value).remove();
                    });
                    //Cambio los valores del indice
                    $.each($filas, function(index, value) {
                        $(value).find('.index').text(index + 1);
                    });
                });
            },
            error: function(data) {
                bootbox.alert("Ha habido un problema :S", function() {
//                        location.reload(true);
                });
            }
        });
    });
    $('#btn_print_pending_report').on('click', function() {

        var frame = $('<iframe>', {'src': Routing.generate('uah_gestoractividades_admin_printpending')}).hide();
        $('body').append(frame);
    });
    $('.filter-csv').on('click', 'a', function() {
        $filter = this.dataset.filter;
        console.log("Filter:" + $filter);
        var frame = $('<iframe>', {'src': Routing.generate('uah_gestoractividades_admin_exportactivities', {filter: $filter})}).hide();
        $('body').append(frame);
    });

});