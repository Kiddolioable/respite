<?php
    $session = Session::instance();
    $user_id = $session->get('user_id');
    if(isset($user_id) && $user_id != ""){ ?>
<!-- Add a new user form -->
    <br><br>
    <div align="center" class="panel panel-success" style="margin-left: 80px; margin-right: 80px;">
        <div class="panel-heading">
            <form class="form-inline" method="post" id="addAccount-aesthetic">
                <div class="form-group addAccount-label">
                    <label for="exampleInputName2">Username</label><br>
                    <input type="text" class="form-control" id="usernameInsert" name="usernameInsert" placeholder="Username">
                </div>
                <div class="form-group addAccount-label">
                    <label for="exampleInputEmail2">Password</label><br>
                    <input type="password" class="form-control" id="passwordInsert" name="passwordInsert" placeholder="Password">
                </div>
                <div class="form-group addAccount-label">
                    <label for="exampleInputEmail2">Domanda Segreta</label><br>
                    <input type="text" class="form-control" id="secretQInsert" name="secretQInsert" placeholder="Es. Come si chiama il tuo cane?">
                </div>
                <div class="form-group addAccount-label">
                    <label for="exampleInputEmail2">Risposta</label><br>
                    <input type="text" class="form-control" id="secretAnswerInsert" name="secretAnswerInsert" placeholder="Es. Bobby">
                </div>

                <!-- Submit new user button -->
                <div style="padding-top: 4.5px" class="form-group addserver-label">
                    <br>
                    <button type="button" class="btn btn-success" onclick="addAccount()">Aggiungi Utente</button>
                </div>
            </form>
        </div>
    </div>
    <br><br>
    <!-- END Add new user form -->


<div style="width: 90%; margin: auto">
    <!-- User information table, downloading from MySQL server -->
    <table id="usersTable" class="table table-bordered" style="width: 100%" cellspacing="0">
        <thead>
        <tr>
            <th>
                <strong>ID</strong>
            </th>
            <th>
                <strong>Username</strong>
            </th>
            <th>
                <strong>Ultimo Login</strong>
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

        $searchInfo = "";
        $log = Log::instance();

        $mysqli =  new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

        //If cannot connect to MySQL table
        if ($mysqli->connect_error) {
            $log->add(Log::ERROR, 'Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
            die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
        } else {


            $results = $mysqli->query("SELECT * FROM accounts");

            //Fetching row
            while($row = $results->fetch_assoc()) {

                ?>

                <!-- Displaying data to table-->
                <tr align="left">
                    <td> <?php echo $row['id_acc']; ?> </td>
                    <td> <?php echo $row['username']; ?> </td>
                    <td> <?php echo $row['last_login']; ?> </td>

                    <td style="width: 10%" align="center">
                        <!-- Delete button -->
                        <button type="button"
                                class="btn btn-sm btn-danger btn-delete"
                                data-toggle="modal"
                                data-target="#deleteModal" onclick="confirmMessage(<?php echo $row['id_acc']; ?>,
                                                                                  '<?php echo $row['username']; ?>')">
                            <i class="fa fa-trash" aria-hidden="true">
                            </i>
                        </button>

                        <!-- Modify button -->
                        <button type="button"
                                class="btn btn-sm btn-warning btn-edit"
                                data-toggle="modal"
                                data-target="#editModal" onclick="confirmMessagePlus(<?php echo $row['id_acc']?>,
                                                                                    '<?php echo $row['username'];?>')">
                            <i class="fa fa-pencil" aria-hidden="true">
                            </i>
                        </button>
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
<!-- END User information table  -->

<!-- Deletion Modal -->
<div class="modal fade" id="deleteModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="deleteModalLabel">Cancellazione Utente</h4>
            </div>
            <div class="modal-body">
                Confermare la cancellazione dell'utente <strong><span id="account-username-delete"></span></strong>
            </div>
            <input type="hidden" id="account-id-delete">
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deleteAccount()"><i class="fa fa-trash" aria-hidden="true" ></i> Cancella</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal Delete -->

<!-- Editing Modal -->
<div class="modal fade" id="editModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editModalLabel">User Edit</h4>
            </div>
            <div class="modal-body">
                <input class="form-control" placeholder="ID" type="hidden" maxlength="48" id="id_to_modify">
                <strong>Username: </strong>
                <input class="form-control" placeholder="Username" type="text" maxlength="48" id="username_to_modify">
                <br>
                <strong>Password: </STRONG>
                <input class="form-control" placeholder="Password" type="text" maxlength="48" id="password_to_modify">
                <br>
                <strong>Name: </strong>
                <input class="form-control" placeholder="Name" type="text" maxlength="48" id="name_to_modify">
                <br>
                <strong>Surname: </strong>
                <input class="form-control" placeholder="Surname" type="text" maxlength="48" id="surname_to_modify">
                <br>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="modificaUtente()">Save changes</button>
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
