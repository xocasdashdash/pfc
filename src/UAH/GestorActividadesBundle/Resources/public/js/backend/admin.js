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
            $id_enrollment = $(value).find(".check_reconocer").val();
            $number_of_credits = $(value).find(".numero_de_creditos").val();
            $fila = [];
            $fila['id'] = $id_enrollment;
            $fila['numero_de_creditos'] = $number_of_credits;
            $filas_values[index] = $fila;
        });
    });
});