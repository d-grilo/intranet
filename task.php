<?php
$page_title = 'Ver tarefa';
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
                if(isset($_GET['task_id'])) {

                    $task_id = $_GET['task_id'];

                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                        or die('<h3>Falha ao conectar à base de dados</h3>');

                    $query = "SELECT it.*, ip.project_name 
                              FROM intranet_task AS it
                              INNER JOIN intranet_project AS ip USING(project_id) 
                              WHERE it.task_id = '$task_id'";

                    $data_task = mysqli_query($dbc, $query)
                        or die('<h3>Falha ao comunicar com base de dados</h3>');

                    $row_task = mysqli_fetch_array($data_task);

                    if($row_task == NULL) {
                        echo '<h2 class="mt-2" style="width: 80%; margin: 0 auto;">Tarefa inexistente <i class="fa fa-thumbs-down" aria-hidden="true"></i> </h2>';
                        exit();
                    }

                    echo '<ul class="list-group"><h2 class="top-label"><i class="fa fa-tasks" aria-hidden="true"></i> ' . $row_task['task_name'] . ' </h2>';

                    echo '<h4 class="main-label mt-2"><i class="fa fa-calendar" aria-hidden="true"></i> Datas</h4>';
                    echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> ' . $row_task['task_start_date'] . '</li>';
                    echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-calendar-times-o" aria-hidden="true"></i> ' . $row_task['task_end_date'] . '</li>';

                    echo '<h4 class="main-label mt-2"><i class="fa fa-info-circle" aria-hidden="true"></i> Sumário</h4>';
                    if($row_task['task_summary'] == NULL)
                        echo '<li class="list-group-item edit-wiki-list"> Sem sumário</li>';
                    else
                        echo '<li class="list-group-item edit-wiki-list"> '  . $row_task['task_summary'] . '</li>';

                    echo '<h4 class="main-label mt-2"><i class="fa fa-heartbeat" aria-hidden="true"></i> Estado</h4>';
                    if($row_task['task_progress'] == 0)
                        echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Por atribuir</li>';
                    else if($row_task['task_progress'] == 1)
                        echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-spinner fa-spin fa-fw"></i> Em progresso</li>';
                    else if($row_task['task_progress'] == 2)
                        echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-check-circle" aria-hidden="true"></i> Concluída</li>';

                    echo '<h4 class="main-label mt-2"><i class="fa fa-tasks" aria-hidden="true"></i> Projecto</h4>';
                    echo '<li class="list-group-item edit-wiki-list"><a href="project.php?project_id=' . $row_task['project_id'] . '"><i class="fa fa-line-chart" aria-hidden="true"></i> '  . $row_task['project_name'] . '</a></li>';

                    echo '</ul>';
                }
                ?>

                <!-- BUTTON GROUP -->
                <div class="text-center">
                    <div class="btn-group">
                        <a href="processar_gestor.php?task_id=<?php echo $task_id;?>&complete=1" id="delete-task-btn" class="btn confirm-btn complete-task-btn mt-2 ml-2"><i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp;&nbsp; Completar&nbsp;&nbsp;</a>
                        <a href="edit_task.php?task_id=<?php echo $task_id;?>" id="edit-task-btn" class="btn confirm-btn mt-2 ml-2"><i class="fa fa-edit" aria-hidden="true"></i> Editar tarefa</a>
                    </div>
                </div>

            </div> <!-- end col -->

        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>