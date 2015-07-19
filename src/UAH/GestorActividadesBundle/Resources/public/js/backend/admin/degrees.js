/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var edit = false;
$(function() {
  $('#form-create-degree').on('submit', function(evt) {
    evt.preventDefault();
    $('#modal-create-degree').modal('hide');
    $.ajax({
      type: 'POST',
      url: Routing.generate('uah_gestoractividades_admin_newdegree'),
      data: $(this).serialize()
    }).done(function(data) {
      if (edit) {
        bootbox.alert('Titulación actualizada');
        edit = false;
        $fila = $('#degree-' + data.degreeId);
        $fila.find('.degree-name').text($('#degree-name').val());
        $fila.find('.degree-knowledge-area').text(
          $('#knowledge-area').val());
        $fila.find('.degree-academic-code').text($('#academic-code').val());
        $fila.data('degreeType', $('.radio-div input[type=radio]:checked').val());
      } else {
        bootbox.alert('Nueva titulación añadida. Recarga para verla');
      }
      $('#form-create-degree')[0].reset();
    }).fail(function(data) {
      bootbox.alert('Problema al crear la titulación: ' + data.responseJSON.message);
    })

  });
  $('#modal-create-degree').on('hidden.bs.modal', function() {
    $('#btn_submit_degree').text('Crear');
  });
  $('table').on('click', '.update_degree', function(evt) {
    $fila = $(this).closest('tr');
    $('#degree-name').val($fila.find('.degree-name').text());
    var knowledgeArea = $fila.find('.degree-knowledge-area').text();
    $('#knowledge-area').selectpicker('val', knowledgeArea);
    //$('.selectpicker').('render');
    $('#academic-code').val($fila.find('.degree-academic-code').text());
    var tipo = $fila.data().degreeType;
    $('.radio-div input[type=radio]').filter(function() {
      return $(this).val() === tipo;
    }).prop('checked', true);
    $('#degree-id').val(evt.target.dataset.degreeId);
    $('#btn_submit_degree').text('Actualizar');
    $('#modal-create-degree').modal('show');
    edit = true;
  });
  $('table').on('click', '.delete_degree', function(evt) {
    $degree_id = $(this).data().degreeId;
    $degree_name = $(this).closest('td').siblings('.degree-name').text();
    bootbox.confirm('¿Estás seguro que quieres eliminar este grado: <br>' + $degree_name + '?', function(result) {
      if (result) {
        $.ajax({
          type: 'POST',
          url: Routing.generate('uah_gestoractividades_admin_deletedegree', {
            degree_id: $degree_id
          }),
          success: function(data) {
            bootbox.alert('Grado eliminado');
            $tbody = $(evt.delegateTarget).find('tbody');
            $(evt.target).closest('tr').remove();
            $filas = $tbody.find('tr');

            //Cambio los valores del indice
            $.each($filas, function(index, value) {
              $(value).find('.index').text(index + 1);
            });
          },
          error: function(data) {
            bootbox.alert('Ha habido un problema al eliminar el grado: ' + data.responseJSON.message);
          }
        });
      }
    });
  });
  $('.selectpicker').selectpicker();

});
