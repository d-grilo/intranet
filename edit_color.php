<?php
$page_title = 'Editar cor';
require_once('header.php');
require_once ('navbar.php');

$sucesso = false;

if(isset($_POST['submit_edit_color'])) {

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('<h3>Falha ao conectar à base de dados');

    $color_id =  mysqli_real_escape_string($dbc, trim($_POST['color_id']));
    $color_name = mysqli_real_escape_string($dbc, trim($_POST['edit_color_name']));
    $color_hex = mysqli_real_escape_string($dbc, trim($_POST['edit_color_hex']));

    $query = "UPDATE intranet_color SET color_name = '$color_name', color_hex = '$color_hex' WHERE color_id = '$color_id'";

    mysqli_query($dbc, $query)
        or die('<h3>Falha ao comunicar com a base de dados');

    # Fechar conexão
    mysqli_close($dbc);

    $sucesso = true;

}

?>
<div class="main" >

    <!-- 1st row -->
    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-wrench ml-2" aria-hidden="true"></i> Administração<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->

    <div class="container" >

        <div class="row mt-6 mb-3" id="section-edit-color">

            <div class="col-sm-6 col-sm-offset-3">

                <?php
                if (isset($_GET['color_id'])) {
                    $color_id = $_GET['color_id'];

                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                        or die('<h3>Falha ao conectar à base de dados');

                    $query = "SELECT * FROM intranet_color WHERE color_id ='$color_id'";

                    $data = mysqli_query($dbc, $query)
                        or die('<h3>Falha ao comunicar com base de dados');

                    $row = mysqli_fetch_assoc($data);

                    echo '<h3 class="top-label mb-2"><i class="fa fa-paint-brush" aria-hidden="true"></i> ' . $row['color_name'] . ' </h3>';
                }
                ?>
                <!-- Edit color form -->
                <form method="POST" id="edit-color-form" action="edit_color.php?color_id=<?php echo $color_id;?>">
                    <input type="hidden" name="color_id" value="<?php echo $color_id;?>">
                    <div class="form-group">
                        <label for="edit-color-name" class="secondary-label" ><i class="fa fa-info-circle" aria-hidden="true"></i> Nome:</label>
                        <input id="edit-color-name" name="edit_color_name" class="form-control" value="<?php echo $row['color_name'];?>">
                    </div>
                    <div class="form-group">
                        <label for="edit-color-hex" class="secondary-label"><i class="fa fa-paint-brush" aria-hidden="true"></i> Hex da cor:</label>
                        <input id="edit-color-hex" name="edit_color_hex" class="form-control" value="<?php echo $row['color_hex'];?>">
                    </div>
                    <?php
                    echo '<div class="mb-4" style="background-color: ' . $row['color_hex'] . ';width: 5%; margin: 0 auto;">&nbsp;</div>';
                    ?>

                    <!-- BUTTON GROUP -->
                    <div class="text-center">
                        <div class="btn-group">
                            <button name="submit_edit_color" type="submit" class="btn confirm-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</button>
                        </div>
                    </div>
                </form>

                <?php
                if($sucesso)
                    echo '<div class="bg-success text-center mt-2">Cor editada com sucesso</div>';
                ?>





            </div> <!-- end col -->

        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>