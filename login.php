<?php
$page_title = 'Login';
$login_error = 5;
require_once('header.php');
require_once ('navbar.php');

# Metodo POST
if($_SERVER['REQUEST_METHOD'] === 'POST') {

    # A form foi submetida
    if (isset($_POST['submit_login'])) {

        # Conexão à BD
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or header('Location: ' . $error_url . '?erro=1');

        $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $password = mysqli_real_escape_string($dbc, trim($_POST['password']));

        if (!empty($username) && !empty($password)) {
            # Procurar o nome de utilizador e a palavra passe na base de dados
            $query = "SELECT user_id, username, level FROM intranet_user WHERE username = '$username' AND " .
                "password = SHA('$password')";

            $data = mysqli_query($dbc, $query)
                or header('Location: ' . $error_url . '?erro=2');

            if(mysqli_num_rows($data) == 1) {
                # O log-in está OK, criar cookies e redirecionar o utilizador
                $row = mysqli_fetch_array($data);
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['level'] = $row['level'];

                setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30)); // expira em 30 dias
                setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30)); // expira em 30 dias
                setcookie('level', $row['level'], time() + (60 * 60 * 24 * 30)); // expira em 30 dias

                # Fechar conexão
                mysqli_close($dbc);

                $redirect_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';

                header('Location: ' . $redirect_url);

            } // username ou pw errada
            else {
                $login_error = 1;
            }

        } // empty fields
        else {
            $login_error = 2;
        }
    } //submit login
} // post


?>


<div class="main" >

    <div class="container" >

        <div class="row mt-6 mb-3" id="section-login">


            <div class="col-sm-6 col-sm-offset-3">

                <h3 class="mb-2 top-label"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</h3>

                <form method="post" id="login-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"> Utilizador</i></span>
                        <input id="login-username" name="username" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"> Password</i></span>
                        <input id="login-password" name="password" type="password" class="form-control" >
                    </div>

                    <hr>

                    <!-- BUTTON GROUP -->
                    <div class="text-center">
                        <div class="btn-group">
                            <input style="width: 130%;" type="submit" class="btn" id="submit-login" name="submit_login" value="Entrar">
                        </div>
                    </div>
                </form>

                <?php
                if($login_error == 1)
                    echo '<div id="div-erro" class="bg-danger signup-error mt-2">Dados incorrectos</div>';
                else if($login_error == 2)
                    echo '<div id="div-erro" class="bg-danger signup-error mt-2">Por favor preencha ambos os campos</div>';
                ?>
            </div> <!-- col -->

        </div><!-- row -->

    </div> <!-- container -->

</div><!-- main -->

<?php
require_once('footer.php');
?>

