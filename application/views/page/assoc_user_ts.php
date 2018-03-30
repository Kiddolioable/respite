<?php
$session = Session::instance();
$user_id = $session->get('user_id');
if(isset($user_id) && $user_id != ""){ ?>
<!-- BUTTON TO APPLY CHECKBOX DELETIONS, classes are assigned AFTER Datatable loads (see script.js) -->
<button id="buttonMultDel" type="button" class="hidden" data-toggle="modal" data-target="#deleteMultipleUsersUTSModal">
    <i id="buttonMultDelIcon" class="" aria-hidden="true"></i>
</button>
    
    <!-- Add new user(s) to server form -->
    <br><br>
    
    <div align="center" class="panel panel-success" style="margin-left: 40px; margin-right: 40px;">
        <div class="panel-heading">
            <form class="form-inline" method="post" id="addAccountUTS-aesthetic">
                <div class="form-group addAccountUTS-label">
                    <label class="form-label" for="exampleInputName2">User ID</label><br>
                    <input type="number" class="form-control" id="useridUTSInsert" name="useridUTSInsert" placeholder="User ID">
                </div>
                <div class="form-group addAccountUTS-label">
                    <label class="form-label" for="exampleInputName2">Terminal Server ID</label><br>
                    <input type="number" class="form-control" id="tsidUTSInsert" name="tsidUTSInsert" disabled="disabled" placeholder="Terminal Server ID" value="8">
                </div>
                <div class="form-group addAccountUTS-label">
                    <label for="exampleInputEmail2">Deleted</label><br>
                    <select class="form-control" id="deletedUTSInsert" name="deletedUTSInsert">
                        <option value="1">Deleted - 1</option>
                        <option value="0">NOT Deleted - 0</option>
                    </select>
                </div>
                <div class="form-group addAccountUTS-label">
                    <label for="exampleInputEmail2">Accesses</label><br>
                    <input type="number" class="form-control" id="accessesUTSInsert" name="accessesUTSInsert" value="0" placeholder="Accesses">
                </div>
    
                <!-- Submit new user to server button -->
                <div style="padding-top: 4.5px" class="form-group addAccountUTS-label">
                    <br>
                    <button type="button" class="btn btn-success" onclick="addAccountUTS()">Add User(s)</button>
                </div>
            </form>
        </div>
    </div
    <!-- END Add new user(s) to server form -->

    <!-- Special edit (Change all "deleted" values to 0 or 1 at once of a specified user id) -->
        <div class="rightSideWidgets" align="right" id="specialEdit">
            <!-- TOOLTIP -->
            <button type="button" id="special-edit-info" data-toggle="tooltip" data-placement="left"
                    title="Enables a form that allows changing the current 'Deleted' status of specified User ID to the specified 'Deleted' status">
                <i class="fa fa-question-circle" aria-hidden="true"></i>
            </button>
            <button id="specialEditToggleButton" type="submit" onclick="arrowToggle('specialEditDiv', 'specialEditIcon')">
                    <strong class="shouldClickMe">Additional Editing</strong>
                <i id="specialEditIcon" class="fa fa-caret-down" aria-hidden="true"></i>
            </button>
                <div style="display: none;" id="specialEditDiv" align="right" class="panel panel-success">
                    <div class="panel-heading">
                        <!-- More info on what this does in tooltip for user -->
                        <!-- Form -->
                        <form class="form-inline" method="post" id="specialeditUTS-aesthetic">
                            <div class="form-group specialeditUTS-label" align="left">
                                <!-- User ID -->
                                <label>User ID</label><br>
                                <input type="number" class="form-control" id="useridspecialeditUTSInsert" name="useridspecialeditUTSInsert" placeholder="User ID to modify">
                            </div>
                            
                            <div class="form-group specialeditUTS-label" align="left">
                                <!-- Deleted -->
                                <label>Deleted Status</label><br>
                                <select class="form-control" id="deletedspecialeditUTSInsert" name="deletedspecialeditUTSInsert">
                                    <option value="1">Deleted - 1</option>
                                    <option value="0">NOT Deleted - 0</option>
                                </select>
                                <br>
                            </div>
                            
                            <!-- Apply button -->
                            <div class="form-group specialeditUTS-label" align="left" style="padding-top: 25px; padding-right: 28px">
                                <button type="button" class="btn btn-success"
                                                 onclick="special_edit_modal_dialog('Confirm Modification', 'Confirm modifications to user(s)?')">
                                    Apply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
        </div><br>
    <!-- END Special edit (Change all "deleted" values to 0 or 1 at once of a specified user id) -->

<!--    <!-- Advanced Search -->
<!--    <div align="right" class="rightSideWidgets" id="advancedSearch">-->
<!--        <!-- TOOLTIP -->
<!--        <button type="button" id="advanced-search-info" data-toggle="tooltip" data-placement="left"-->
<!--                title="Enables a form that allows for cross-searching IDs with names. TIP: load the full table with 'Enable Full Table'-->
<!--                    for complete results">-->
<!--            <i class="fa fa-question-circle" aria-hidden="true"></i>-->
<!--        </button>-->
<!--        <button id="advancedSearchToggleButton" type="button" onclick="arrowToggle('advancedSearchDiv', 'advancedSearchIcon')">-->
<!--                <strong class="shouldClickMe">Advanced Search</strong>-->
<!--            <i id="advancedSearchIcon" class="fa fa-caret-down" aria-hidden="true"></i>-->
<!--        </button>-->
<!--        <div id="advancedSearchDiv" align="center" style="display: none" class="panel panel-success">-->
<!--            <div class="panel-heading">-->
<!--                <!-- Form -->
<!--                <form class="form-inline" method="post" id="advancedSearch-aesthetic">-->
<!--                    <div class="form-group advancedSearch-label" align="left">-->
<!--                        <!-- User ID -->
<!--                        <label>User ID</label><br>-->
<!--                        <input type="number" class="form-control" id="useridInsertAS" name="useridInsertAS" placeholder="User ID">-->
<!--                    </div>-->
<!--    -->
<!--                    <div class="form-group advancedSearch-label" align="left">-->
<!--                        <!-- Name -->
<!--                        <label>Name</label><br>-->
<!--                        <input type="text" class="form-control" id="nameInsertAS" name="nameInsertAS" placeholder="Name">-->
<!--                        <br>-->
<!--                    </div>-->
<!--                    -->
<!--                    <div class="form-group advancedSearch-label" align="left">-->
<!--                        <!-- Login -->
<!--                        <label>Login</label><br>-->
<!--                        <input type="text" class="form-control" id="loginInsertAS" name="loginInsertAS" placeholder="Login">-->
<!--                        <br>-->
<!--                    </div>-->
<!---->
<!--                    <div class="form-group advancedSearch-label" align="left">-->
<!--                        <!-- Company -->
<!--                        <label>Company</label><br>-->
<!--                        <input type="text" class="form-control" id="companyInsertAS" name="companyInsertAS" placeholder="Company">-->
<!--                        <br>-->
<!--                    </div>-->
<!--                    -->
<!--    -->
<!--                    <!-- Apply button -->
<!--                    <div class="form-group advancedSearch-label" align="left" style="padding-top: 25px; padding-right: 28px">-->
<!--                        <button type="button" class="btn btn-success" onclick="reloadOnClick(); advancedSearchTable();">-->
<!--                            <i class="fa fa-search" aria-hidden="true"></i> Search-->
<!--                        </button>-->
<!--                    </div>-->
<!--                </form>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div><br>-->
<!--    <!-- END Advanced Search -->

   

    <!-- Toggle Full Table -->
    <div align="right" class="rightSideWidgets" id="loadFullTable" style="margin-left: 1350px">
        <!-- TOOLTIP -->
        <button type="button" id="table-toggle-info" data-toggle="tooltip" data-placement="left"
                    title="Toggle between 'Quick' or 'Full' table. NOTE: 'Full' table loads all data from the database into the table but requires a few seconds to do">
            <!-- Enable/Disable full/quick table -->
             <form method="post">
                <select class="form-control" id="tableModeInsert" name="tableModeInsert" onchange="fullTableLoadToggle(); reloadOnClick()">
                    <option value="full-table">Full Table</option>
                    <option value="quick-table">Quick Table</option>
                </select>
             </form>
        </button>
    </div><br>
    <!-- END Toggle Full Table -->

    <!-- Server information table, downloading from MySQL server @ service_outsourcing > Tables > terminal_server -->
    <div style="width: 95%; margin: auto">
        <table id="userUTSTable" class="table table-bordered" style="width: 100%" cellspacing="0">
            <thead>
            <tr>
                <th>
                    <strong>ID</strong>
                </th>
                <th>
                    <strong>User ID</strong>
                </th>
                <th>
                    <strong>Name</strong>
                </th>
                <th>
                    <strong>Username</strong>
                </th>
                <th>
                    <strong>Company Name</strong>
                </th>
                <th>
                    <strong>Terminal Server ID</strong>
                </th>
                <th>
                    <strong>Deleted</strong>
                </th>
                <th>
                    <strong>Accesses</strong>
                </th>
                <th>
                    <strong>Creation Date</strong>
                </th>
                <th>
                    <!-- Modify/Delete icons -->
                </th>
            </tr>
            </thead>

            <tbody>
            <!-- Loads table into page -->
            <?php

            include "db_connect.php";

            $log = Log::instance();
            $session = Session::instance();
            $tableToLoad = $session->get('tableToLoad');
            $limit=$session->get('limit');
//            $isASearching=$session->get('isASearching');
            
                $mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME_OS);

                //If cannot connect to MySQL table
                if ($mysqli->connect_error) {
                    $log->add(Log::ERROR, 'Error : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
                    die('Error : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
                } else {
                    //Selecting * from a joined view
                    $results = $mysqli->query("SELECT * FROM intrasix.ts_intrasix_users ORDER BY id DESC " . $limit);

                    //Fetching row
                    while ($row = $results->fetch_assoc()) {

                        $id_UTS = $row['id'];
                        $datetime_UTS = $row['ts'];


                        ?>
                        <!-- Displaying data to table -->
                        <tr align="left">
                            <td> <?php echo $id_UTS; ?> </td>
                            <td> <?php echo $row['id_utente']; ?> </td>
                            <td> <?php echo $row['id_ts']; ?> </td>
                            <td> <?php echo $row['nome']; ?> </td>
                            <td> <?php echo $row['login']; ?> </td>
                            <td> <?php echo $row['nome_ditta']; ?> </td>
                            <td> <?php echo $row['cancellato']; ?> </td>
                            <td> <?php echo $row['accessi']; ?> </td>
                            <td> <?php echo $datetime_UTS; ?> </td>


                            <td style="padding-left: 50px; padding-right: 50px;" align="center">

                                <!-- Delete button -->
                                <button type="button"
                                        class="btn btn-sm btn-danger btn-delete"
                                        data-toggle="modal"
                                        data-target="#deleteUserUTSModal"
                                        onclick="confirmDeleteUTS (<?php echo $id_UTS; ?>,
                                            '<?php echo $row['id_utente']; ?>')">
                                    <i class="fa fa-trash" aria-hidden="true">
                                    </i>
                                </button>

                                <!-- Modify button -->
                                <button type="button"
                                        class="btn btn-sm btn-warning btn-edit"
                                        data-toggle="modal"
                                        data-target="#editUserUTSModal"
                                        onclick="confirmModifyUTS    (<?php echo $id_UTS ?>,
                                            '<?php echo $row['id_utente']; ?>',
                                            '<?php echo $row['id_ts']; ?>',
                                            '<?php echo $row['cancellato']; ?>',
                                            '<?php echo $row['accessi']; ?>')">
                                    <i class="fa fa-pencil" aria-hidden="true">
                                    </i>
                                </button>

                                <!-- Delete checkboxes -->
                                <input id="prr_<?php echo $id_UTS; ?>"
                                       onclick="addCheckedIDsToArray(<?php echo $id_UTS; ?>, 'buttonMultDel')"
                                       style="float: right" type="checkbox" id="deleteUserUTSradio"
                                       name="deleteUserUTSradio" value="<?php $row['id'] ?>">
                            </td>

                        </tr>
                        <?php
                    }


                    // Frees the memory associated with a result
                    $results->free();

                    // close connection
                    $mysqli->close();

                }
            ?>
            </tbody>
        </table>
    </div>
    <!-- END Server information table  -->

    <!-- Special Edit Modal -->
    <div id="special_edit_modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <!-- Title -->
                    <h4 class="modal-title" id="special_edit_modal_title"></h4>
                </div>
                <!-- Body -->
                <div class="modal-body">
                    <p id="special_edit_modal_body"> </p>
                </div>
                <div class="modal-footer">
                    <button type="button"  class="btn btn-default" data-dismiss="modal" onclick="reloadOnClick()">Cancel</button>
                    <button type="button"  class="btn btn-primary" data-dismiss="modal" onclick="specialEditUTS()">Confirm</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- Special Edit Modal -->

    <!-- Deletion Modal -->
    <div class="modal fade" id="deleteUserUTSModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteUserUTSModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="deleteUserUTSLabel">User Deletion</h4>
                </div>
                <div class="modal-body">
                    Confirm deletion of user number <strong><span id="user_id_UTS_to_delete"></span></strong>
                </div>
                <input type="hidden" id="id_UTS_to_delete">
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deleteUserUTS()"><i class="fa fa-trash" aria-hidden="true" ></i> Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Deletion Modal -->

    <!-- Multiple Deletion Modal -->
    <div class="modal fade" id="deleteMultipleUsersUTSModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteMultipleUsersUTSModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="deleteMultipleUsersUTSModalLabel">User Deletion</h4>
                </div>
                <div class="modal-body">
                    Confirm deletion of selected user(s)?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="multipleDeleteUTS()"><i class="fa fa-trash" aria-hidden="true" ></i> Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Multiple Deletion Modal -->

    <!-- Editing Modal -->
    <div class="modal fade" id="editUserUTSModal" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editUserUTSModalLabel">Table edit</h4>
                </div>
                <div class="modal-body">
                    <input class="form-control" placeholder="ID" type="hidden" maxlength="48" id="id_UTS_to_modify">
                    <strong>User ID: </strong>
                    <input class="form-control" placeholder="User ID" type="text" maxlength="48" id="user_id_UTS_to_modify">
                    <br>
                    <strong>Terminal Server ID: </strong>
                    <input class="form-control" placeholder="Terminal Server ID" type="text" maxlength="48" id="ts_id_UTS_to_modify">
                    <br>
                    <strong>Deleted: </strong>
                    <select class="form-control" id="deleted_UTS_to_modify" name="deleted_UTS_to_modify">
                        <option value="1">Deleted - 1</option>
                        <option value="0">NOT Deleted - 0</option>
                    </select><br>
                    <strong>Accesses: </strong>
                    <input class="form-control" placeholder="Description" type="text" maxlength="48" id="accesses_UTS_to_modify">
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="modifyUserUTS()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Modal Edit -->

<!-- ERROR 404 PAGE (If no permission to view) -->
<?php } else { ?>
    <div id="error-404">
        <meta http-equiv="refresh" content="0; url=/errors/404.php" />
    </div>
<?php } ?>
    
<div style="padding: 80px">
    
</div>
