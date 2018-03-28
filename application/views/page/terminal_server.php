<?php
$session = Session::instance();
$user_id = $session->get('user_id');
if(isset($user_id) && $user_id != ""){ ?>
    <!-- Add a new server form -->
    <br><br>
    <div align="center" class="panel panel-success" style="margin-left: 60px; margin-right: 60px;">
        <div class="panel-heading">
            <form class="form-inline" method="post" id="addserver-aesthetic">
                <div class="form-group addserver-label">
                    <label class="form-label addserver-label" for="exampleInputName2">Host</label><br>
                    <input type="text" class="form-control" id="hostInsert" name="hostInsert" placeholder="Host">
                </div>
                <div class="form-group addserver-label">
                    <label class="form-label" for="exampleInputName2">Port</label><br>
                    <input type="number" class="form-control" id="portInsert" name="portInsert" placeholder="Port">
                </div>
                <div class="form-group addserver-label">
                    <label for="exampleInputName2">Server Name</label><br>
                    <input type="text" class="form-control" id="nameInsert" name="nameInsert" placeholder="Server Name">
                </div>
                <div class="form-group addserver-label">
                    <label for="exampleInputEmail2">Description</label><br>
                    <input type="text" class="form-control" id="descriptionInsert" name="descriptionInsert" placeholder="Description">
                </div>
                <div class="form-group addserver-label">
                    <label for="exampleInputEmail2">Deleted</label><br>
                    <select class="form-control" id="cancelledInsert" name="cancelledInsert">
                        <option value="0">NOT Deleted - 0</option>
                        <option value="1">Deleted - 1</option>
                    </select>
                </div>
                <div class="form-group addserver-label">
                    <label for="exampleInputEmail2">Domain</label><br>
                    <select class="form-control" id="domainInsert" name="domainInsert">
                        <option value="0">NOT In Domain - 0</option>
                        <option value="1">In Domain - 1</option>
                    </select>
                </div>
                <div class="form-group addserver-label">
                    <label for="exampleInputEmail2">Internal IP</label><br>
                    <input type="text" class="form-control" id="internalipInsert" name="internalipInsert" placeholder="Internal IP">
                </div>
    
                <!-- Submit new server button -->
                <div style="padding-top: 4.5px" class="form-group addserver-label">
                    <br>
                    <button type="button" class="btn btn-success" onclick="addServer()">Add Server</button>
                </div>
            </form>
        </div>
    </div>
    <br><br>
    <!-- END Add new server form -->


    <!-- Server information table, downloading from MySQL server @ service_outsourcing > Tables > assoc_user_ts -->
    <div style="width: 92.5%; margin: auto">
        <table id="serverTable" class="table table-bordered" style="width: 100%" cellspacing="0">
            <thead>
            <tr>
                <th>
                    <strong>ID</strong>
                </th>
                <th>
                    <strong>Host</strong>
                </th>
                <th>
                    <strong>Port</strong>
                </th>
                <th>
                    <strong>Server Name</strong>
                </th>
                <th>
                    <strong>Description</strong>
                </th>
                <th>
                    <strong>Deleted</strong>
                </th>
                <th>
                    <strong>Domain</strong>
                </th>
                <th>
                    <strong>Internal IP</strong>
                </th>
                <th>
                    <strong>Connected Users</strong>
                </th>
                <th>
                    <strong>Check</strong>
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

            $mysqli =  new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_TABLE_OS);
                
            //If cannot connect to MySQL table
            if ($mysqli->connect_error) {                
                $log->add(Log::ERROR, 'Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
                die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
            } else {


                $results = $mysqli->query("SELECT * FROM terminal_server");
        
                //Fetching row
                while($row = $results->fetch_assoc()) {

                    $host = $row['host'];
                    $name = $row['nome'];
                    $description = $row['descrizione'];
                    $epochTime = $row['check'];
                    $dateTimeNF = new DateTime("@$epochTime");
                    $dateTimeFormatted = $dateTimeNF->format('Y-m-d H:i:s');

                    ?>
                    <!-- Displaying data to table -->
                    <tr align="left">
                        <td> <?php echo $row['id']; ?> </td>
                        <td> <?php echo $row['host']; ?> </td>
                        <td> <?php echo $row['port']; ?> </td>
                        <td> <?php echo $row['nome']; ?> </td>
                        <td> <?php echo $row['descrizione']; ?> </td>
                        <td> <?php echo $row['cancellato']; ?> </td>
                        <td> <?php echo $row['dominio']; ?> </td>
                        <td> <?php echo $row['ip_interno']; ?> </td>
                        <td> <?php echo $row['collegati']; ?> </td>
                        <td> <?php echo $dateTimeFormatted; ?> </td>


                        <td style="padding-left: 50px; padding-right: 50px;" align="center">
                            <!-- Delete button -->
                            <button type="button"
                                    class="btn btn-sm btn-danger btn-delete"
                                    data-toggle="modal"
                                    data-target="#deleteServerModal" onclick="confirmServerDelete   (<?php echo $row['id']; ?>,
                                                                                                    '<?php echo $name; ?>',
                                                                                                    '<?php echo $host; ?>')">
                                <i class="fa fa-trash" aria-hidden="true">
                                </i>
                            </button>

                            <!-- Modify button -->
                            <button type="button"
                                    class="btn btn-sm btn-warning btn-edit"
                                    data-toggle="modal"
                                    data-target="#editServerModal" onclick="confirmServerEdit   (<?php echo $row['id']?>,
                                                                                                '<?php echo $host; ?>',
                                                                                                '<?php echo $row['port']; ?>',
                                                                                                '<?php echo $name; ?>',
                                                                                                '<?php echo $row['descrizione']; ?>',
                                                                                                '<?php echo $row['cancellato']; ?>',
                                                                                                '<?php echo $row['dominio']; ?>',
                                                                                                '<?php echo $row['ip_interno']; ?>') ">
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
    <!-- END Server information table  -->

    <!-- Deletion Modal -->
    <div class="modal fade" id="deleteServerModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteServerModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="deleteServerModalLabel">Server Deletion</h4>
                </div>
                <div class="modal-body">
                    Confirm deletion of <strong><span id="serverName_to_delete"></span></strong> (<span id="host_to_delete"></span>)
                </div>
                <input type="hidden" id="serverid_to_delete">
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deleteServer()"><i class="fa fa-trash" aria-hidden="true" ></i> Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Modal Delete -->

    <!-- Editing Modal -->
    <div class="modal fade" id="editServerModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editServerModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editServerModalLabel">Table edit</h4>
                </div>
                <div class="modal-body">
                    <input class="form-control" placeholder="ID" type="hidden" maxlength="48" id="serverid_to_modify">
                    <strong>Host: </strong>
                    <input class="form-control" placeholder="Host" type="text" maxlength="48" id="host_to_modify">
                    <br>
                    <strong>Port: </STRONG>
                    <input class="form-control" placeholder="Port" type="text" maxlength="48" id="port_to_modify">
                    <br>
                    <strong>Server Name: </strong>
                    <input class="form-control" placeholder="Server Name" type="text" maxlength="48" id="servName_to_modify">
                    <br>
                    <strong>Description: </strong>
                    <input class="form-control" placeholder="Description" type="text" maxlength="48" id="description_to_modify">
                    <br>
                    <strong>Deleted: </strong>
                    <select class="form-control" id="cancelled_to_modify" name="cancelled_to_modify">
                        <option value="0">NOT Deleted - 0</option>
                        <option value="1">Deleted - 1</option>
                    </select>
                    <br>
                    <strong>Domain: </strong>
                    <select class="form-control" id="domain_to_modify" name="domain_to_modify">
                        <option value="0">NOT In Domain - 0</option>
                        <option value="1">In Domain - 1</option>
                    </select>
                    <br>
                    <strong>Internal IP: </strong>
                    <input class="form-control" placeholder="Internal IP" type="text" maxlength="48" id="internalip_to_modify">
                    <br>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="editServer()">Save changes</button>
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
    
