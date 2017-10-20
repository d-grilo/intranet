<?php
$page_title = 'Administração Utilizadores';
require_once('header.php');
require_once ('redirect.php');
require_once ('navbar.php');

?>

<div class="main">

    <!-- 1st row -->
    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-wrench ml-2" aria-hidden="true"></i> Administração<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->

    <div class="container">

        <div class="row row-eq-height mt-10">

            <div class="col-sm-6 col-sm-offset-3">

                <h3 class="secondary-label"><i class="fa fa-cogs" aria-hidden="true"></i> Administração Utilizadores</h3>
                <ul class="list-group">
                    <a class="manage-links" href="manage_users.php"><li class="list-group-item edit-wiki-list"><i class="fa fa-users" aria-hidden="true"></i> Utilizadores</li></a>
                    <a class="manage-links" href="manage_colors.php"><li class="list-group-item edit-wiki-list"><i class="fa fa-paint-brush" aria-hidden="true"></i> Cores</li></a>
                    <a class="manage-links" href="manage_vacations.php"><li class="list-group-item edit-wiki-list"><i class="fa fa-palm-tree" aria-hidden="true"></i> Férias</li></a>
                </ul>

                <?php
                if($_SESSION['level'] == 0) {
                    ?>
                    <!-- BUTTON GROUP -->
                    <div class="text-center">
                        <div class="btn-group">
                            <a href="signup.php" class="btn confirm-btn mt-2 ml-2"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar utilizador</a>
                        </div>
                    </div>
                    <?php
                }
                ?>

            </div> <!-- col -->

        </div> <!--1st row -->


    </div> <!-- container -->

</div> <!-- main -->

<?php
require_once('footer.php');
?>

