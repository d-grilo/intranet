<?php
ob_start();
$page_title = 'Adicionar cor';
require_once('header.php');
require_once ('navbar.php');

$error = 5;

# Método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    # A form foi submetida
    if (isset($_POST['submit_add_color'])) {

        # Conexão à BD
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or header('Location: ' . $error_url . '?erro=1');

        $color_name = mysqli_real_escape_string($dbc, trim($_POST['color_name']));
        $color_hex = mysqli_real_escape_string($dbc, trim($_POST['color_hex']));

        if(!empty($color_name) && !empty($color_hex)) {

            $query = "SELECT * FROM intranet_color WHERE color_name = '$color_name' OR color_hex = '$color_hex'";

            $data = mysqli_query($dbc, $query)
               or header('Location: ' . $error_url . '?erro=2');

            if (mysqli_num_rows($data) == 0) {

                $query = "INSERT INTO intranet_color(color_name, color_hex)" .
                    "VALUES('$color_name', '$color_hex')";

                mysqli_query($dbc, $query)
                    or header('Location: ' . $error_url . '?erro=2');

                $error = 0;

                # Fechar conexão
                mysqli_close($dbc);


            } // ja existe nome de cor ou hex
            else {
                $error = 2;
            }

        } // empty fields
        else {
            $error = 1;
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

        <div class="row mt-6 mb-3" id="section-add-color">

            <div class="col-sm-6 col-sm-offset-3">

                <h2 class="mb-2 top-label"><i class="fa fa-paint-brush pull-left" aria-hidden="true"></i>Adicionar cor</h2>

                <form method="POST" id="add-color-form" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <div class="form-group">
                        <label for="color-name" class="control-label secondary-label"><i class="fa fa-clipboard" aria-hidden="true"></i> Nome:</label>
                        <input id="color-name" class="form-control" name="color_name" type="text" placeholder="Nome da cor" value="<?php if (!empty($color_name)) echo $color_name; ?>">
                    </div>

                    <div class="form-group">
                        <label for="color-hex" class="control-label secondary-label"><i class="fa fa-code" aria-hidden="true"></i> Hex da cor: </label>
                        <input id="color-hex" class="form-control" name="color_hex" type="text" placeholder="O hex da cor, por exemplo: red ou #eeee" value="<?php if (!empty($color_hex)) echo $color_hex;?>">
                    </div>

                    <!-- BUTTON GROUP -->
                    <div class="text-center mt-3">
                        <div class="btn-group">
                            <button name="submit_add_color" type="submit" class="btn confirm-btn"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar</button>
                        </div>
                    </div> <!-- end btn group -->

                </form>

                <?php
                if($error == 1) {
                    echo '<div id="div-erro" class="bg-danger signup-error mt-2">Por favor preencha todos os campos</div>';
                }
                else if($error == 2) {
                    echo '<div id="div-erro" class="bg-danger signup-error mt-2">O nome ou hex desta cor já existem registados no sistema</div>';
                }
                else if($error == 0) {
                    echo '<div id="div-erro" class="bg-success signup-error mt-2">A cor foi introduzida com sucesso</div>';

                }
                ?>

            </div> <!-- end col -->

        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
ob_end_flush();
?>
