<?php
$page_title = 'Editar Projecto';
require_once('header.php');
require_once ('navbar.php');

$error = 5;

if(isset($_POST['submit_edit_project'])) {

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('<h3>Falha ao conectar à base de dados</h3>');

    $project_name = mysqli_real_escape_string($dbc, trim($_POST['edit_project_name']));
    $project_start_date = mysqli_real_escape_string($dbc, trim($_POST['edit_project_start_date']));
    $project_end_date = mysqli_real_escape_string($dbc, trim($_POST['edit_project_end_date']));
    $project_id = mysqli_real_escape_string($dbc, trim($_POST['project_id']));
    $project_summary = mysqli_real_escape_string($dbc, trim($_POST['edit_project_summary']));
    $add_user_name = mysqli_real_escape_string($dbc, trim($_POST['add_user']));


    if (!empty($project_name) && !empty($project_start_date) && !empty($project_end_date)) {

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

                # Verificar se este utilizador já se encontra relacionado com este projecto
                $query = "SELECT * FROM intranet_assigned_project WHERE user_id = '$value' AND project_id = '$project_id'";
                $data = mysqli_query($dbc, $query);

                # Caso não esteja, relacioná-lo
                if (mysqli_num_rows($data) == 0) {

                    $query = "INSERT INTO intranet_assigned_project(user_id, project_id)" .
                        "VALUES('$value', '$project_id')";

                    mysqli_query($dbc, $query);

                    # Fazer update ao estado do projecto para "em progresso"
                    $query = "UPDATE intranet_project SET project_progress = 1 WHERE project_id = '$project_id'";
                    mysqli_query($dbc, $query);
                } else {
                    $error = 3; // o utilizador já se encontra relacionado com este projecto
                }

            }
        }

        # Só actualizar os restantes dados caso não existam outros problemas
        if ($error != 3 && $error != 4) {
            $query = "UPDATE intranet_project SET project_name = '$project_name', project_start_date = '$project_start_date', 
              project_end_date = '$project_end_date', project_summary = '$project_summary' WHERE project_id = '$project_id'";

            mysqli_query($dbc, $query)
                or die('<h3>Falha ao comunicar com a base de dados</h3>');


            $error = 1; // correu tudo bem
        }


    } else {
        $error = 2; // campos vazios
    }


} // form submit


?>
<div class="main" >

    <!-- 1st row -->
    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-wrench ml-2" aria-hidden="true"></i> Administração<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->

    <div class="container" >

        <div class="row mt-6 mb-3" id="section-edit-project">

            <div class="col-sm-6 col-sm-offset-3">

                <?php
                if (isset($_GET['project_id'])) {
                    $project_id = $_GET['project_id'];

                    # Variável de sessão com o ID do projecto a editar
                    $_SESSION['project_id'] = $project_id;

                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                    or die('<h3>Falha ao conectar à base de dados</h3>');

                    $query_project = "SELECT * FROM intranet_project WHERE project_id = '$project_id'";

                    $query_assigned = "SELECT iap.user_id, iu.username, iu.name 
                              FROM intranet_assigned_project AS iap
                              INNER JOIN intranet_user AS iu USING(user_id)
                              WHERE project_id = '$project_id'";

                    $query_users = "SELECT user_id, name FROM intranet_user";

                    $data_project = mysqli_query($dbc, $query_project)
                        or die('<h3>Falha ao comunicar com a base de dados</h3>');

                    $data_assigned = mysqli_query($dbc, $query_assigned)
                        or die('<h3>Falha ao comunicar com a base de dados</h3>');

                    $data_users = mysqli_query($dbc, $query_users)
                        or die('<h3>Falha ao comunicar com a base de dados</h3>');


                    $row_project = mysqli_fetch_assoc($data_project);


                    echo '<h2 class="top-label mb-2"><i class="fa fa-line-chart" aria-hidden="true"></i> ' . $row_project['project_name'] . ' </h2>';
                }
                ?>
                <form method="POST" id="edit-project-form" class="test-form" action="edit_project.php?project_id=<?php echo $project_id;?>">
                    <input type="hidden" name="project_id" value="<?php echo $project_id;?>">
                    <div class="form-group">
                        <label for="edit-project-name"><i class="fa fa-clipboard" aria-hidden="true"></i> Nome:</label>
                        <input id="edit-project-name" name="edit_project_name" class="form-control test-input" value="<?php echo $row_project['project_name'];?>">
                    </div>
                    <div class="form-group">
                        <label for="edit-project-start-date"><i class="fa fa-calendar" aria-hidden="true"></i> Data Ínicio:</label>
                        <input id="edit-project-start-date" name="edit_project_start_date" class="form-control test-input" value="<?php echo $row_project['project_start_date'];?>">
                    </div>
                    <div class="form-group">
                        <label for="edit-project-end-date"><i class="fa fa-calendar-times-o" aria-hidden="true"></i> Data fim:</label>
                        <input id="edit-project-end-date" name="edit_project_end_date" class="form-control" value="<?php echo $row_project['project_end_date'];?>">
                    </div>
                    <hr>

                    <h3 class="mt-2"><i class="fa fa-edit" aria-hidden="true"></i> Adicionar Membros <button type="button" class="confirm-btn pull-right edit-chevron2"><i class="fa fa-chevron-down chevron2" aria-hidden="true"></i></button></h3>

                    <div id="add-member-div">
                        <div style="width: 100%;" class="search-box ajax-search-box">
                            <input class="search-box-input" name="add_user" type="text" autocomplete="off" placeholder="Procurar utilizador.." />

                            <div class="result ajax-result">
                            </div>
                        </div>
                    </div> <!-- end add member div -->
                    <?php
                    echo '<hr>';

                    # Secção membros do projecto
                    echo '<h3 style="margin-top: 2rem;"><i class="fa fa-users" aria-hidden="true"></i> Membros<button type="button" class="confirm-btn pull-right edit-chevron"><i class="fa fa-chevron-down chevron" aria-hidden="true"></i></button></h3>';
                    while ($row_users = mysqli_fetch_array($data_assigned)) {
                        echo '<li  style="background-color: inherit;" class="list-group-item user-list"><i class="fa fa-user" aria-hidden="true"></i> ' . $row_users['name'] . '<a href="processar_gestor.php?user_id=' . $row_users['user_id'] . '&project_id=' . $project_id . '&delete=1"><i class="fa fa-times-rectangle pull-right" aria-hidden="true"></i></a></li>';
                    }

                    ?>

                    <hr>
                    <div class="form-group" style="margin-top: 1rem;">
                        <label for="edit-project-summary"><i class="fa fa-info-circle" aria-hidden="true"></i> Sumário:</label>
                        <textarea rows="5" id="edit-project-summary" name="edit_project_summary" class="form-control"><?php echo $row_project['project_summary'];?></textarea>
                    </div>

                </form>

                <!-- BUTTON GROUP -->
                <div class="text-center">
                    <div class="btn-group">
                        <button type="submit" name="submit_edit_project" form="edit-project-form" id="edit-project-btn" style=" margin-top: 2rem; margin-left: 2rem;" class="btn confirm-btn"><i class="fa fa-edit" aria-hidden="true"></i> Submeter</button>
                    </div>
                </div>


                <?php

                # A form foi submetida com sucesso e os dados do projecto foram actualizados
                if($error == 1) {
                    echo '<div id="div-erro" class="bg-success text-center mt-2">Projecto editado com successo</div>';
                }
                else if($error == 2) {
                    echo '<div id="div-erro" class="bg-danger text-center mt-2">Alguns campos críticos foram deixados em branco</div>';

                }
                else if($error == 3) {
                    echo '<div id="div-erro" class="bg-danger text-center mt-2">O utilizador já se encontra associado a este projecto</div>';
                }
                else if($error == 4) {
                    echo '<div id="div-erro" class="bg-danger text-center mt-2">O utilizador não existe</div>';
                }

                # Verifica se a variável de sessão existe
                if(isset($_SESSION['project_edit'])) {

                    # O utilizador foi inserido no projecto
                    if($_SESSION['project_edit'] == 1) {
                        echo '<div id="div-erro" class="bg-success text-center mt-2">O utilizador foi introduzido no projecto com sucesso</div>';
                        unset($_SESSION['project_edit']);
                    }

                    # O utilizador já está inserido neste projecto
                    else if($_SESSION['project_edit'] == 2) {
                        echo '<div id="div-erro" class="bg-danger text-center mt-2">O utilizador já se encontra associado a este projecto</div>';
                        unset($_SESSION['project_edit']);
                    }

                    # O utilizador foi removido do projecto
                    else if($_SESSION['project_edit'] == 3) {
                        echo '<div id="div-erro" class="bg-success text-center mt-2">O utilizador foi removido com sucesso do projecto</div>';
                        unset($_SESSION['project_edit']);
                    }
                    # O utilizador não faz parte deste projecto
                    else if($_SESSION['project_edit'] == 4) {
                        echo '<div id="div-erro" class="bg-danger text-center mt-2">O utilizador não faz parte deste projecto</div>';
                        unset($_SESSION['project_edit']);
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

        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>


