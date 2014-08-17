/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).on('ready', function() {
    $('table').on('click', '.update_permissions', function(evt) {
//console.log(evt.target);
        $idLdap = $(evt.target).closest('tr').find('.id_ldap').data().idLdap;
        $permits = $(evt.target).closest('tr').find('select :selected').text();
        $role = $(evt.target).closest('tr').find('select :selected').val();
        //Mostrar bootbox
        bootbox.confirm('¿Estás seguro que quieres dar permisos de (' + $permits + ') a: ' + $idLdap + '?', function(result) {
            //Si dice que si, envíamos al servidor
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: Routing.generate('uah_gestoractividades_admin_updatepermissions', {identity: encodeURIComponent($idLdap), permits: $role}),
                    success: function(data) {
                        bootbox.alert(data.message);
                    },
                    error: function(data) {
                        //bootbox.alert("Ha habido un error: " + data.responseJSON.message);
                    }
                });
            }
        });
    });
    $('table').on('click', '.delete_permissions', function(evt) {
//Mostrar bootbox
//Si dice que si, envíamos al servidor
        console.log(evt.target);
        $tbody = $(evt.target).closest('tbody');
        $fila = $(evt.target).closest('tr');
        $idLdap = $(evt.target).closest('tr').find('.id_ldap').data().idLdap;
        $permits = $(evt.target).closest('tr').find('select :selected').text();
        //Mostrar bootbox
        bootbox.confirm('¿Estás seguro que quieres quitar los permisos a: ' + $idLdap + '?', function(result) {
            //Si dice que si, envíamos al servidor
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: Routing.generate('uah_gestoractividades_admin_deletepermissions', {identity: encodeURIComponent($idLdap)}),
                    success: function(data) {
                        $fila.remove();
                        $filas = $tbody.find('tr');
                        $.each($filas, function(index, value) {
                            $(value).find('.index').text(index + 1);
                        });
                        bootbox.alert(data.message);
                    },
                    error: function(data) {
                        bootbox.alert("Ha habido un error: <br>" + data.responseJSON.message);
                    }
                });
            }
        });
    });
    $('.selectpicker').selectpicker();
    $('[title!=""]').qtip({
        content: {
            title: 'Para que sirve'
        },
        position: {
            my: 'top center', // Position my top left...
            at: 'bottom center', // at the bottom right of...
            //target: $('.selector') // my target
        },
        style: {classes: 'hidden-print'},
        adjust: {
            x: 10,
            y: 10
        }

    });
    $('#btn_create_new_user').on('click', function() {

    });
    $('#form-create-new-user').on('submit', function(evt) {
        evt.preventDefault();
        $uah_name = $('#uah-name').val();
        $uah_role = $('#uah-roles :selected').val();
        $uah_role_name = $('#uah-roles :selected').text();
        $('#modal-create-user').modal('hide')

        bootbox.confirm('¿Estás seguro que quieres crear este usuario (' + $uah_name + ') con estos permisos: ' + $uah_role_name + ' ?', function(result) {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: Routing.generate('uah_gestoractividades_admin_newuser', {uah_name: encodeURI($uah_name), uah_role: $uah_role}),
                    success: function(data) {
                        bootbox.alert('Usuario creado!');
                        $('#uah-name').val('');
                        $('#uah-roles :selected').val('');
                    },
                    error: function(data) {
                        bootbox.alert('Ha habido un error al crear el usuario:' + data.responseJSON.message);

                    }
                });
            } else {
                $('#modal-create-user').modal('show');
            }
        });

    });
});