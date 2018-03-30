
<tbody>
            <!-- Loads table into page -->
            <?php

            include "db_connect.php";

            $searchInfo = "";
            $log = Log::instance();
            $session = Session::instance();

            $mysqli =  new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME_OS);

            //If cannot connect to MySQL table
            if ($mysqli->connect_error) {
                $log->add(Log::ERROR, 'Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
                die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
            } else {


                $results = $mysqli->query("SELECT * FROM assoc_user_ts limit 100");

                //Fetching row
                while($row = $results->fetch_assoc()) {

                    $id_UTS = $row['id'];
                    $datetime_UTS = $row['ts'];


                    ?>
                    <!-- Displaying data to table -->
                    <tr align="left">
                        <td> <?php echo $id_UTS; ?> </td>
                        <td> <?php echo $row['id_utente']; ?> </td>
                        <td> <?php echo $row['id_ts']; ?> </td>
                        <td> <?php echo $row['cancellato']; ?> </td>
                        <td> <?php echo $row['accessi']; ?> </td>
                        <td> <?php echo $datetime_UTS; ?> </td>


                        <td style="padding-left: 50px; padding-right: 50px;" align="center">

                            <!-- Delete button -->
                            <button type="button"
                                    class="btn btn-sm btn-danger btn-delete"
                                    data-toggle="modal"
                                    data-target="#deleteUserUTSModal" onclick="confirmDeleteUTS (<?php echo $id_UTS; ?>,
                                                                                                '<?php echo $row['id_utente']; ?>')">
                                <i class="fa fa-trash" aria-hidden="true">
                                </i>
                            </button>

                            <!-- Modify button -->
                            <button type="button"
                                    class="btn btn-sm btn-warning btn-edit"
                                    data-toggle="modal"
                                    data-target="#editUserUTSModal" onclick="confirmModifyUTS    (<?php echo $id_UTS?>,
                                                                                                '<?php echo $row['id_utente']; ?>',
                                                                                                '<?php echo $row['id_ts']; ?>',
                                                                                                '<?php echo $row['cancellato']; ?>',
                                                                                                '<?php echo $row['accessi']; ?>')">
                                <i class="fa fa-pencil" aria-hidden="true">
                                </i>
                            </button>

                            <!-- Delete checkboxes -->
                            <input id="prr_<?php echo $id_UTS; ?>"onclick="addCheckedIDsToArray(<?php echo $id_UTS; ?>, 'buttonMultDel')" style="float: right" type="checkbox" id="deleteUserUTSradio" name="deleteUserUTSradio" value="<?php $row['id']?>">
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