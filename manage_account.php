<?php
$page_title = 'Gerir conta';
require_once('header.php');
require_once ('navbar.php');

$error = 5;


# Metodo POST
if($_SERVER['REQUEST_METHOD'] === 'POST') {

    # A form foi submetida
    if(isset($_POST['submit_edit_account'])) {

        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or die('<h3>Falha ao conectar à base de dados</h3>');


        $user_id =  mysqli_real_escape_string($dbc, trim($_POST['user_id']));

        $name = mysqli_real_escape_string($dbc, trim($_POST['edit_user_name']));
        $email = mysqli_real_escape_string($dbc, trim($_POST['edit_user_email']));

        $password1 = mysqli_real_escape_string($dbc, trim($_POST['edit_user_password']));
        $password2 = mysqli_real_escape_string($dbc, trim($_POST['edit_user_password2']));


        if(!empty($name) && !empty($email) && !empty($password1) && !empty($password2)) {

            if($password1 == $password2) {

                $query = "UPDATE intranet_user SET name = '$name', email = '$email', password = SHA('$password1') WHERE user_id = '$user_id'";

                mysqli_query($dbc, $query)
                    or die('<h3>Falha ao comunicar com a base de dados</h3>');

                $error = 0; // correu tudo bem
            }
            else {
                $error = 1; // passwords diferem
            }

        } // empty vars
        else if(!empty($name) && !empty($email) && empty($password1) && empty($password2)) {

            # Verificar se o email já existe na bd
            $query = "SELECT email FROM intranet_user WHERE email = '$email' AND user_id != '$user_id'";
            $data = mysqli_query($dbc, $query)
            or die('<h3>Falha ao comunicar com a base de dados</h3>');


            if(mysqli_num_rows($data) == 0) {

                $query = "UPDATE intranet_user SET name ='$name', email = '$email' WHERE user_id = '$user_id'";
                mysqli_query($dbc, $query)
                or die('<h3>Falha ao comunicar com a base de dados</h3>');
                $error = 0; // correu tudo bem
            } // email repetido
            else {
                $error = 3;
            }

        } // password(s) vazias
        else {
            $error = 2; // existem variáveis vazias
        }


    } // submit

} // post

?>

<div class="main" >

    <!-- 1st row -->
    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-wrench ml-2" aria-hidden="true"></i> Administração<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->

    <div class="container" >

        <div class="row mt-6 mb-3" id="section-edit-account">

            <div class="col-sm-6 col-sm-offset-3">

                <h2 class="top-label mb-2"><i class="fa fa-user aria-hidden="true"></i> Gerir conta</h2>

                <?php
                # Caso seja passado o user id por get, editar conta pretendida
                if(isset($_GET['user_id'])) {
                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                    or die('<h3>Falha ao conectar à base de dados</h3>');
                    $user_id = $_GET['user_id'];

                    $query = "SELECT * FROM intranet_user WHERE user_id = '$user_id'";
                    $data = mysqli_query($dbc, $query)
                    or die('<h3>Falha ao comunicar com a base de dados</h3>');

                    $row = mysqli_fetch_array($data);

                }
                # Senão, editar conta actual(sessão)
                else {
                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                        or die('<h3>Falha ao conectar à base de dados</h3>');

                    $user_id = $_SESSION['user_id'];

                    $query = "SELECT * FROM intranet_user WHERE user_id = '$user_id'";
                    $data = mysqli_query($dbc, $query)
                        or die('<h3>Falha ao comunicar com a base de dados</h3>');

                    $row = mysqli_fetch_array($data);
                }

                ?>

                <form method="POST" id="edit-account-form" action="<?php if(!isset($_GET['user_id'])) echo $_SERVER['PHP_SELF']; else echo 'manage_account.php?user_id=' .  $user_id ;?>">

                    <input type="hidden" name="user_id" value="<?php if(isset($_GET['user_id'])) echo $user_id; else echo $_SESSION['user_id'];?>">

                    <div class="form-group">
                        <label class="control-label secondary-label" for="edit-user-name"><i class="fa fa-id-card" aria-hidden="true"></i> Nome:</label>
                        <input id="edit-user-name" name="edit_user_name" class="form-control" value="<?php echo $row['name']; ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label secondary-label" for="edit-user-email"><i class="fa fa-envelope" aria-hidden="true"></i> Email:</label>
                        <input id="edit-user-email" name="edit_user_email" class="form-control" value="<?php echo $row['email']; ?>">
                    </div>

                    <hr>

                    <h3 class="mt-2"><i class="fa fa-lock" aria-hidden="true"></i> Password<button type="button" class="confirm-btn pull-right edit-chevron"><i class="fa fa-chevron-up chevron" aria-hidden="true"></i></button></h3>
                    <div class="user-list" style="display: none;">
                    <div class="form-group">
                        <label class="control-label secondary-label" for="edit-user-password"><i class="fa fa-lock" aria-hidden="true"></i> Nova Password:</label>
                        <input type="password" id="edit-user-password" name="edit_user_password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label secondary-label" for="edit-user-password2"><i class="fa fa-lock" aria-hidden="true"></i> Repita a password:</label>
                        <input type="password" id="edit-user-password2" name="edit_user_password2" class="form-control" >
                    </div>
                    </div>

                    <hr>

                </form>

                <!-- BUTTON GROUP -->
                <div class="text-center">
                    <div class="btn-group">
                        <button type="submit" name="submit_edit_account" form="edit-account-form" id="edit-account-btn" class="btn confirm-btn pl-2 pr-2 mt-2 ml-2"><i class="fa fa-edit" aria-hidden="true"></i> Editar</button>
                    </div>
                </div>

                <?php
                if($error == 1)
                    echo '<div class="bg-danger signup-error mt-2">As passwords não coincidem</div>';
                else if($error == 2)
                    echo '<div class="bg-danger signup-error mt-2">Por favor preencha todos os campos relevantes</div>';
                else if($error == 3)
                    echo '<div class="bg-danger signup-error mt-2">Por favor escolha outro email</div>';
                else if($error == 0)
                    echo '<div class="bg-success text-center mt-2">Os dados foram actualizados com sucesso</div>';
                ?>

                <!-- <img style="height: 60px; ; width: 60px;" src="images/profile.jpg" class="img-rounded img-responsive"> -->

            </div> <!-- end col -->

        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>
