<?php
$page_title = 'Adicionar tarefa';
require_once('header.php');
require_once ('navbar.php');

# Variável de erros
$error = 5;

# Método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    # A form foi submetida
    if (isset($_POST['submit_task'])) {

        # Conexão à BD
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or die('<h3>Falha ao conectar à base de dados</h3>');

        $task_name = mysqli_real_escape_string($dbc, trim($_POST['task_name']));
        $task_project = mysqli_real_escape_string($dbc, trim($_POST['selected_project']));
        $task_start_date = mysqli_real_escape_string($dbc, trim($_POST['task_start_date']));
        $task_end_date = mysqli_real_escape_string($dbc, trim($_POST['task_end_date']));
        $task_summary = mysqli_real_escape_string($dbc, trim($_POST['task_summary']));

        $user_id = $_SESSION['user_id'];

#       # Só continuar caso pelo menos o nome da tarefa, projecto associado, data de inicio e de fim estejam preenchidos
        if(!empty($task_name) && isset($task_project) && !empty($task_start_date) && !empty($task_end_date)) {

            $query = "SELECT task_name FROM intranet_task WHERE task_name = '$task_name'";

            $data = mysqli_query($dbc, $query)
                or die('<h3>Falha ao comunicar com a base de dados</h3>');

            # Apenas criar a tarefa caso não exista outra com o mesmo nome
            if(mysqli_num_rows($data) == 0) {

                $query = "INSERT INTO intranet_task(task_name, task_start_date, task_end_date, project_id, task_summary)" .
                    "VALUES('$task_name', '$task_start_date', '$task_end_date', '$task_project', '$task_summary')";

                mysqli_query($dbc, $query)
                    or die('<h3>Falha ao comunicar com a base de dados</h3>');

                # Obter o último task_id para inserir na próxima query
                $query_task_id = "SELECT MAX(task_id) last_id FROM intranet_task";

                $data_task_id = mysqli_query($dbc, $query_task_id)
                    or die('<h3>Falha ao comunicar com a base de dados</h3>');

                $task_id = mysqli_fetch_assoc($data_task_id);

                $value = $task_id['last_id'];

                /* Verifica se foi atribuido algum utilizador à tarefa,
                caso tenha sido, mudar o identificador do progresso */
                if(isset($_POST['users'])) {
                    $query = "UPDATE intranet_task SET task_progress = 1 WHERE task_id = '$value'";

                    mysqli_query($dbc, $query)
                        or die('<h3>Falha ao comunicar com a base de dados</h3>');


                    # Atribuir os utilizadores selecionados ao último projecto criado
                    foreach($_POST['users'] as $_boxValue) {

                        $query = "INSERT INTO intranet_assigned_task(task_id, user_id)" .
                            "VALUES('$value', '$_boxValue')";

                        mysqli_query($dbc, $query)
                        or die('<h3>Falha ao comunicar com a base de dados</h3>');
                    }

                }// post users

                $error = 0; // correu tudo bem

                # Fechar conexão
                mysqli_close($dbc);

            } // tarefa com mesmo nome
            else {
                $error = 2;
            }


        } // empty fields
        else {
            $error = 1;
        }

    } // submit form

} // post



?>

<div class="main" >

    <!-- 1st row -->
    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-briefcase ml-2" aria-hidden="true"></i> Gestor de projectos<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->

    <div class="container" >


        <div class="row mt-6 mb-3" id="section-add-task">

            <div class="col-sm-6 col-sm-offset-3">

                <h2 class="top-label mb-2"><i class="fa fa-tasks pull-left" aria-hidden="true"></i>Nova tarefa</h2>

                <form method="post" id="add-task-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="task-name" class="control-label secondary-label"><i class="fa fa-clipboard aria-hidden="true"></i> Nome:</label>
                        <input id="task-name" name="task_name" type="text" class="form-control" placeholder="Nome da tarefa" value="<?php if (!empty($task_name)) echo $task_name; ?>">
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="selected-project" class="secondary-label"><i class="fa fa-folder"></i> Projecto:</label>
                        <select id="selected-project" name="selected_project" class="form-control">
                            <?php
                            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                            or die('<h3>Falha ao conectar à base de dados');

                            $query = "SELECT * FROM intranet_project WHERE project_progress != 2";

                            $data = mysqli_query($dbc, $query);

                            # Caso seja passado por GET o nome do projecto, mostra só o projecto pretendido
                            if(isset($_GET['project_name'])) {
                                while ($row = mysqli_fetch_array($data)) {
                                    if($row['project_name'] == $_GET['project_name'])
                                        echo '<option value="' . $row['project_id'] . '">' . $row['project_name'] . '</option>';
                                }
                            }
                            # Senão, mostra todos os projectos
                            else {
                                while ($row = mysqli_fetch_array($data)) {
                                    echo '<option value="' . $row['project_id'] . '">' . $row['project_name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <hr>
                    <div class="form-group">
                        <label for="datepicker" class="control-label secondary-label"><i class="fa fa-calendar"></i> Data ínicio:</label>
                        <input id="datepicker" name="task_start_date" type="text" class="form-control" placeholder="Data de ínicio" value="<?php if (!empty($task_start_date)) echo $task_start_date; ?>">
                    </div>
                    <div class="form-group">
                        <label for="datepicker2" class="control-label secondary-label"><i class="fa fa-calendar-times-o"></i> Data fim:</label>
                        <input id="datepicker2" name="task_end_date" type="text" class="form-control" placeholder="Data fim" value="<?php if (!empty($task_end_date)) echo $task_end_date; ?>">
                    </div>
                    <hr>
                    <div class="form-group">
                        <h3 class="secondary-label mt-2"><i class="fa fa-users" aria-hidden="true"></i> Membros<button type="button"  class="confirm-btn pull-right edit-chevron"><i  class="fa fa-chevron-down chevron" aria-hidden="true"></i></button></h3>

                        <?php
                        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                        or die('<h3>Falha ao conectar à base de dados</h3>');

                        $query_users = "SELECT * FROM intranet_user";

                        $data_users = mysqli_query($dbc, $query_users)
                        or die('<h3>Falha ao comunicar com a base de dados</h3>');

                        $numero = 0;
                        echo '<div class="white-bordered-2">';
                        while($row = mysqli_fetch_array($data_users)) {
                            echo '<div class="checkbox user-list">
                                     <label class="ml-2"> <input name="users[]" type="checkbox" value="' . $row['user_id'] . '">' . $row['name'] . ' </label></li>
                                    </div>';
                        }
                        ?>
                        </div>


                    </div>

                    <hr>
                    <div class="form-group">
                        <label for="task-summary" class="control-label secondary-label"><i class="fa fa-info-circle"></i> Sumário:</label>
                        <textarea id="task-summary" name="task_summary" class="form-control" rows="5"></textarea>
                    </div>
                    <div class="text-center">
                        <div class="btn-group">
                            <input id="submit-task" style="width: 130%;" type="submit" class="btn confirm-btn"  name="submit_task" value="Submeter">
                        </div>
                    </div>

                </form>

                <?php
                if($error == 1) {
                    echo '<div id="div-erro" class="bg-danger signup-error mt-2">Por favor preencha todos os campos. O sumário é opcional</div>';
                }
                else if($error == 2) {
                    echo '<div id="div-erro" class="bg-danger signup-error mt-2">Já existe uma tarefa com este nome</div>';
                }
                else if($error == 0) {
                    echo '<div id="div-erro" class="bg-success signup-error mt-2">A tarefa foi criada com sucesso</div>';
                }
                ?>

            <script>
                $(document).ready(function() {

                    if ($('#div-erro').length > 0){

                        $('html, body').animate({
                            scrollTop: $('#div-erro').offset().top
                        }, 'slow');
                    }

                });
            </script>




            </div> <!-- end col -->

        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>
