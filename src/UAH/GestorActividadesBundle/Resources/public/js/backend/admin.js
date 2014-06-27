/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function() {
    $('#btn_reconocer').on('click', function(evt) {
        $filas_seleccionadas = $('#tbl_inscritos :checked').closest('tr');
        $filas_values = [];
        $filas_seleccionadas.map(function(index, value) {
            $id_enrollment = $(value).find("#reconocer").val();
            $number_of_credits = $(value).find("#number_of_credits").val();
            $fila = [];
            $fila['id'] = $id_enrollment;
            $fila['number_of_credits'] = $number_of_credits;
            $filas_values[index] = $fila;
        });
    });
});