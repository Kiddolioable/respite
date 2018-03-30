//Document Ready functions run on initialization

//Toggle Datatable
$(document).ready(function(){
    $('#usersTable').DataTable();
    $('#serverTable').DataTable();
    $('#userUTSTable').DataTable();
});



//Toggle tooltips
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip()
})


//REMEMBER user selected for table load mode
$(function() {
    if (typeof(Storage) != "undefined") {
        if (sessionStorage.getItem('tableModeInsert')) {
            $("#tableModeInsert option").eq(sessionStorage.getItem('tableModeInsert')).prop('selected', true);
        }

        $("#tableModeInsert").on('change', function () {
            sessionStorage.setItem('tableModeInsert', $('option:selected', this).index());
        });
    } else{
        service_dialog("Alert", "Please enable web storage support!");
    }
});

//Hide multiple delete icon on first load
$(document).ready(function (){
    document.getElementById('buttonMultDel').className = "btn btn-danger btn-sm";
    document.getElementById('buttonMultDelIcon').className = "fa fa-trash fa-2x";
    $('#buttonMultDel').toggle();
});

//Show/Hide special edit form
function arrowToggle(shown, icon){
    if(document.getElementById(shown).style.display == "none") {
        document.getElementById(shown).style.display = "block";
        document.getElementById(icon).className = "fa fa-caret-up";
    }
    else if(document.getElementById(shown).style.display == "block") {
        document.getElementById(shown).style.display = "none";
        document.getElementById(icon).className = "fa fa-caret-down";
    }
}

//Show service modals with custom messages and titles
function service_dialog(title, message) {
    $('#service_modal_dialog_title').text(title);
    $('#service_modal_dialog_body').text(message);
    $('#service_modal_dialog').modal('show');
}

//Display special edit modal
function special_edit_modal_dialog(title, message){
    $('#special_edit_modal_title').text(title);
    $('#special_edit_modal_body').text(message);
    $('#special_edit_modal').modal('show');
}

//Reloads page when called
function reloadOnClick(){
    location.reload();
}

/* * * * * * * * * * * * * * * * * * *
 *      USER CONFIGURATION TABLE     *
 * * * * * * * * * * * * * * * * * * */

//Confirm deletion
function confirmMessage(id, username, nome){

    $('#account-id-delete').val(id);
    $('#account-username-delete').text(username);
}

//Confirm edit
function confirmMessagePlus(id,
                            username,
                            name,
                            surname) {
    $('#id_to_modify').val(id);
    $('#username_to_modify').val(username);
    $('#name_to_modify').val(name);
    $('#surname_to_modify').val(surname);
}

//Delete user from table
function deleteAccount(){
    var idD = $('#account-id-delete').val();
    var usernameD = $('#account-username-delete').text();

    $.ajax({
        url: "/Rpc/deleteAccount",
        method: "POST",
        data: { "id_acc" : idD,
                "username" : usernameD },
        success: function (s) {
            if(s.is_error == true){
                $('#deleteModal').modal('hide');
                service_dialog("Errore", "Cancellazione non riuscita");
            }else{
                if(s.is_admin == false){
                    $('#deleteModal').modal('hide');
                    service_dialog("Error 70", "Errore: permesso negato");
                }else {
                    $('#deleteModal').modal('hide');
                    service_dialog("Successo", "Utente cancellato");
                }
            }
        },
        dataType: "json"
    });

    //location.reload();
}

//Modify/Update table
function modificaUtente(){
    var idD = $('#id_to_modify').val();
    var usernameD = $('#username_to_modify').val();
    var passwordD = $('#password_to_modify').val();
    var nameD = $('#name_to_modify').val();
    var surnameD = $('#surname_to_modify').val();

    $.ajax({
        url: "/Rpc/modificaUtente",
        method: "POST",
        data: { "id" : idD,
            "username" : usernameD,
            "password" : passwordD,
            "nome": nameD,
            "cognome" : surnameD},
        success: function(s){
            //Show various service modals
            if (s.is_error == false){
                if (s.is_admin == true){
                    $('#editModal').modal('hide');
                    service_dialog("Success","User successfully modified");
                } else{
                    $('#editModal').modal('hide');
                    service_dialog("Error 70", "Modification failed: permission denied (error 70)");
                }
            } else{
                $('#editModal').modal('hide');
                service_dialog("Error", "Could not modify user");
            }
        },
        dataType: "json"
    });

    //location.reload();
}

//Add user
function addAccount(){
    var usernameD = $('#usernameInsert').val();
    var passwordD = $('#passwordInsert').val();
    var secretQ = $('#secretQInsert').val();
    var secretA = $('#secretAnswerInsert').val();

    $.ajax({
        url: "/Rpc/addAccount",
        method: "POST",
        data: {
            "usernameInsert" : usernameD,
            "passwordInsert" : passwordD,
            "secretQInsert" : secretQ,
            "secretAInsert" : secretA
        },
        success: function (s) {
            if (s.is_error == false){
                if (s.is_admin == true){
                    if(s.is_complete == true){
                        service_dialog("Success", "Utente aggiunto con successo");
                    } else{
                        service_dialog("Error", "Campo mancante: username/password")
                    }
                } else{
                    service_dialog("Error 70", "Operazione fallita: permesso negato (errore 70)");
                }
            } else{
                service_dialog("Error", "Operazione fallita");
            }
        },
        dataType: "json"
    });

    //location.reload();
}

/* * * * * * * * * * * * * * * * * * *
 *       TERMINAL SERVER TABLE       *
 * * * * * * * * * * * * * * * * * * */

//Confirm Deletion
function confirmServerDelete(id,
                             servName,
                             host){
    $('#serverid_to_delete').val(id);
    $('#serverName_to_delete').text(servName);
    $('#host_to_delete').text(host);
}

function confirmServerEdit(id,
                           host,
                           port,
                           servName,
                           description,
                           cancelled,
                           domain,
                           internal_ip){
    $('#serverid_to_modify').val(id);
    $('#host_to_modify').val(host);
    $('#port_to_modify').val(port);
    $('#servName_to_modify').val(servName);
    $('#description_to_modify').val(description);
    $('#cancelled_to_modify').val(cancelled);
    $('#domain_to_modify').val(domain);
    $('#internalip_to_modify').val(internal_ip);
}

//Add Server
function addServer(){
    var hostD = $('#hostInsert').val();
    var portD = $('#portInsert').val();
    var nameD = $('#nameInsert').val();
    var descriptionD = $('#descriptionInsert').val();
    var cancelledD = $('#cancelledInsert').text();
    var domainD = $('#domainInsert').text();
    var internalipD = $('#internalipInsert').val();

    $.ajax({
        url: '/Rpc/addServer',
        method: "POST",
        data: {
            "hostInsert": hostD,
            "portInsert": portD,
            "nameInsert": nameD,
            "descriptionInsert": descriptionD,
            "cancelledInsert": cancelledD,
            "domainInsert": domainD,
            "internalipInsert": internalipD
        },
        success: function(s){
            if (s.is_error == false){
                if (s.is_admin == true){
                    if (s.is_complete == true){
                        service_dialog("Success", "Server(s) successfully added");
                    } else{
                        service_dialog("Error", "Missing field(s): host/port/internal ip")
                    }
                } else{
                    service_dialog("Error 70", "Operation failed: permission denied (error 70)");
                }
            } else{
                service_dialog("Error", "Could not add server(s)");
            }
        },
        dataType: "json"
    });
}

//Delete Server
function deleteServer(){
    var idD = $('#serverid_to_delete').val();
    var servNameD = $('#serverName_to_delete').val();
    var hostD = $('#host_to_delete').val();

    $.ajax({
        url: "/Rpc/deleteServer",
        method: "POST",
        data: {
            "id" : idD,
            "nome" : servNameD,
            "host" : hostD
        },
        success: function(s){
            if (s.is_error == false){
                if (s.is_admin == true){
                    service_dialog("Success", "Server(s) successfully deleted");
                } else{
                    service_dialog("Error 70", "Operation failed: permission denied (error 70)");
                }
            } else{
                service_dialog("Error", "Could not delete server(s)");
            }
        },
        dataType: "json"
    });
}

//Modify Server
function editServer(){
    var idD = $('#serverid_to_modify').val();
    var hostD = $('#host_to_modify').val();
    var portD = $('#port_to_modify').val();
    var servNameD = $('#servName_to_modify').val();
    var descriptionD = $('#description_to_modify').val();
    var cancelledD = $('#cancelled_to_modify').val();
    var domainD = $('#domain_to_modify').val();
    var internalipD = $('#internalip_to_modify').val();

    $.ajax({
        url: "/Rpc/editServer",
        method: "POST",
        data: { "id" : idD,
            "host" : hostD,
            "port" : portD,
            "nome": servNameD,
            "descrizione" : descriptionD,
            "cancellato" : cancelledD,
            "dominio" : domainD,
            "ip_interno" : internalipD},
        success: function(s){
            //Show various service modals
            if (s.is_error == false){
                if (s.is_admin == true){
                    $('#editServerModal').modal('hide');
                    service_dialog("Success","Server successfully modified");
                } else{
                    $('#editServerModal').modal('hide');
                    service_dialog("Error 70", "Modification(s) failed: permission denied (error 70)");
                }
            } else{
                $('#editServerModal').modal('hide');
                service_dialog("Error", "Could not modify server");
            }
        },
        dataType: "json"
    });
}

/* * * * * * * * * * * * * * * * * * *
 *    USER TERMINAL SERVER TABLE     *
 * * * * * * * * * * * * * * * * * * */

//Confirm to delete user
function confirmDeleteUTS(utsid, userutsid){
    $('#id_UTS_to_delete').val(utsid);
    $('#user_id_UTS_to_delete').text(userutsid);
}

//Confirm to modify user
function confirmModifyUTS(utsid,
                          userutsid,
                          tsutsid,
                          deleteduts,
                          accessesutd){
    $('#id_UTS_to_modify').val(utsid);
    $('#user_id_UTS_to_modify').val(userutsid);
    $('#ts_id_UTS_to_modify').val(tsutsid);
    $('#delete_UTS_to_modify').val(deleteduts);
    $('#accesses_UTS_to_modify').val(accessesutd);
}

//Add user
function addAccountUTS(){
    var useridUTS = $('#useridUTSInsert').val();
    var tsidUTS = $('#tsidUTSInsert').val();
    var deletedUTS = $('#deletedUTSInsert').val();
    var accessesUTS = $('#accessesUTSInsert').val();

    $.ajax({
        url: '/Rpc/addAccountUTS',
        method: "POST",
        data: {
            "useridUTSInsert": useridUTS,
            "tsidUTSInsert": tsidUTS,
            "deletedUTSInsert": deletedUTS,
            "accessesUTSInsert": accessesUTS
        },
        success: function(s){
            if (s.is_error == false){
                if (s.is_admin == true){
                    if (s.is_complete == true){
                        service_dialog("Success", "User(s) successfully added to the terminal server");
                    } else{
                        service_dialog("Error", "Missing field: user id");
                        console.log(s.is_complete);
                    }
                } else{
                    service_dialog("Error 70", "Operation failed: permission denied (error 70)");
                }
            } else{
                service_dialog("Error", "Could not add user(s) to the terminal server");
            }
        },
        dataType: "json"
    });
}

//Delete user
function deleteUserUTS(){
    var idUTS = $('#id_UTS_to_delete').val();

    $.ajax({
        url: '/Rpc/deleteUserUTS',
        method: "POST",
        data: {
            "id" : idUTS
        },
        success: function(s){
            if (s.is_error == false){
                if (s.is_admin == true){
                    service_dialog("Success", "User successfully deleted from the terminal server");
                } else{
                    service_dialog("Error 70", "Operation failed: permission denied (error 70)");
                }
            } else{
                service_dialog("Error", "Could not delete user(s) from the terminal server");
            }
        },
        dataType: "json"
    });
}

//Modify user
function modifyUserUTS(){
    var idUTS = $('#id_UTS_to_modify').val();
    var useridUTS = $('#user_id_UTS_to_modify').val();
    var tsidUTS = $('#ts_id_UTS_to_modify').val();
    var deletedUTS = $('#deleted_UTS_to_modify').val();
    var accessesUTS = $('#accesses_UTS_to_modify').val();

    $.ajax({
        url: '/Rpc/modifyUserUTS',
        method: "POST",
        data: {
            "id" : idUTS,
            "id_utente" : useridUTS,
            "id_ts" : tsidUTS,
            "cancellato" : deletedUTS,
            "accessi" : accessesUTS
        },
        success: function(s){
            if (s.is_error == false){
                if (s.is_admin == true){
                    $('#editUserUTSModal').modal('hide');
                    service_dialog("Success", "User successfully modified in the terminal server");
                } else {
                    $('#editUserUTSModal').modal('hide');
                    service_dialog("Error 70", "Operation failed: permission denied (error 70)");
                }
            } else{
                $('#editUserUTSModal').modal('hide');
                service_dialog("Error", "Could not modify user in the terminal server");
            }
        },
        dataType: "json"
    });
}

//Special Edit
function specialEditUTS() {
    var useridUTS = $('#useridspecialeditUTSInsert').val();
    var deletedUTS = $('#deletedspecialeditUTSInsert').val();

    $.ajax({
        url: "/Rpc/specialEditUTS",
        method: "POST",
        data: {
            "useridspecialeditUTSInsert": useridUTS,
            "deletedspecialeditUTSInsert": deletedUTS
        },
        success: function (s) {
            if (s.is_error == false) {
                if (s.is_admin == true) {
                    $('#editUserUTSModal').modal('hide');
                    service_dialog("Success", "User(s) successfully modified in the terminal server");
                } else {
                    $('#editUserUTSModal').modal('hide');
                    service_dialog("Error 70", "Operation failed: permission denied (error 70)");
                }
            } else {
                $('#editUserUTSModal').modal('hide');
                service_dialog("Error", "Could not modify user(s) in the terminal server");
            }
        },
        dataType: "json"
    });
}


var arr = [];

//Add checked IDs to the array
function addCheckedIDsToArray(id, icon){

    if($('#prr_'+id).is(':checked')){
        arr.push(id);
        document.getElementById(icon).style.display = "block";
    }else{
        var index = arr.indexOf(id);
        if (index > -1) {
            arr.splice(index, 1);

        }

        if (index <= 0){
            document.getElementById(icon).style.display = "none";
        }
    }
}

//Multiple Delete
function multipleDeleteUTS(){
    var jsonString = JSON.stringify(arr);

    $.ajax({
        url: "/Rpc/multipleDeleteUTS",
        method: "POST",
        data: {
          'id' : jsonString
        },
        success: function(s){
            if (s.is_error == false){
                if (s.is_admin == true){
                    if (s.is_selected == true){
                        $('#deleteMultipleUsersUTSModal').modal('hide');
                        service_dialog("Success", "User(s) successfully deleted from the terminal server");
                    } else{
                        $('#deleteMultipleUsersUTSModal').modal('hide');
                        service_dialog("Error", "Please select at least one user to delete");
                    }
                } else{
                    $('#deleteMultipleUsersUTSModal').modal('hide');
                    service_dialog("Error 70", "Operation failed: permission denied (error 70)");
                }
            } else{
                $('#deleteMultipleUsersUTSModal').modal('hide');
                service_dialog("Error", "Could not delete user(s) from the terminal server");
            }
        },
        dataType: "json"
    });
}

//Full table load toggle
function fullTableLoadToggle(){
    var selected = $('#tableModeInsert').val();

    $.ajax({
        url: '/Rpc/fullTableLoadToggle',
        method: "POST",
        data: ({
            'selected' : selected
        }),
        dataType: "json"
    });
}

//EASTER EGG (IRON MAIDEN)
function easterEggify(idOne,
                      idTwo,
                      idThree,
                      idFour,
                      idFive,
                      idSix,
                      idSeven,
                      idEight,
                      idNine,
                      idTen){
    document.getElementById(idOne).className = "easterEggifyBody";
    document.getElementById(idTwo).className = "easterEggifyHeader";
    document.getElementById(idThree).className = "navbar easterEggifyNavbar";
    document.getElementById(idFour).className = "easterEggifyLogo";
    document.getElementById(idFive).className = "easterEggifyLogoReal";
    document.getElementById(idSix).className = "easterEggifyTemplate";
    document.getElementById(idSeven).className = "easterEggifyFooter";
    document.getElementById(idEight).className = "hidden";
    document.getElementById(idNine).className = "easterEggifyHome";
    document.getElementById(idTen).className = "carousel slide";
}
