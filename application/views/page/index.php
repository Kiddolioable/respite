<?php
    $session = Session::instance();

    $user_id = $session->get('user_id');
    $user_persName = $session->get('user_persName');
    
    if(isset($user_id) && $user_id != "")
    {
        ?>
        <br><br>
        <div id="homepage-content" class="homepage-content" style="margin-left: 100px; margin-right: 100px;">
            <?php if ($session->get('user_last_login') != ""){ ?>
                <h3 id="homepage-hello">Welcome back, <?php echo $user_persName.".";?> </h3>
                <h4>Your last login was <?php echo $session->get('user_last_login'); ?>. </h4>
            <?php } else{ ?> 
                <h3 id="homepage-hello">Welcome, <?php echo $user_persName.".";?> </h3>
                
            <?php } ?>
        </div>
        
        <!-- EASTER EGG! -->
        <div align="center" id="homepage-eggified" class="hidden" style="margin-left: 100px; margin-right: 100px;">
            <h3>Rock on, <?php echo $user_persName."!";?> </h3>
        </div>
        
        <div id="carousel-example-generic" class="hidden" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                <li data-target="#carousel-example-generic" data-slide-to="3"></li>
                <li data-target="#carousel-example-generic" data-slide-to="4"></li>
                <li data-target="#carousel-example-generic" data-slide-to="5"></li>
                <li data-target="#carousel-example-generic" data-slide-to="6"></li>
            </ol>

            <!-- Wrapper for slides -->
            <div align="center" class="carousel-inner" role="listbox">
                <div class="item active">
                    <img src="/public/images/gifOne.gif" alt="...">
                    <div class="carousel-caption">
                    </div>
                </div>
                <div class="item">
                    <img src="/public/images/gifTwo.gif" alt="...">
                    <div class="carousel-caption">
                    </div>
                </div>
                <div style="width: 300px" class="item">
                    <img src="/public/images/gifThree.gif" alt="...">
                    <div class="carousel-caption">
                    </div>
                </div>
                <div class="item">
                    <img src="/public/images/gifFour.gif" alt="...">
                    <div class="carousel-caption">
                    </div>
                </div>
                <div class="item">
                    <img src="/public/images/gifFive.gif" alt="...">
                    <div class="carousel-caption">
                    </div>
                </div>
                <div class="item">
                    <img src="/public/images/gifSix.gif" alt="...">
                    <div class="carousel-caption">
                    </div>
                </div>
                <div class="item">
                    <img src="/public/images/gifSeven.gif" alt="...">
                    <div class="carousel-caption">
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        
        <div style="padding: 320.5px">
<!--            Spacer for footer-->
        </div>
        
        

        <!-- Toggle IRON MAIDEN mode (Easter Egg). Per te Gabri xD -->
        <div id="easterLogoDiv">
            <a href="#">
                <img onclick="easterEggify('bodyID', 
                'headerID', 
                'navbar-main', 
                'SISlogo',
                'EasterEggLogo', 
                'main-footer', 
                'footer-text',
                'homepage-content',
                'homepage-eggified',
                'carousel-example-generic')
                " 
                     id="easterLogo" src="/public/images/Iron-Maiden-Logo.png" alt="easterToggleLogo">
            </a>
        </div>

        <?php
    } else{
    ?> 
    <!-- Home Tab -->
    <br><br>
    <!-- Login -->
    <div id="tab-login" class="not-home">
        <h1 id="login-header">Sign in</h1>
    
        <br>
        <form class="form-horizontal" method="post">
    
            <div class="form-group">
    
                <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="usernameInsertHome" name="usernameInsertHome" placeholder="Insert Username">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="passwordInsertHome" name="passwordInsertHome" placeholder="Insert Password">
                </div>
            </div>
            
            <!-- Create account, forgot user and forgot pass links-->
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">Sign in</button>
                    <br><br>
                        <?php echo HTML::anchor('/page/signup', "Create an account")?>
                    <br><br>
                    <a href="#">
                        <p>Forgot your username?</p>
                    </a>
                    <a href="#">
                        <p>Forgot your password?</p>
                    </a>
                </div>
            </div>
    </div>

        <!-- Editing Modal -->
        <div class="modal fade" id="lolModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="lolModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="lolModalLabel">Table edit</h4>
                    </div>
                    <div class="modal-body">
                        <strong>Qual è il linguaggio preferito di Vania?: </strong>
                        <input class="form-control" placeholder=":P" type="text" maxlength="48" id="lolInput">
                        <br>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Modal Edit -->
   <?php } ?>




