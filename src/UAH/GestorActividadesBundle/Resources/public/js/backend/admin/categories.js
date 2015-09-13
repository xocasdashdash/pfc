/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var edit = false;
$(function() {
  $('#form-create-category').on('submit', function(evt) {
    evt.preventDefault();
    regex = /^[a-zA-Z\ \'\u00e1\u00e9\u00ed\u00f3\u00fa\u00c1\u00c9\u00cd\u00d3\u00da\u00f1\u00d1\u00FC\u00DC]+$/; ///^[A-Za-z0-9][A-Za-z0-9 ]*$/;
    if (!regex.test($('#category-name').val().trim())) {
      bootbox.alert('El nombre no puede estar vacio');
    } else {
      $('#modal-create-category').modal('hide');
      $.ajax({
        type: 'POST',
        url: Routing.generate('uah_gestoractividades_admin_newcategory'),
        data: $(this).serialize()
      }).done(function(data) {
        if (edit) {
          bootbox.alert('Categoría actualizada');
          edit = false;
          $fila = $('#category-' + data.categoryId);
          $fila.find('.category-name').text($('#category-name').val());
          $fila.find('.category-knowledge-area').text($('#knowledge-area').val());
        } else {
          bootbox.alert('Nueva categoría añadida. Recarga para verla.');
        }
        edit = false;
        $('#form-create-category')[0].reset();
      }).fail(function(data) {
        bootbox.alert('Problema al crear la categoría: ' + data.responseJSON.message, function() {
          $('#modal-create-category').modal('show');
        });
      });
    }
  });

  $('#modal-create-category').on('hide.bs.modal', function(evt) {
    $('#btn_submit_category').text('Crear');
  });

  $(document).keydown(function(evt) {
    if (event.which === 27) {
      $('#modal-create-category').modal('hide');
      $('#form-create-category')[0].reset();
      $('#parent-category').selectpicker('val', null);
      if (edit) {
        edit = false;
      }
    }
  });

  $('#close_modal').on('click', function() {
    $('#form-create-category')[0].reset();
    edit = false;
    $('#parent-category').selectpicker('val', null);
  });
  $('#modal-create-category').on('hidden.bs.modal', function() {
    edit = false;
  });
  $('#modal-create-category').on('show.bs.modal',function(evt){
      if(edit === false){
        $('#form-create-category')[0].reset();
      }      
  });

  $('table').on('click', '.update_category', function(evt) {
    $fila = $(this).closest('tr');
    $('#category-name').val($fila.find('.category-name').text());
    $category_parent = $fila.find('.category-parent');
    if ($category_parent !== '-') {
      $('#parent-category').selectpicker('val', $category_parent.data().parentCategoryId);
    }
    $('#category-id').val(evt.target.dataset.categoryId);
    $('#btn_submit_category').text('Actualizar');
    edit = true;
    $('#modal-create-category').modal('show');

  });
  $('table').on('click', '.delete_category', function(evt) {
    $category_id = $(this).data().categoryId;
    $category_name = $(this).closest('td').siblings('.category-name').text();
    bootbox.confirm('¿Estás seguro que quieres eliminar esta categoria: <br>' + $category_name + '?', function(result) {
      if (result) {
        $.ajax({
          type: 'POST',
          url: Routing.generate('uah_gestoractividades_admin_deletecategory', {
            category_id: $category_id
          })
        }).done(function(data) {
          bootbox.alert('Categoría eliminada');
          $(evt.target).closest('tr').find('.category-status').text(data.message);
        }).fail(function(data) {
          bootbox.alert('Ha habido un problema al eliminar la categoría: ' + data.responseJSON.message);
        });
      }
    });
  });
  $('.selectpicker').selectpicker();
});
