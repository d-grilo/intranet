<?php
if(isset($_SESSION['username'])) {
    ?>
    <!-- fixded top -->
    <nav style="background-color: #1a2129; border-right: solid 1px white;" class="navbar sidebar navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle glyphicon glyphicon-menu-hamburger gi-2x" data-toggle="collapse"
                        data-target="#bs-sidebar-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <span style="font-size:25px;" class=" hidden-xs showopacity glyphicon glyphicon-eye-open"></span>
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li style="margin-top: 3rem;">
                        <a href="index.php">Dashboard<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-list-alt"></span></a>
                    </li>
                    <li>
                        <a href="wiki.php">Wiki<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-info-sign"></span></a>
                    </li>
                    <li>
                        <a href="calendario.php">Calendário<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-calendar"></span></a>
                    </li>
                    <li>
                        <a href="view_users.php">Utilizadores<span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-users"></span></a>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administração
                            <span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-cog"></span>
                        </a>
                        <ul class="dropdown-menu forAnimate" role="menu">
                            <li><a href="manage_wiki.php">Gerir Wiki</a></li>
                            <li><a href="manage_project_manager.php">Gerir projectos</a></li>
                            <li><a href="manage_accounts.php">Gerir utilizadores</a></li>
                            <li class="divider"></li>
                            <li><a href="manage_account.php">Gerir conta</a></li>

                        </ul>
                    </li>

                    <li>
                        <a class="logout-link" href="logout.php">Sair<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-log-out"></span></a>
                    </li>


                </ul>
            </div>
        </div>
    </nav>
    <?php
}
else {
    ?>
    <!-- fixded top? -->
    <nav style="background-color: #1a2129; border-right: solid 1px white;"  class="navbar sidebar navbar-fixed-top hidden-xs" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target="#bs-sidebar-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <span style="font-size:25px;" class=" hidden-xs showopacity glyphicon glyphicon-lock"></span>
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">

            </div>
        </div>
    </nav>


<?php
} ?>



