<?php 
    $session = Session::instance();
    $user_id = $session->get('user_id');
    if(isset($user_id) && $user_id != ""){ ?>
<!-- Add a new user form -->
    <br><br>
    <div align="center" class="panel panel-success" style="margin-left: 80px; margin-right: 80px;">
        <div class="panel-heading">
            <form class="form-inline" method="post" id="adduser-aesthetic">
                <div class="form-group adduser-label">
                    <label for="exampleInputName2">Username</label><br>
                    <input type="text" class="form-control" id="usernameInsert" name="usernameInsert" placeholder="Username">
                </div>
                <div class="form-group adduser-label">
                    <label for="exampleInputName2">Name</label><br>
                    <input type="text" class="form-control" id="nameInsert" name="nameInsert" placeholder="Name">
                </div>
                <div class="form-group adduser-label">
                    <label for="exampleInputEmail2">Surname</label><br>
                    <input type="text" class="form-control" id="surnameInsert" name="surnameInsert" placeholder="Surname">
                </div>
                <div class="form-group adduser-label">
                    <label for="exampleInputEmail2">Password</label><br>
                    <input type="password" class="form-control" id="passwordInsert" name="passwordInsert" placeholder="Password">
                </div>
        
                <!-- Submit new user button -->
                <div style="padding-top: 4.5px" class="form-group addserver-label">
                    <br>
                    <button type="button" class="btn btn-success" onclick="addUser()">Add User</button>
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
                <strong>Name</strong>
            </th>
            <th>
                <strong>Surname</strong>
            </th>
            <th>
                <strong>CR_Date</strong>
            </th>
            <th>
                <strong>UP_Date</strong>
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
    
        $mysqli =  new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_TABLE);
        
        //If cannot connect to MySQL table 
        if ($mysqli->connect_error) {            
            $log->add(Log::ERROR, 'Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
            die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
        } else {


            $results = $mysqli->query("SELECT * FROM login");
    
            //Fetching row
            while($row = $results->fetch_assoc()) {

                $user = $row['username'];
                $name = $row['nome'];
                $surname = $row['cognome'];

                ?>
                
                <!-- Displaying data to table-->
                <tr align="left">
                    <td> <?php echo $row['id']; ?> </td>
                    <td> <?php echo $row['username']; ?> </td>
                    <td> <?php echo $row['nome']; ?> </td>
                    <td> <?php echo $row['cognome']; ?> </td>
                    <td> <?php echo $row['cr_date']; ?> </td>
                    <td> <?php if($row['up_date'] == ""){
                            ?><div align="center"> - </div> <?php
                        } else {
                            echo $row['up_date']; }
                        ?> </td>


                    <td style="padding-left: 50px; padding-right: 50px;" align="center">
                        <!-- Delete button -->
                        <button type="button"
                                class="btn btn-sm btn-danger btn-delete"
                                data-toggle="modal"
                                data-target="#deleteModal" onclick="confirmMessage(<?php echo $row['id']; ?>,
                                                                                  '<?php echo $row['username']; ?>',
                                                                                  '<?php echo $user; ?>')">
                            <i class="fa fa-trash" aria-hidden="true">
                            </i>
                        </button>

                        <!-- Modify button -->
                        <button type="button"
                                class="btn btn-sm btn-warning btn-edit"
                                data-toggle="modal"
                                data-target="#editModal" onclick="confirmMessagePlus(<?php echo $row['id']?>,
                                                                                    '<?php echo $user; ?>',
                                                                                    '<?php echo $name; ?>',
                                                                                    '<?php echo $surname; ?>')">
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
                <h4 class="modal-title" id="deleteModalLabel">User Deletion</h4>
            </div>
            <div class="modal-body">
                Confirm deletion of <strong><span id="nome_da_cancellare"></span></strong>
            </div>
            <input type="hidden" id="id_da_cancellare">
            <input type="hidden" id="username_da_cancellare">
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cancellaUtente()"><i class="fa fa-trash" aria-hidden="true" ></i> Confirm</button>
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
    
