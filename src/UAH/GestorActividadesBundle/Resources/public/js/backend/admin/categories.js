/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var edit = false;
$(document).on('ready', function() {
    $('#form-create-category').on('submit', function(evt) {
        evt.preventDefault();
        $('#modal-create-category').modal('hide');
        $.ajax({
            type: 'POST',
            url: Routing.generate('uah_gestoractividades_admin_newcategory'),
            data: $(this).serialize(),
            success: function(data) {
                if (edit) {
                    bootbox.alert('Categoría actualizada');
                    edit = false;
                    $fila = $('#category-' + data.categoryId);
                    $fila.find('.category-name').text($('#category-name').val());
                    $fila.find('.category-knowledge-area').text($('#knowledge-area').val());

                } else {
                    bootbox.alert('Nueva categoría añadida. Recarga para verla.');
                }
                $('#form-create-category')[0].reset();
            },
            error: function(data) {
                bootbox.alert('Problema al crear la categoría: ' + data.responseJSON.message);
            }
        });
    });
    $('#modal-create-category').on('hidden.bs.modal', function() {
        $('#btn_submit_category').text('Crear');
    });
    $('table').on('click', '.update_category', function(evt) {
        $fila = $(this).closest('tr');
        $('#category-name').val($fila.find('.category-name').text());
        $('#knowledge-area').selectpicker('val', knowledgeArea);
        //$('.selectpicker').('render');
        $('#category-id').val(evt.target.dataset.categoryId);
        $('#btn_submit_category').text('Actualizar');
        $('#modal-create-category').modal('show');
        edit = true;
    });
    $('table').on('click', '.delete_category', function(evt) {
        $category_id = $(this).data().categoryId;
        $category_name = $(this).closest('td').siblings('.category-name').text();
        bootbox.confirm('¿Estás seguro que quieres eliminar esta categoria: <br>' + $category_name + '?', function(result) {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: Routing.generate('uah_gestoractividades_admin_deletecategory', {category_id: $category_id}),
                    success: function(data) {
                        bootbox.alert('Categoría eliminada');
                        $(evt.target).closest('tr').find('.category-status').text(data.message);                        
                    },
                    error: function(data) {
                        bootbox.alert('Ha habido un problema al eliminar la categoría: ' + data.responseJSON.message);
                    }
                });
            }
        });
    });
    $('.selectpicker').selectpicker();

});

