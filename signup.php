<?php
ob_start(); // test

$page_title = 'Registo';
require_once('header.php');

$redirect = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';

if($_SESSION['level'] != 0)
    header('Location: ' . $redirect);

require_once ('redirect.php');
require_once ('navbar.php');

# Variável de erros
$error = 5;


# Metodo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    # A form foi submetida
    if (isset($_POST['submit_signup'])) {

        # Conexão à BD
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or header('Location: ' . $error_url . '?erro=1');

        $name = mysqli_real_escape_string($dbc, trim($_POST['name']));
        $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
        $password = mysqli_real_escape_string($dbc, $_POST['password']);
        $password2 = mysqli_real_escape_string($dbc, $_POST['password2']);
        $birthday = mysqli_real_escape_string($dbc, $_POST['birthday']);
        $color = mysqli_real_escape_string($dbc, trim($_POST['selected_color']));

        if(!empty($name) && !empty($username) && !empty($password) && !empty($password2) && !empty($birthday)) {

            if($password == $password2) {

                # Verificar se alguem ja possui este nome de utilizador
                $query = "SELECT * FROM intranet_user WHERE username = '$username'";

                $data = mysqli_query($dbc, $query)
                    or header('Location: ' . $error_url . '?erro=2');

                if(mysqli_num_rows($data) == 0) {
                    # O nome de utilizador é unico
                    $query = "INSERT INTO intranet_user(name, username, email, password, birthday, color)" .
                        "VALUES ('$name', '$username', '$email', SHA('$password'), '$birthday', '$color')";

                    mysqli_query($dbc, $query)
                        or header('Location: ' . $error_url . '?erro=2');

                    # Correu tudo bem
                    $error = 0;

                    mysqli_close($dbc);

                } // O utilizador não é unico
                else {
                    $error = 1;
                }


            } // passwords nao coincidem
            else {
                $error = 2;
            }

        } // empty fields
        else {
            $error = 3;
        }


    } // isset submit_signup


} // request method post
?>


<div class="main">

    <!-- 1st row -->
    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-wrench ml-2" aria-hidden="true"></i> Administração<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->

    <div class="container" >

        <div class="row mt-7 mb-3" id="section-add-member">

            <div class="col-sm-6 col-sm-offset-3">

                <h2 class="top-label"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></i> Novo utilizador</h2>

                <form class="mt-3" method="POST" id="signup-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="name" class="control-label secondary-label"><i class="fa fa-user"></i> Nome:</label>
                        <input id="nome-utilizador" name="name" type="text" class="form-control" placeholder="Nome do utilizador" value="<?php if (!empty($name)) echo $name; ?>">
                    </div>
                    <div class="form-group">
                        <label for="username" class="control-label secondary-label"><i class="fa fa-user"></i> Username:</label>
                        <input id="username" name="username" type="text" class="form-control" placeholder="Username do utilizador na intranet" value="<?php if (!empty($username)) echo $username; ?>">
                    </div>
                    <div class="form-group ">
                        <label for="email" class="control-label secondary-label"><i class="fa fa-address-book"></i> Email:</label>
                        <input id="signup-email" name="email" type="text" class="form-control" placeholder="Email do utilizador" value="<?php if (!empty($email)) echo $email; ?>">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="password" class="control-label secondary-label"><i class="fa fa-lock"></i> Password:</label>
                        <input id="password" name="password" type="password" class="form-control" placeholder="Password do utilizador" >
                    </div>
                    <div class="form-group">
                        <label for="password2" class="control-label secondary-label"><i class="fa fa-lock"></i> Repita a password:</label>
                        <input id="password2" name="password2" type="password" class="form-control" placeholder="Introduza a password novamente" >
                    </div>
                    <hr>

                    <div class="form-group">
                        <label for="selected-color" class="control-label secondary-label"><i class="fa fa-paint-brush"></i> Cor:</label>
                        <select id="selected-color" name="selected_color" class="form-control">
                            <?php
                            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                or die('<h3>Falha ao conectar à base de dados');
                            $query = "SELECT * FROM intranet_color WHERE color_id NOT IN (SELECT color FROM intranet_user) AND color_id != 1";
                            $data = mysqli_query($dbc, $query);

                            while ($row = mysqli_fetch_array($data)) {
                                echo '<option value="' . $row['color_id'] . '">' . $row['color_name'] . '</option>';
                            }

                            ?>
                        </select>


                    <div class="form-group mt-2">
                        <label for="birthday" class="control-label secondary-label"><i class="fa fa-calendar"></i> Data nascimento:</label>
                        <input id="birthday" name="birthday" type="text" class="form-control" placeholder="YYYY-MM-DD">
                    </div>

                    <hr>

                    <!-- BUTTON GROUP -->
                    <div class="text-center">
                        <div class="btn-group">
                            <input id="register-button" style="width: 130%;" type="submit" class="btn"  name="submit_signup" value="Submeter">
                        </div>
                    </div>

                </form>
                    <?php
                    if($error == 1) {
                        echo '<div id="div-erro" class="bg-danger signup-error mt-2">Por favor escolha outro nome de utilizador</div>';
                    }
                    else if($error == 2) {
                        echo '<div id="div-erro" class="bg-danger signup-error mt-2">As duas passwords não coincidem</div>';

                    }
                    else if($error == 3) {
                        echo '<div id="div-erro" class="bg-danger signup-error mt-2">Necessita de preencher todos os campos</div>';

                    }
                    else if($error == 0) {
                        echo '<div id="div-erro" class="bg-success signup-error mt-2">O utilizador foi adicionado com sucesso</div>';

                    }
                    ?>
            </div><!-- end col -->

            <script>
                $(document).ready(function() {
                    if ($('#div-erro').length > 0){
                        $('html, body').animate({
                            scrollTop: $('#div-erro').offset().top
                        }, 'slow');
                    }
                });
            </script>

        </div><!-- end row -->

    </div><!-- end container -->

</div><!-- end main -->

<?php
require_once('footer.php');
?>
