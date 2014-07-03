/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    console.log("Altura:" + $("#right-block").css("height"));
    $('#right-block').css('height', $('#left-block').css('height'));
    console.log("Altura:" + $("#right-block").css("height"));
    $(window).resize(function() {
        console.log("Altura:" + $("#right-block").css("height"));
        $('#right-block').css('height', $('#left-block').css('height'));
        console.log("Altura:" + $("#right-block").css("height"));
    });

    $("#tbl_activities").on("click", ".unenroll", function() {
        //enviar ajax a unenroll en el enrollmentController
    });
});
