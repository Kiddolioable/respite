<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rpc extends Controller
{

    private $DB_HOST = "172.16.20.167";
    private $DB_USER = "root";
    private $DB_PASS = "sis";
    private $DB_TABLE = "crm2016";
    private $DB_TABLE_OS = "service_outsourcing";
    private $DB_TABLE_SIX = "intrasix";

    /* * * * * * * * * * * * * * * * * * *
     *      USER CONFIGURATION TABLE     *
     * * * * * * * * * * * * * * * * * * */

    //Allows client to add user to table if is logged on the server
    public function action_addUser()
    {
        $session = Session::instance();
        $user_id = $session->get('user_id');
        $user_name = $session->get('user_name');

        $username_inserted = $_POST['usernameInsert'];
        $password_inserted = $_POST['passwordInsert'];
        $name_inserted = $_POST['nameInsert'];
        $surname_inserted = $_POST['surnameInsert'];

        if ($name_inserted == "") {
            $name_inserted = "unnamed_user";
        }

        $ret = new stdClass();
        $ret->is_error = false;
        $ret->is_admin = false;
        $ret->is_complete = false;

        if (isset($user_id) && $user_id != "") {

            //Giving admin perms
            $ret->is_admin = true;
            //Connect to database
            $mysqli = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $this->DB_TABLE);
            //If connection failed
            if ($mysqli->connect_error) {

                $ret->is_error = true;
                $log = Log::instance();
                $log->add(Log::ERROR, "Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);

                die("Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);


            } else {
                if (($ret->is_admin == true) && (isset($username_inserted) && $username_inserted != "") && (isset($password_inserted) && $password_inserted != "")) {

                    $ret->is_complete = true;

                    if ($ret->is_complete == true) {

                        //Add new user to database
                        $mysqli->query("INSERT INTO login (username,
                                                               nome,
                                                               cognome,
                                                               password)
                                                       VALUES ('$username_inserted',
                                                               '$name_inserted',
                                                               '$surname_inserted',
                                                               sha1('$password_inserted'))");
                        $id = $mysqli->insert_id;
                        $logTemp = Log::instance();

                        //Adds a number to unnamed users
                        if ($name_inserted == "unnamed_user") {
                            $name_inserted = "$name_inserted" . $id;
                            $mysqli->query("UPDATE login SET nome= '" . $name_inserted . "' WHERE id= $id");
                        }

                        //Logs user additions
                        $logTemp->add(Log::INFO, "User " . "$user_name" . " (" . $user_id . ") has added user " . $username_inserted . " (" . $id . ") to the database");
                    }
                }
            }
            //Close connection
            $mysqli->close();
        } else {

        }

        $this->response->body(json_encode($ret));
    }

    //Deletes user from table if client is logged on the server
    public function action_cancellaUtente()
    {
        $session = Session::instance();
        $user_id = $session->get('user_id');
        $user_name = $session->get('user_name');

        $ret = new stdClass();

        $ret->is_error = false;
        $ret->is_admin = false;

        if (isset($user_id) && $user_id != "") {
            //Giving admin perms
            $ret->is_admin = true;

            $log = Log::instance();

            $id = $_POST['id'];

            //If the ID is set
            if (($ret->is_admin == true) && isset($id)) {
                //Connect to database
                $mysqli = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $this->DB_TABLE);
                //If cannot connect
                if ($mysqli->connect_error) {

                    $log->add(Log::ERROR, "Connection error (" . $mysqli->connect_errno . "): " . $mysqli->connect_error);
                    $ret->is_error = true;

                    die("Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);


                } else {

                    $user_name_deleted = $_POST['username'];

                    //Delete the row
                    $mysqli->query("DELETE FROM login WHERE id= $id ");

                    //Logs user deletions
                    $log->add(Log::INFO, "User " . "$user_name" . " (" . $user_id . ") has deleted user " . $user_name_deleted . " (" . $id . ") from the database");

                }

                //Close connection
                $mysqli->close();


            }


        }

        $this->response->body(json_encode($ret));

    }

    //Allows client to modify users on table if logged on server
    public function action_modificaUtente()
    {
        $session = Session::instance();
        $user_id = $session->get('user_id');
        $user_persName = $_POST['nome'];
        $user_persSurname = $_POST['cognome'];
        $user_name = $_POST['username'];
        $user_password = $_POST['password'];

        $user_current_name = $session->get('user_name');

        $ret = new stdClass();
        $ret->is_error = false;
        $ret->is_admin = false;

        //If user is logged on
        if (isset($user_id) && $user_id != "") {
            //Giving admin perms
            $ret->is_admin = true;

            $log = Log::instance();
            $id = $_POST['id'];

            if (($ret->is_admin == true) && isset($id)) {
                //Connect to database
                $mysqli = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $this->DB_TABLE);

                //If cannot connect to server
                if ($mysqli->connect_error) {

                    $log->add(Log::ERROR, "Connection error (" . $mysqli->connect_errno . "): " . $mysqli->connect_error);
                    $ret->is_error = true;

                    die("Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);

                } else {
                    //Update table query
                    $mysqli->query("UPDATE login SET username= '" . $user_name . "',
                                                         nome= '" . $user_persName . "',
                                                         cognome= '" . $user_persSurname . "',
                                                         password= sha1('" . $user_password . "')
                                                      WHERE id= $id ");
                }

                if ($user_persName == "") {
                    $user_persName = "unnamed_user" . $id;
                    $mysqli->query("UPDATE login SET nome= '" . $user_persName . "' WHERE id= $id ");
                }

                //Logs user modifications
                $log->add(Log::INFO, "User " . "$user_current_name" . " (" . $user_id . ") has modified user " . $user_name . " (" . $id . ") in the database");
            }

            //Close connection
            $mysqli->close();

        } else {

        }

        $this->response->body(json_encode($ret));
    }


    /* * * * * * * * * * * * * * * * * * *
     *      TERMINAL SERVER TABLE        *
     * * * * * * * * * * * * * * * * * * */

    //Add Server
    public function action_addServer()
    {
        $session = Session::instance();
        $user_id = $session->get('user_id');
        $user_name = $session->get('user_name');

        $host_inserted = $_POST['hostInsert'];
        $port_inserted = $_POST['portInsert'];
        $name_inserted = $_POST['nameInsert'];
        $description_inserted = $_POST['descriptionInsert'];
        $cancelled_inserted = $_POST['cancelledInsert'];
        $domain_inserted = $_POST['domainInsert'];
        $internal_ip_inserted = $_POST['internalipInsert'];

        if ($name_inserted == "") {
            $name_inserted = "unnamed_server";
        }

        $ret = new stdClass();
        $ret->is_error = false;
        $ret->is_admin = false;
        $ret->is_complete = false;

        if (isset($user_id) && $user_id != "") {

            //Giving admin perms
            $ret->is_admin = true;
            //Connect to database
            $mysqli = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $this->DB_TABLE_OS);
            //If connection failed
            if ($mysqli->connect_error) {

                $ret->is_error = true;
                $log = Log::instance();
                $log->add(Log::ERROR, "Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);

                die("Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);


            } else {
                //If server add form is complete
                if (($ret->is_admin == true) && ((isset($host_inserted)) && $host_inserted != "") && ((isset($port_inserted) && $port_inserted != "")) && ((isset($internal_ip_inserted) && $internal_ip_inserted != ""))) {

                    $ret->is_complete = true;

                    if ($ret->is_complete == true) {

                        //Add new server to database
                        $mysqli->query("INSERT INTO terminal_server   (host,
                                                                       port,
                                                                       nome,
                                                                       descrizione,
                                                                       cancellato,
                                                                       dominio,
                                                                       ip_interno)
                                                               VALUES ('$host_inserted',
                                                                       '$port_inserted',
                                                                       '$name_inserted',
                                                                       '$description_inserted',
                                                                       '$cancelled_inserted',
                                                                       '$domain_inserted',
                                                                       '$internal_ip_inserted')");
                        $id = $mysqli->insert_id;
                        $logTemp = Log::instance();

                        //Adds a number to unnamed users
                        if ($name_inserted == "unnamed_server") {
                            $name_inserted = "$name_inserted" . $id;
                            $mysqli->query("UPDATE terminal_server SET nome= '" . $name_inserted . "' WHERE id= $id");
                        }

                        //Logs user additions
                        $logTemp->add(Log::INFO, "User " . "$user_name" . " (" . $user_id . ") has added server " . $name_inserted . " (" . $id . ") to the database");
                    }
                }
            }
            //Close connection
            $mysqli->close();
        } else {

        }

        $this->response->body(json_encode($ret));
    }

    //Delete Server
    public function action_deleteServer()
    {
        $session = Session::instance();
        $user_id = $session->get('user_id');
        $user_name = $session->get('user_name');

        $ret = new stdClass();

        $ret->is_error = false;
        $ret->is_admin = false;

        if (isset($user_id) && $user_id != "") {
            //Giving admin perms
            $ret->is_admin = true;

            $log = Log::instance();

            //If user is admin
            if ($ret->is_admin == true) {
                //Connect to database
                $mysqli = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $this->DB_TABLE_OS);
                $id = $_POST['id'];
                $ret->is_id = $id;
                //If cannot connect
                if ($mysqli->connect_error) {

                    $log->add(Log::ERROR, "Connection error (" . $mysqli->connect_errno . "): " . $mysqli->connect_error);
                    $ret->is_error = true;

                    die("Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);


                } else {
                    //If ID exists
                    if (isset($id)) {
                        $serverName_deleted = $_POST['nome'];

                        //Delete the row
                        $mysqli->query("DELETE FROM terminal_server WHERE id= $id ");

                        //Logs user deletions
                        $log->add(Log::INFO, "User " . "$user_name" . " (" . $user_id . ") has deleted server " . $serverName_deleted . " (" . $id . ") from the database");
                    }
                }

                //Close connection
                $mysqli->close();
            }
        }

        $this->response->body(json_encode($ret));
    }

    //Edit Server
    public function action_editServer()
    {
        $session = Session::instance();
        $user_id = $session->get('user_id');
        $host_to_modify = $_POST['host'];
        $port_to_modify = $_POST['port'];
        $servName_to_modify = $_POST['nome'];
        $description_to_modify = $_POST['descrizione'];
        $cancelled_to_modify = $_POST['cancellato'];
        $domain_to_modify = $_POST['dominio'];
        $internal_ip_to_modify = $_POST['ip_interno'];

        $user_current_name = $session->get('user_name');

        $ret = new stdClass();
        $ret->is_error = false;
        $ret->is_admin = false;

        //If user is logged on
        if (isset($user_id) && $user_id != "") {
            //Giving admin perms
            $ret->is_admin = true;

            $log = Log::instance();
            $id = $_POST['id'];

            //If is admin and ID is set 
            if ($ret->is_admin == true && isset($id)) {
                //Connect to database
                $mysqli = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $this->DB_TABLE_OS);

                //If cannot connect to server
                if ($mysqli->connect_error) {

                    $log->add(Log::ERROR, "Connection error (" . $mysqli->connect_errno . "): " . $mysqli->connect_error);
                    $ret->is_error = true;

                    die("Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);

                } else {
                    //Update table query
                    $mysqli->query("UPDATE terminal_server SET   host= '" . $host_to_modify . "',
                                                                     port= '" . $port_to_modify . "',
                                                                     nome= '" . $servName_to_modify . "',
                                                                     descrizione= '" . $description_to_modify . "',
                                                                     cancellato= '" . $cancelled_to_modify . "',
                                                                     dominio= '" . $domain_to_modify . "',
                                                                     ip_interno= '" . $internal_ip_to_modify . "'
                                                               WHERE id= $id ");
                }

                if ($servName_to_modify == "") {
                    $servName_to_modify = "unnamed_server" . $id;
                    $mysqli->query("UPDATE terminal_server SET nome= '" . $servName_to_modify . "' WHERE id= $id ");
                }

                //Logs user modifications
                $log->add(Log::INFO, "User " . "$user_current_name" . " (" . $user_id . ") has modified server " . $servName_to_modify . " (" . $id . ") in the database");
            }

            //Close connection
            $mysqli->close();

        } else {

        }

        $this->response->body(json_encode($ret));
    }

    /* * * * * * * * * * * * * * * * * * *
     *      TERMINAL SERVER TABLE        *
     * * * * * * * * * * * * * * * * * * */

    //Add User to Terminal Server
    public function action_addUserUTS()
    {
        $session = Session::instance();
        $user_id = $session->get('user_id');
        $user_name = $session->get('user_name');

        $user_id_UTS = $_POST['useridUTSInsert'];
        $ts_id_UTS = $_POST['tsidUTSInsert'];
        $deleted_UTS = $_POST['deletedUTSInsert'];
        $accesses_UTS = $_POST['accessesUTSInsert'];
        $datetime_UTS = date("Y-m-d H:i:s");
        $log = Log::instance();

        $ret = new stdClass();
        $ret->is_error = false;
        $ret->is_admin = false;
        $ret->is_complete = false;

        if (isset($user_id) && $user_id != "") {

            //Giving admin perms
            $ret->is_admin = true;
            //Connect to database
            $mysqli = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $this->DB_TABLE_OS);
            //If connection failed
            if ($mysqli->connect_error) {
                
                $ret->is_error = true;
                $log->add(Log::ERROR, "Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);

                die("Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);


            } else {
                //If UTS user add form is complete
                
                //Add EIGHT users to the TS AT A TIME (This is a CUSTOM request for specific functionality)
                
                if (($ret->is_admin == true) && ((isset($user_id_UTS)) && $user_id_UTS != "")) {

                    $ret->is_complete = true;

                    if ($ret->is_complete == true) {
                        
                        //Select all user ids from the table
                        $user_id_UTS_in_table = $mysqli->query("SELECT id_utente FROM assoc_user_ts WHERE id_utente= $user_id_UTS");
                            
                        //Checking if user id already exists. If yes, then put in remaining user ids to have 8 user ids with id_ts ranging from 8 to 15
                        if($user_id_UTS_in_table->num_rows >= 1){

                            $results = $mysqli->query("SELECT * FROM assoc_user_ts");
                            $count = 1;
                            
                            //Fetching all rows into $row
                            while($row = $results->fetch_assoc()){
                                $user_id_UTS_intable = $row['id_utente'];
                                $ts_id_UTS_intable = $row['id_ts'];


                                //While the ts id THAT WE'RE TRYING TO ADD == ts id IN THE TABLE then increment ts id and re check
                                while(($user_id_UTS == $user_id_UTS_intable) && $count < 8) {

                                    if($count == 1) {
                                        //Logs first addition (because loop will miss one always)
                                        $id = $mysqli->insert_id;
                                        if($id != 0){
                                            $log->add(Log::INFO, "User " . "$user_name" . " (" . $user_id . ") has added user " . $id . " to the terminal server");
                                        }
                                    }
                                    
                                    //Increment the TS ID and the count
                                    $ts_id_UTS++;
                                    $count++;

                                    if($ts_id_UTS != $ts_id_UTS_intable) {
                                        //Add new user(s) to the terminal server
                                        $mysqli->query("INSERT INTO assoc_user_ts (id_utente,
                                                                                   id_ts,
                                                                                   cancellato,
                                                                                   accessi,
                                                                                   ts)
                                                                          VALUES ('$user_id_UTS',
                                                                                  '$ts_id_UTS',
                                                                                  '$deleted_UTS',
                                                                                  '$accesses_UTS',
                                                                                  '$datetime_UTS')");
                                        
                                        $id = $mysqli->insert_id;
                                        //Logging new additions
                                        if($id != 0){
                                            $log->add(Log::INFO, "User " . "$user_name" . " (" . $user_id . ") has added user " . $id . " to the terminal server");
                                        }
                                    }
                                }

                            }
                            
                        } else{
                            for ($i = 0; $i < 8; $i++){
                                //Add new user(s) to the terminal server
                                $mysqli->query("INSERT INTO assoc_user_ts (id_utente,
                                                                           id_ts,
                                                                           cancellato,
                                                                           accessi,
                                                                           ts)
                                                                  VALUES ('$user_id_UTS',
                                                                          '$ts_id_UTS',
                                                                          '$deleted_UTS',
                                                                          '$accesses_UTS',
                                                                          '$datetime_UTS')");
    
                                $ts_id_UTS++;
    
                                $id = $mysqli->insert_id;
                                //Logs user addition(s) to server
                                $log->add(Log::INFO, "User " . "$user_name" . " (" . $user_id . ") has added user " . $id . " to the terminal server");
                            }
//                            $log->add(Log::INFO, "TEST TEST TEST TEST TEST- These are the user IDs in the variable: ".$user_id_UTS_in_table);
                        }
                    }
                } else{
                    $log->add(Log::INFO, "FLAG");
                }
            }
            //Close connection
            $mysqli->close();
            $results->free();
        } else {

        }

        $this->response->body(json_encode($ret));
    }

    //Delete user(s) from TS
    public function action_deleteUserUTS()
    {
        $session = Session::instance();
        $user_id = $session->get('user_id');
        $user_name = $session->get('user_name');

        $id = $_POST['id'];
        
        $ret = new stdClass();
        $ret->is_error = false;
        $ret->is_admin = false;

        if (isset($user_id) && $user_id != "") {

            //Giving admin perms
            $ret->is_admin = true;
            //Connect to database
            $mysqli = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $this->DB_TABLE_OS);
            //If connection failed
            if ($mysqli->connect_error) {

                $ret->is_error = true;
                $log = Log::instance();
                $log->add(Log::ERROR, "Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);

                die("Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);


            } else {
                //If is allowed
                if (($ret->is_admin == true) && isset($id)){

                    //Delete users from the terminal server
                    $mysqli->query("DELETE FROM assoc_user_ts WHERE id= $id");
                    $logTemp = Log::instance();

                    //Logs user deletions from server
                    $logTemp->add(Log::INFO, "User " . "$user_name" . " (" . $user_id . ") has deleted user " . $id . " from the terminal server");
                }
            }
            //Close connection
            $mysqli->close();
        } else {

        }

        $this->response->body(json_encode($ret));
    }
    
    //Modify user(s) in TS
    public function action_modifyUserUTS()
    {
        $session = Session::instance();
        $user_id = $session->get('user_id');
        $user_name = $session->get('user_name');

        $id = $_POST['id'];
        $user_id_UTS_to_modify = $_POST['id_utente'];
        $ts_id_UTS_to_modify = $_POST['id_ts'];
        $deleted_UTS_to_modify = $_POST['cancellato'];
        $accesses_UTS_to_modify = $_POST['accessi'];
        
        $ret = new stdClass();
        $ret->is_error = false;
        $ret->is_admin = false;

        if (isset($user_id) && $user_id != "") {

            //Giving admin perms
            $ret->is_admin = true;
            //Connect to database
            $mysqli = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $this->DB_TABLE_OS);
            //If connection failed
            if ($mysqli->connect_error) {

                $ret->is_error = true;
                $log = Log::instance();
                $log->add(Log::ERROR, "Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);

                die("Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);


            } else {
                //If is admin and ID is set
                if (($ret->is_admin == true) && isset($id)){

                    //Update users on the terminal server
                    $mysqli->query("UPDATE assoc_user_ts SET id_utente= '". $user_id_UTS_to_modify ."', 
                                                                id_ts= '". $ts_id_UTS_to_modify ."', 
                                                                cancellato= '". $deleted_UTS_to_modify ."', 
                                                                accessi= '". $accesses_UTS_to_modify ."'
                                                            WHERE id= $id");
                    $logTemp = Log::instance();

                    //Logs user modifications on server
                    $logTemp->add(Log::INFO, "User " . "$user_name" . " (" . $user_id . ") has modified user " . $id . " in the terminal server");
                }
            }
            //Close connection
            $mysqli->close();
        } else {

        }

        $this->response->body(json_encode($ret));
    }
    
    //Special Edit user(s) in TS
    public function action_specialEditUTS()
    {
        $session = Session::instance();
        $user_id = $session->get('user_id');
        $user_name = $session->get('user_name');
        
        $user_id_UTS_to_modify = $_POST['useridspecialeditUTSInsert'];
        $deleted_UTS_to_modify = $_POST['deletedspecialeditUTSInsert'];
        $log = Log::instance();
            
        $ret = new stdClass();
        $ret->is_error = false;
        $ret->is_admin = false;
        

        if (isset($user_id) && $user_id != "") {

            //Giving admin perms
            $ret->is_admin = true;
            //Connect to database
            $mysqli = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $this->DB_TABLE_OS);
            //If connection failed
            if ($mysqli->connect_error) {

                $ret->is_error = true;
                
                $log->add(Log::ERROR, "Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);

                die("Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);


            } else {
                //If is admin
                if (($ret->is_admin == true) && isset($user_id_UTS_to_modify)){
                    
                    //Getting number of rows where we have the inserted ID to modify for the "for" condition
                    $results= $mysqli->query("SELECT id_utente FROM assoc_user_ts WHERE id_utente= $user_id_UTS_to_modify");

                    for($i = 0; $i < $results->num_rows; $i++){
                        //Modify users in the terminal server
                        $mysqli->query("UPDATE assoc_user_ts SET cancellato= '" . $deleted_UTS_to_modify . "'
                                                         WHERE id_utente= $user_id_UTS_to_modify");
                        
                        //Logs user modifications on server
                        $log->add(Log::INFO, "User " . "$user_name" . " (" . $user_id . ") has modified user id" . $user_id_UTS_to_modify . " in the terminal server");
                    }
                } else{
                    
                }
            }
            //Close connection
            $mysqli->close();
        } else {

        }

        $this->response->body(json_encode($ret));
    }

    //Delete multiple users from TS
    public function action_multipleDeleteUTS()
    {
        $session = Session::instance();
        $user_id = $session->get('user_id');
        $user_name = $session->get('user_name');
        $log = Log::instance();
        
        $ret = new stdClass();
        $ret->is_error = false;
        $ret->is_admin = false;
        $ret->is_selected = false;
        
        if (isset($user_id) && $user_id != "") {

            //Giving admin perms
            $ret->is_admin = true;
            //Connect to database
            $mysqli = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $this->DB_TABLE_OS);
            //If connection failed
            if ($mysqli->connect_error) {

                $ret->is_error = true;
                $log->add(Log::ERROR, "Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);

                die("Connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);


            } else {
                $id = json_decode(stripslashes($_POST['id']), true);
                
                for($i = 0; $i < count($id); $i++){
                    //If is allowed
                    if (($ret->is_admin == true) && isset($id[$i])) {

                        $ret->is_selected = true;
                        //Delete users from the terminal server
                        $mysqli->query("DELETE FROM assoc_user_ts WHERE id= $id[$i]");

                        //Logs user deletions from server
                        $log->add(Log::INFO, "User " . "$user_name" . " (" . $user_id . ") has deleted user " . $id[$i] . " from the terminal server");
                    }
                }
            }
            //Close connection
            $mysqli->close();
        } else {

        }

        $this->response->body(json_encode($ret));
    }
    
    //Load full table toggle
    public function action_fullTableLoadToggle(){
        $session = Session::instance();
        $log = Log::instance();
        
        //Checking if user has selected option
        if(isset($_POST['selected'])){
            //Setting variable to option
            $selectedOption = $_POST['selected'];
            //Setting "LIMIT" injection to "LOAD TABLE" query
            if($selectedOption == "quick-table"){
                $session->set('limit', 'limit 100');
            } else if($selectedOption == "full-table"){
                $session->set('limit', '');
            }
        }
    }
    
}

// End Rpc
