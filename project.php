<?php
$page_title = 'Ver projecto';
require_once('header.php');
require_once ('navbar.php');
?>
<div class="main">

    <!-- 1st row -->
    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-briefcase ml-2" aria-hidden="true"></i> Gestor de projectos<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->

    <div class="container" >

        <div class="row mt-6 mb-3" id="section-see-project">

            <div class="col-sm-6 col-sm-offset-3">

                <?php
                if(isset($_GET['project_id'])) {

                    $project_id = $_GET['project_id'];
                    $user_id = $_SESSION['user_id'];

                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                        or die('<h3>Falha ao conectar à base de dados</h3>');

                    $query = "SELECT * FROM intranet_project WHERE project_id = '$project_id'";

                    $query2 = "SELECT iap.user_id, iu.username 
                              FROM intranet_assigned_project AS iap
                              INNER JOIN intranet_user AS iu USING(user_id)
                              WHERE project_id = '$project_id'";

                    $query3= "SELECT iat.*, it.task_name
                            FROM intranet_assigned_task AS iat
                            INNER JOIN intranet_task AS it USING(task_id)
                            INNER JOIN intranet_user AS iu USING(user_id)
                            WHERE it.project_id = '$project_id' AND iat.user_id = '$user_id'";

                    $data_project = mysqli_query($dbc, $query)
                    or die('<h3>Falha ao comunicar com base de dados</h3>');

                    $data_users = mysqli_query($dbc, $query2)
                    or die('<h3>Falha ao comunicar com base de dados</h3>');

                    $data_tasks = mysqli_query($dbc, $query3)
                        or die('<h3>Falha ao comunicar com a base de dados</h3>');

                    $row_project = mysqli_fetch_array($data_project);

                    if ($row_project == NULL) {
                        echo '<h2 class="mt-2" style="width: 80%; margin: 0 auto;">Projecto inexistente <i class="fa fa-thumbs-down" aria-hidden="true"></i> </h2>';
                        exit();
                    }

                    echo '<ul class="list-group"><h2 class="top-label"><i class="fa fa-line-chart" aria-hidden="true"></i> ' . $row_project['project_name'] . ' </h2>';

                    echo '<h4 class="main-label mt-2"><i class="fa fa-calendar" aria-hidden="true"></i> Datas</h4>';
                    echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> ' . $row_project['project_start_date'] . '</li>';
                    echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-calendar-times-o" aria-hidden="true"></i> ' . $row_project['project_end_date'] . '</li>';

                    echo '<h4 class="main-label mt-2"><i class="fa fa-users" aria-hidden="true"></i> Utilizadores</h4>';

                    while ($row_users = mysqli_fetch_array($data_users)) {
                        echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-user" aria-hidden="true"></i> ' . $row_users['username'] . '</li>';
                    }

                    echo '<h4 class="main-label mt-2"><i class="fa fa-heartbeat" aria-hidden="true"></i> Estado</h4>';
                    if($row_project['project_progress'] == 0)
                        echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Por atribuir</li>';
                    else if($row_project['project_progress'] == 1)
                        echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-spinner fa-spin fa-fw"></i> Em progresso</li>';
                    else if($row_project['project_progress'] == 2)
                        echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-check-circle" aria-hidden="true"></i> Concluído</li>';


                    echo '<h4 class="main-label mt-2"><i class="fa fa-info-circle" aria-hidden="true"></i> Sumário</h4>';
                    if($row_project['project_summary'] == NULL)
                        echo '<li class="list-group-item edit-wiki-list"> Sem sumário</li>';
                    else
                        echo '<li class="list-group-item edit-wiki-list"> '  . $row_project['project_summary'] . '</li>';

                    echo '<h4 class="main-label mt-2"><i class="fa fa-tasks" aria-hidden="true"></i> Tarefas</h4>';

                    $num = mysqli_num_rows($data_tasks);
                    if($num <= 0)
                        echo '<li class="list-group-item edit-wiki-list">Sem tarefas associadas</li>';
                    else {
                        while ($row_tasks = mysqli_fetch_array($data_tasks)) {
                            echo '<li class="list-group-item edit-wiki-list"><a href="task.php?task_id=' . $row_tasks['task_id'] . '"><i class="fa fa-arrow-right" aria-hidden="true"></i> ' . $row_tasks['task_name'] . '</a></li>';

                        }
                    }
                    echo '</ul>';

                } // get id
                ?>

                <!-- BUTTON GROUP -->
                <div class="text-center">
                    <div class="btn-group">
                        <a href="processar_gestor.php?project_id=<?php echo $project_id;?>&complete=1" id="delete-project-btn" class="btn confirm-btn complete-project-btn mt-2 ml-2"><i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;&nbsp; Concluír&nbsp;&nbsp;</a>
                        <a href="processar_gestor.php?project_id=<?php echo $project_id;?>&progress=1" id="progresss-project-btn" class="btn confirm-btn progress-project-btn mt-2 ml-2"><i class="fa fa-bolt" aria-hidden="true"></i>&nbsp;&nbsp; Colocar em progresso&nbsp;&nbsp;</a>
                        <a href="edit_project.php?project_id=<?php echo $project_id ;?>" id="edit-project-btn" class="btn confirm-btn mt-2 ml-2"><i class="fa fa-edit" aria-hidden="true"></i> Editar projecto</a>

                    </div>
                </div> <!-- end button group -->

            </div> <!-- end col -->

        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>