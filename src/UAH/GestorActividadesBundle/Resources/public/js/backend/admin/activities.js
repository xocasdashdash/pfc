/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).on('ready', function() {

    $('#btn_show_all_activities').on('click', function() {

    });
    $('#btn_approve_selected').on('click', function() {
    });
    $('#btn_print_pending_report').on('click', function() {

        var frame = $('<iframe>', {'src': Routing.generate('uah_gestoractividades_admin_printpending')}).hide();
        $('body').append(frame);


    });
});