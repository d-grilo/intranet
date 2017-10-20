<?php
$page_title = 'Editar tarefa';
require_once('header.php');
require_once ('navbar.php');

$error = 5;


if(isset($_POST['submit_edit_task'])) {

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('<h3>Falha ao conectar à base de dados</h3>');

    $task_name = mysqli_real_escape_string($dbc, trim($_POST['edit_task_name']));
    $task_start_date = mysqli_real_escape_string($dbc, trim($_POST['edit_task_start_date']));
    $task_end_date = mysqli_real_escape_string($dbc, trim($_POST['edit_task_end_date']));
    $task_id = mysqli_real_escape_string($dbc, trim($_POST['task_id']));
    $task_summary = mysqli_real_escape_string($dbc, trim($_POST['edit_task_summary']));
    $add_user_name = mysqli_real_escape_string($dbc, trim($_POST['add_user']));


    if (!empty($task_name) && !empty($task_start_date) && !empty($task_end_date)) {

        if (!empty($add_user_name)) {

            # Encontrar o userid do utilizador apartir do seu nome
            $query = "SELECT user_id FROM intranet_user WHERE name = '$add_user_name'";
            $data = mysqli_query($dbc, $query);

            if (mysqli_num_rows($data) == 0) {
                $error = 4; // utilizador inexistente
            }
            else {

                $add_user_id = mysqli_fetch_assoc($data);
                # valor do user_id
                $value = $add_user_id['user_id'];

                # Verificar se este utilizador já se encontra relacionado com esta tarefa
                $query = "SELECT * FROM intranet_assigned_task WHERE user_id = '$value' AND task_id = '$task_id'";
                $data = mysqli_query($dbc, $query);

                # Caso não esteja, relacioná-lo
                if (mysqli_num_rows($data) == 0) {

                    $query = "INSERT INTO intranet_assigned_task(user_id, task_id)" .
                        "VALUES('$value', '$task_id')";

                    mysqli_query($dbc, $query);

                    # Fazer update ao estado da task para "em progresso"
                    $query = "UPDATE intranet_task SET task_progress = 1 WHERE task_id = '$task_id'";
                    mysqli_query($dbc, $query);
                } else {
                    $error = 3; // o utilizador já se encontra relacionado com esta tarefa
                }

            }
        }

        # Só actualizar os restantes dados caso não existam outros problemas
        if ($error != 3 && $error != 4) {
            $query = "UPDATE intranet_task SET task_name = '$task_name', task_start_date = '$task_start_date', 
              task_end_date = '$task_end_date', task_summary = '$task_summary' WHERE task_id = '$task_id'";

            mysqli_query($dbc, $query)
                or die('<h3>Falha ao comunicar com a base de dados</h3>');


            $error = 1; // correu tudo bem
        }

    } else {
        $error = 2; // campos vazios
    }


} // form submit


?>

    <div class="main">

        <!-- 1st row -->
        <div class="row">
            <!-- melhorar, usar div -->
            <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-wrench ml-2" aria-hidden="true"></i> Administração<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                    <?php echo $_SESSION['username'];?></span></h2>
        </div> <!-- end 1st row -->

        <div class="container" >

            <div class="row mt-6 mb-3" id="section-edit-task">

                <div class="col-sm-6 col-sm-offset-3">

                    <?php
                    if (isset($_GET['task_id'])) {
                        $task_id = $_GET['task_id'];

                        # Variável de sessão com o ID da tarefa a editar
                        $_SESSION['task_id'] = $task_id;

                        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                            or die('<h3>Falha ao conectar à base de dados</h3>');

                        $query_task = "SELECT * FROM intranet_task WHERE task_id = '$task_id'";

                        $query_assigned = "SELECT iat.user_id, iu.username, iu.name 
                              FROM intranet_assigned_task AS iat
                              INNER JOIN intranet_user AS iu USING(user_id)
                              WHERE task_id = '$task_id'";

                        $query_users = "SELECT user_id, name FROM intranet_user";

                        $data_task = mysqli_query($dbc, $query_task)
                            or die('<h3>Falha ao comunicar com a base de dados</h3>');

                        $data_assigned = mysqli_query($dbc, $query_assigned)
                            or die('<h3>Falha ao comunicar com a base de dados</h3>');

                        $data_users = mysqli_query($dbc, $query_users)
                            or die('<h3>Falha ao comunicar com a base de dados</h3>');


                        $row_task = mysqli_fetch_assoc($data_task);


                        echo '<h2 class="top-label mb-2"><i class="fa fa-tasks" aria-hidden="true"></i> ' . $row_task['task_name'] . ' </h2>';
                    }
                    ?>
                    <form method="POST" id="edit-task-form" class="test-form" action="edit_task.php?task_id=<?php echo $task_id;?>">
                        <input type="hidden" name="task_id" value="<?php echo $task_id;?>">
                        <div class="form-group">
                            <label class="control-label secondary-label" for="edit-task-name"><i class="fa fa-clipboard" aria-hidden="true"></i> Nome:</label>
                            <input id="edit-task-name" name="edit_task_name" class="form-control test-input" value="<?php echo $row_task['task_name'];?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label secondary-label" for="edit-task-start-date"><i class="fa fa-calendar" aria-hidden="true"></i> Data Ínicio:</label>
                            <input id="edit-task-start-date" name="edit_task_start_date" class="form-control test-input" value="<?php echo $row_task['task_start_date'];?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label secondary-label" for="edit-task-end-date"><i class="fa fa-calendar-times-o" aria-hidden="true"></i> Data fim:</label>
                            <input id="edit-task-end-date" name="edit_task_end_date" class="form-control" value="<?php echo $row_task['task_end_date'];?>">
                        </div>

                        <hr>

                        <h3 class="mt-2 "><i class="fa fa-edit" aria-hidden="true"></i> Adicionar Membros <button type="button" class="confirm-btn pull-right edit-chevron2"><i class="fa fa-chevron-down chevron2" aria-hidden="true"></i></button></h3>

                        <div id="add-member-div">
                            <div style="width: 100%;" class="search-box-task ajax-search-box">

                                <input class="search-box-input" name="add_user" type="text" autocomplete="off" placeholder="Procurar utilizador.." />

                                <div class="result-task ajax-result"></div>
                            </div>
                        </div> <!-- end add member div -->

                        <?php
                        echo '<hr>';

                        # Secção membros da tarefa
                        echo '<h3 class="mt-2"><i class="fa fa-users" aria-hidden="true"></i> Membros<button type="button" class="confirm-btn pull-right edit-chevron"><i class="fa fa-chevron-down chevron" aria-hidden="true"></i></button></h3>';
                        while ($row_users = mysqli_fetch_array($data_assigned)) {
                            echo '<li class="list-group-item user-list edit-wiki-list"><i class="fa fa-user" aria-hidden="true"></i> ' . $row_users['name'] . '<a href="processar_gestor.php?user_id=' . $row_users['user_id'] . '&task_id=' . $task_id . '&delete=1"><i class="fa fa-times-rectangle pull-right" aria-hidden="true"></i></a></li>';
                        }

                        ?>

                        <hr>

                        <div class="form-group" style="margin-top: 1rem;">
                            <label class="control-label secondary-label" for="edit-task-summary"><i class="fa fa-info-circle" aria-hidden="true"></i> Sumário:</label>
                            <textarea rows="5" id="edit-task-summary" name="edit_task_summary" class="form-control"><?php echo $row_task['task_summary'];?></textarea>
                        </div>

                    </form>

                    <!-- BUTTON GROUP -->
                    <div class="text-center">
                        <div class="btn-group">
                            <button type="submit" name="submit_edit_task" form="edit-task-form" id="edit-task-btn" class="btn confirm-btn mt-2 ml-2"><i class="fa fa-edit" aria-hidden="true"></i> Submeter</button>
                        </div>
                    </div>

                    <?php

                    # A form foi submetida com sucesso e os dados da tarefa foram actualizados
                    if($error == 1) {
                        echo '<div id="div-erro" class="bg-success text-center mt-2">Tarefa editada com successo</div>';
                    }
                    else if($error == 2) {
                        echo '<div id="div-erro" class="bg-danger text-center mt-2">Alguns campos críticos foram deixados em branco</div>';

                    }
                    else if($error == 3) {
                        echo '<div id="div-erro" class="bg-danger text-center mt-2">O utilizador já se encontra associado a esta tarefa</div>';
                    }
                    else if($error == 4) {
                        echo '<div id="div-erro" class="bg-danger text-center mt-2">O utilizador não existe</div>';
                    }

                    # Verifica se a variável de sessão existe
                    if(isset($_SESSION['task_edit'])) {

                        # O utilizador foi inserido na tarefa
                        if($_SESSION['task_edit'] == 1) {
                            echo '<div id="div-erro" class="bg-success text-center mt-2">O utilizador foi introduzido à tarefa com sucesso</div>';
                            unset($_SESSION['task_edit']);
                        }

                        # O utilizador já está inserido nesta tarefa
                        else if($_SESSION['task_edit'] == 2) {
                            echo '<div id="div-erro" class="bg-danger text-center mt-2">O utilizador já se encontra associado a esta tarefa</div>';
                            unset($_SESSION['task_edit']);
                        }

                        # O utilizador foi removido da tarefa
                        else if($_SESSION['task_edit'] == 3) {
                            echo '<div id="div-erro" class="bg-success text-center mt-2">O utilizador foi removido com sucesso da tarefa</div>';
                            unset($_SESSION['task_edit']);
                        }
                        # O utilizador não faz parte desta tarefa
                        else if($_SESSION['task_edit'] == 4) {
                            echo '<div id="div-erro" class="bg-danger text-center mt-2">O utilizador não faz parte desta tarefa</div>';
                            unset($_SESSION['task_edit']);
                        }

                    }

                    ?>

                </div> <!-- end col-->

                <script>
                    $(document).ready(function() {
                        if ($('#div-erro').length > 0){
                            $('html, body').animate({
                                scrollTop: $('#div-erro').offset().top
                            }, 'slow');
                        }
                    });
                </script>




                </div> <!-- end col-->

            </div> <!-- end row -->

        </div> <!-- end container -->

    </div> <!-- end main -->

<?php
require_once('footer.php');
?>