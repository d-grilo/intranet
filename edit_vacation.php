<?php
$page_title = 'Editar férias';
require_once('header.php');
require_once ('navbar.php');

$error = 5;


# Método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    # A form foi submetida
    if (isset($_POST['submit_edit_vacation'])) {

        # Conexão à BD
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or die('<h3>Falha ao conectar à base de dados</h3>');

        $vacation_start = mysqli_real_escape_string($dbc, trim($_POST['edit_start_date']));
        $vacation_end = mysqli_real_escape_string($dbc, trim($_POST['edit_end_date']));
        $user_id = $_POST['user_id'];
        $vacation_id = $_POST['vacation_id'];

        # Só continuar caso todos os campos estejam preenchidos
        if (!empty($user_id) && !empty($vacation_id) && !empty($vacation_start) && !empty($vacation_end)) {

            $query = "SELECT vacation_start_date, vacation_end_date, user_id FROM intranet_vacation
                      WHERE vacation_start_date = '$vacation_start' AND vacation_end_date = '$vacation_end' AND user_id = '$user_id'";

            $data = mysqli_query($dbc, $query)
                or die('<h3>Falha ao comunicar com a base de dads</h3>');

            # Apenas actualizar o período de férias caso não seja repetido
            if (mysqli_num_rows($data) == 0) {

                $query = "UPDATE intranet_vacation SET vacation_start_date = '$vacation_start', vacation_end_date = '$vacation_end' 
                          WHERE vacation_id = '$vacation_id'";

                mysqli_query($dbc, $query)
                    or die('<h3>Falha ao comunicar com a base de dadosss</h3>');

                $error = 0;

                # Fechar conexão
                mysqli_close($dbc);



            } // prazo de férias repetido para este utilizador
            else
                $error = 2;

        } // empty fields
        else
            $error = 1;

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

        <div class="row mt-10 mb-3" id="section-edit-vacation">

            <div class="col-sm-6 col-sm-offset-3">
                <?php
                if (isset($_GET['vacation_id'])) {
                    $vacation_id = $_GET['vacation_id'];


                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                        or die('<h3>Falha ao conectar à base de dados</h3>');

                    $query = "SELECT iv.*, iu.name FROM intranet_vacation AS iv
                          INNER JOIN intranet_user AS iu USING(user_id) WHERE vacation_id = '$vacation_id'";

                    $data = mysqli_query($dbc, $query)
                        or die('<h3>Falha ao comunicar com a base de dados</h3>');

                    $row = mysqli_fetch_assoc($data);

                    echo '<h2 class="top-label mb-2"><i class="fa fa-palm-tree" aria-hidden="true"></i> Prazo férias (' . $row['name'] . ')</h2>';


                }
                ?>
                <form method="POST" id="edit-vacation-form" class="test-form" action="edit_vacation.php?vacation_id=<?php echo $vacation_id;?>">
                    <input type="hidden" name="vacation_id" value="<?php echo $vacation_id;?>">
                    <input type="hidden" name="user_id" value="<?php echo $row['user_id'];?>">
                    <div class="form-group">
                        <label class="control-label secondary-label" for="edit-start-date"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Data início</label>
                        <input id="edit-start-date" name="edit_start_date" class="form-control test-input datepicker" value="<?php echo $row['vacation_start_date'];?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label secondary-label" for="edit-end-date"><i class="fa fa-calendar-times-o" aria-hidden="true"></i> Data fim</label>
                        <input id="edit-end-date" name="edit_end_date" class="form-control test-input datepicker" value="<?php echo $row['vacation_end_date'];?>">
                    </div>

                </form>

                <?php

                if($error == 1) {
                    echo '<div class="bg-danger signup-error mt-2">Por favor preencha todos os campos</div>';
                }
                else if($error == 2) {
                    echo '<div class="bg-danger signup-error mt-2">Já existe um prazo de férias previstas para este utilizador neste espaço de tempo</div>';
                }
                else if($error == 0) {
                    echo '<div class="bg-success signup-error mt-2">O prazo de férias foi editado com sucesso</div>';
                }

                ?>

                <!-- BUTTON GROUP -->
                <div class="text-center">
                    <div class="btn-group">
                        <button type="submit" name="submit_edit_vacation" form="edit-vacation-form" id="edit-vacation-btn" class="btn confirm-btn mt-2 ml-2"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;&nbsp;Editar&nbsp; &nbsp; </button>
                    </div>
                </div>

            </div> <!-- end col -->

        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>
