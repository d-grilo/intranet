<?php
$page_title = 'Adicionar projecto';
require_once('header.php');
require_once ('navbar.php');

# Variável de erros
$error = 5;

# Metodo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    # A form foi submetida
    if(isset($_POST['submit_project'])) {

        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or die('<h3>Falha ao conectar à base de dados');

        $project_name = mysqli_real_escape_string($dbc, trim($_POST['project_name']));
        $project_start_date = mysqli_real_escape_string($dbc, trim($_POST['project_start_date']));
        $project_end_date = mysqli_real_escape_string($dbc, trim($_POST['project_end_date']));
        $project_summary = mysqli_real_escape_string($dbc, trim($_POST['project_summary']));


        $user_id = $_SESSION['user_id'];


        # Só continuar caso pelo menos o nome, data inicio e data de fim estejam preenchidos
        if(!empty($project_name) && !empty($project_start_date) && !empty($project_end_date)) {


            $query = "SELECT project_name FROM intranet_project WHERE project_name = '$project_name'";

            $data = mysqli_query($dbc, $query)
                or die('<h3>Falha ao comunicar com a base de dados</h3>');

            # Apenas criar o projecto caso não exista outra com o mesmo nome
            if (mysqli_num_rows($data) == 0) {

                $query_project = "INSERT INTO intranet_project(project_name, project_start_date, project_end_date, project_summary)" .
                    "VALUES('$project_name', '$project_start_date', '$project_end_date', '$project_summary')";

                mysqli_query($dbc, $query_project)
                    or die('<h3>Falha ao comunicar com a base de dados</h3>');


                # Obter o último project_id para inserir na proxima query
                $query_project_id = "SELECT MAX(project_id) last_id FROM intranet_project";

                $data_project_id = mysqli_query($dbc, $query_project_id)
                or die('<h3>Falha ao comunicar com a base de dados</h3>');

                $project_id = mysqli_fetch_assoc($data_project_id);

                $value = $project_id['last_id'];

                /* Verifica se foi atribuido algum utilizador ao projecto,
                 caso tenha sido, mudar o identificador do progresso */
                if(isset($_POST['users'])) {

                    $query = "UPDATE intranet_project SET project_progress = 1 WHERE project_id = '$value'";

                    mysqli_query($dbc, $query)
                        or die('<div class="container"><h3>Falhassssss ao comunicar com a base de dados</h3></div>');

                    # Atribuir os utilizadores selecionados ao último projecto criado
                    foreach($_POST['users'] as $_boxValue) {

                        $query = "INSERT INTO intranet_assigned_project(project_id, user_id)" .
                            "VALUES('$value', '$_boxValue')";

                        mysqli_query($dbc, $query)
                        or die('<h3>Falha ao comunicar com a base de dados</h3>');
                    }


                }

                $error = 0;

            } // este projecto já existe
            else {
                $error = 1;
            }


        } // empty fields
        else {
            $error = 2;
        }

    } //isset submit project

} // metodo post

?>
<div class="main">

    <!-- 1st row -->
    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-briefcase ml-2" aria-hidden="true"></i> Gestor de projectos<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->

    <div class="container" >

        <div class="row mt-6 mb-3" id="section-add-project">

            <div class="col-sm-6 col-sm-offset-3">

                <h2 class="top-label mb-2"><i class="fa fa-line-chart pull-left" aria-hidden="true"></i>Novo projecto</h2>

                <form method="post" id="add-project-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="project-name" class="control-label secondary-label"><i class="fa fa-clipboard"></i> Nome:</label>
                        <input id="project-name" name="project_name" type="text" class="form-control" placeholder="Nome do projecto" value="<?php if (!empty($project_name)) echo $project_name; ?>">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="datepicker" class="control-label secondary-label"><i class="fa fa-calendar"></i> Data ínicio:</label>
                        <input id="datepicker" name="project_start_date" type="text" class="form-control" placeholder="Data de ínicio" value="<?php if (!empty($project_start_date)) echo $project_start_date; ?>">
                    </div>

                    <div class="form-group">
                        <label for="datepicker2" class="control-label secondary-label"><i class="fa fa-calendar-times-o"></i> Data fim:</label>
                        <input id="datepicker2" name="project_end_date" type="text" class="form-control" placeholder="Data fim" value="<?php if (!empty($project_end_date)) echo $project_end_date; ?>">
                    </div>
                    <hr>


                    <div class="form-group">
                        <h3 class="mt-2 secondary-label"><i class="fa fa-users" aria-hidden="true"></i> Membros<button type="button" class="confirm-btn pull-right edit-chevron"><i  class="fa fa-chevron-down chevron" aria-hidden="true"></i></button></h3>

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
                                    <label class="ml-2"><input name="users[]" type="checkbox" value="' . $row['user_id'] . '">' . $row['name'] . ' </label></li>
                                    </div>';
                        }
                        ?>
                        </div>
                        <hr>

                    </div>

                    <div class="form-group">
                        <label for="project-summary" class="control-label secondary-label"><i class="fa fa-info-circle"></i> Sumário:</label>
                        <textarea id="project-summary" name="project_summary" class="form-control" rows="5"></textarea>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="btn-group">
                            <input id="submit-project" style="width: 130%;" type="submit" class="btn confirm-btn"  name="submit_project" value="Submeter">
                        </div>
                    </div>


                </form>
                <?php
                if($error == 1) {
                    echo '<div id="div-erro" class="bg-danger signup-error mt-2">Um projecto com este nome já existe</div>';
                }
                else if($error == 2) {
                    echo '<div id="div-erro" class="bg-danger signup-error mt-2">Por favor preencha todos os campos, o sumário é opcional</div>';
                }
                else if($error == 0) {
                    echo '<div id="div-erro" class="bg-success signup-error mt-2">O projecto foi adicionado com sucesso</div>';
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

