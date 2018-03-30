<?php

    include "db_connect.php";
    $session = Session::instance();
    $log = Log::instance();


    $current_datetime= date("Y-m-d H:i:s");

    if(isset($_POST['usernameInsertHome']) && isset($_POST['passwordInsertHome'])){
        if($_POST['usernameInsertHome'] != "" && $_POST['passwordInsertHome'] != ""){
            $username = $_POST['usernameInsertHome'];
            $password = sha1($_POST['passwordInsertHome']);
            $mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
            //IF username exists in table (where username = $username), then it gets stored into $results. Password too.
            $results = $mysqli->query("SELECT * FROM accounts WHERE username ='".$username."' AND password = '".$password."'");
            //Stores EVERY info of user
//            $results_user_persInfo = $mysqli->query("SELECT * FROM login WHERE username='".$username."' AND password= '".$password."'");

            if (isset($results) && $results->num_rows == 1){
                //fetching from $row_login
                $row_login = $results->fetch_assoc();
                //Giving/Setting user info to session
                $session->set('user_id', $row_login['id_acc']);
                $session->set('user_name', $row_login['username']);
                $session->set('user_last_login', $row_login['last_login']);
                //Sets table load limit to 100 rows as default
                $session->set('limit', 'limit 100');
                $session->set('table_load_full', '0');
                //Sets the table to load to default in assoc_user_ts.php
                $session->set('tableToLoad', 'default');

                $user_name = $session->get('user_name');
                $user_id = $session->get('user_id');

                //Log user logins
                $log->add(Log::INFO, "User ".$user_name." (".$user_id.") logged in on date ".$current_datetime);

                ?>
                <!-- Login successful notification before redirect -->
                <div class="alert alert-success" role="alert" style="margin-bottom: 0px;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <i class="fa fa-check-circle" aria-hidden="true"></i> <strong>Login riuscito</strong> -
                    Welcome, <strong><?php echo $user_name?></strong>!
                </div>
                <?php
            } else{
                ?>
                <!-- Login unsuccessful warning in case both fields are wrong -->
                <div class="alert alert-danger" role="alert" style="margin-bottom: 0px;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> <strong>Login fallito</strong> -
                    Password errato.
                </div>
           <?php }

            //Free memory in results, close sql connection
            $results -> free();
            $mysqli -> close();

        } else{
                ?>
                <!-- Login unsuccessful warning -->
                <div class="alert alert-danger" role="alert" style="margin-bottom: 0px;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> <strong>Login fallito</strong> -
                    Immettere username e password.
                </div>
        <?php }
    }

    //Declaring variables of user information
    $user_id = $session->get('user_id');
    $user_name = $session->get('user_name');
    $user_persName = $session->get('user_persName');
    $user_persSurname = $session->get('user_persSurname');
    $user_cr_date = $session->get('user_CR_Date');
    $user_up_date = $session->get('user_UP_Date');
    $user_last_login = $session->get('user_last_login');

//    $log->add(Log::INFO, $user_last_login);

?>

<!DOCTYPE html>
<html>
<head>

    <title>
        Respite Shoes
    </title>
    <!--including Javascript files -->
    <script src="/public/js/jquery-1.12.4.min.js"></script>
    <script src="/public/js/bootstrap.min.js"></script>
    <script src="/public/js/jquery.dataTables.min.js"></script>
    <script src="/public/js/dataTables.bootstrap4.min.js"></script>
    <script src="/public/js/script.js"></script>

    <!--include CSS files -->
    <link href="/public/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/public/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/public/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link href="/public/css/style.css" rel="stylesheet" type="text/css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="/public/images/favicon.ico" type="image/x-icon"/>
    <link rel="ico" href="/public/images/favicon.ico" type="image/x-icon">

    <meta charset="UTF-8">

</head>

<!-- *_*_*_*_*_* BODY *_*_*_*_*_* -->
<body id="bodyID" class="bodyPage" data-spy="scroll" data-target="#navbar-main">

<!-- Service Modal Dialog -->
<div id="service_modal_dialog" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <!-- Title -->
                <h4 class="modal-title" id="service_modal_dialog_title"></h4>
            </div>
                <!-- Body -->
            <div class="modal-body">
                <p id="service_modal_dialog_body"> </p>
            </div>
            <div class="modal-footer">
                <button type="button"  class="btn btn-success" data-dismiss="modal" onclick="reloadOnClick()">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- END Service Modal Dialog-->

<!-- SIS srl logo -->
<div id="headerID" class="headerImage">
    <br><br>
    <img id="SISlogo" src="/public/images/SISlogo.png" alt="SISlogo"/>
    <br><br><br>
</div>

<!-- EASTER EGG LOGO -->
<img id="EasterEggLogo" class="hidden" src="/public/images/Iron-Maiden-Logo.png" alt="SISlogo"/>

<!-- Navbar -->
<nav id="navbar-main" class="navbar navbar-inverse" data-spy="affix" data-offset-top="133" style="z-index: 100">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <!-- Home -->
                <li id="navTab">
                    <?php echo HTML::anchor('/', '<i class="fa fa-home" aria-hidden="true"></i>  Home', array('class' => 'navbar-text')); ?>
                </li>

                <?php
                if (isset($user_id) && $user_id != "")
                { ?>
                    <!-- User Configuration -->
                    <li id="navTab">
                        <?php echo HTML::anchor('/page/configurazioneutenti', '<i class="fa fa-user-md" aria-hidden="true"></i> Configurazione Utenti', array('class' => 'navbar-text')); ?>
                    </li id="navTab">

                    <!-- Terminal Server -->
                    <li id="navTab">
                        <?php echo HTML::anchor('/page/terminal_server', '<i class="fa fa-server" aria-hidden="true"></i>  Terminal Server', array('class' => 'navbar-text')); ?>
                    </li>

                    <!-- Assoc User TS -->
                    <li id="navTab">
                        <?php echo HTML::anchor('/page/assoc_user_ts', '<i class="fa fa-users" aria-hidden="true"></i>  User Terminal Server', array('class' => 'navbar-text')); ?>
                    </li>

                <?php } ?>


            </ul>

            <?php

                if (isset($user_id) && $user_id != "")
                {
            ?>      <!-- Strangely their positions are inverted -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Log Out -->
                        <li id="navTab">
                            <?php echo HTML::anchor('/page/logout', '<i class="fa fa-power-off" aria-hidden="true"></i>  Log Out', array('class' => 'navbar-text')); ?>
                        </li id="navTab">
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <!-- "Username Info/Hello, $user" -->
                        <li>
                            <a data-toggle="modal" data-target="#userInfoPopover" href="" class="navbar-text"><i class="fa fa-user" aria-hidden="true"></i> Hello, <b><?php echo  $user_name ?></b></a>
                        </li>
                    </ul>

                <?php }
            ?>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<!--Username info modal (acting as a popover)-->
<div class="modal fade" id="userInfoPopover" tabindex="-1" role="dialog" aria-labelledby="userInfoPopoverLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!-- Modal head -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="userInfoLabel"><i class="fa fa-user" aria-hidden="true"></i> User information of <b><?php echo $user_name?></b></h3>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
<!--                 Last login-->
                <span class="input-group-addon user-info-prefix">
                    <strong>
                        Last login:
                    </strong>
                </span>
                <span class="input-group-addon user-info-suffix">
                    <?php echo $user_last_login?>
                </span><br>
<!--                User ID-->
                <span class="input-group-addon user-info-prefix">
                    <strong>
                        User ID:
                    </strong>
                </span>
                <span class="input-group-addon user-info-suffix">
                    <?php echo $user_id?>
                </span><br>
<!--                 Username-->
                <span class="input-group-addon user-info-prefix">
                    <strong>
                        Username:
                    </strong>
                </span>
                <span class="input-group-addon user-info-suffix">
                    <?php echo $user_name?>
                </span><br>
<!--                 First name-->
                <span class="input-group-addon user-info-prefix">
                    <strong>
                        First name:
                    </strong>
                </span>
                <span class="input-group-addon user-info-suffix">
                    <?php echo $user_persName?>
                </span><br>
<!--                Last name-->
                <span class="input-group-addon user-info-prefix">
                    <strong>
                        Last name:
                    </strong>
                </span>
                <span class="input-group-addon user-info-suffix">
                    <?php echo $user_persSurname?>
                </span><br>
<!--                CR_Date-->
                <span class="input-group-addon user-info-prefix">
                    <strong>
                        Account creation date:
                    </strong>
                </span>
                <span class="input-group-addon user-info-suffix">
                    <?php echo $user_cr_date?>
                </span><br>
<!--                UP_Date-->
                <span class="input-group-addon user-info-prefix">
                    <strong>
                        Last update:
                    </strong>
                </span>
                <span class="input-group-addon user-info-suffix">
                    <?php if($user_up_date != "") {
                        echo $user_up_date;
                    } else {
                        echo "-";
                    }
                    ?>
                </span><br>
            </div>
        </div>
    </div>
</div>
<!--END Username info modal-->

<!-- PAGE CONTENT -->
<?php echo $content; ?>
<!-- END PAGE CONTENT -->

<!-- Footer -->
<div style="padding-top: 100px"></div>
    <div id="main-footer" class="main-footer" align="center">
        <span id="footer-text" class="footer-text">
                <strong>Project Respite</strong> - <i>Designer Shoes</i>
        </span>
    </div>
</body>
</html>
