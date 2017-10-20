<?php
$page_title = 'Dashboard';
require_once('header.php');
require_once ('redirect.php');
require_once ('navbar.php');


# Conexão à BD
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or header('Location: ' . $error_url . '?erro=1');

$query = "SELECT iap.*, ip.project_name
          FROM intranet_assigned_project AS iap
          INNER JOIN intranet_project AS ip USING(project_id)
          WHERE iap.user_id = '" . $_SESSION['user_id'] . "' AND project_progress != 2  ORDER BY project_end_date ASC";

$data_project = mysqli_query($dbc, $query)
    or header('Location: ' . $error_url . '?erro=2');

$projects = array();


# Array de projectos
while($row_project = mysqli_fetch_array($data_project)) {
    array_push($projects, $row_project);
}



$query = "SELECT iat.*, it.task_name
          FROM intranet_assigned_task AS iat
          INNER JOIN intranet_task AS it USING(task_id)
          WHERE iat.user_id = '" . $_SESSION['user_id'] . "' AND task_progress != 2 ORDER BY task_end_date ASC";

$data_task = mysqli_query($dbc, $query)
    or header('Location: ' . $error_url . '?erro=2');

$tasks = array();

# Array de tarefas
while($row_task = mysqli_fetch_array($data_task)) {
    array_push($tasks, $row_task);
}

# Datas -> Notificações

# Data de hoje
$date = time();
$today = strtotime(date("Y-m-d"));

# Separa a data em três variáveis
$day = date('d', $date);
$month = date('m', $date);
$year = date('Y', $date);


# Notificações - Aniversários
$query = "SELECT * FROM intranet_user WHERE DAY(birthday) = '$day' AND MONTH(birthday) = '$month'";
$data_birthday = mysqli_query($dbc, $query)
    or header('Location: ' . $error_url . '?erro=2');

# Notificações - Projectos
$query = "SELECT iap.project_assign_id, iap.user_id, ip.* FROM intranet_assigned_project AS iap
  INNER JOIN intranet_project AS ip USING(project_id)
  WHERE iap.user_id = '" . $_SESSION['user_id'] . "' AND project_progress != 2";
$data_notifications_projects = mysqli_query($dbc, $query);

# Notificações - Tarefas
$query = "SELECT iat.task_assign_id, iat.user_id, it.* FROM intranet_assigned_task AS iat
INNER JOIN intranet_task AS it USING(task_id)
WHERE iat.user_id = '" . $_SESSION['user_id'] . "' AND task_progress != 2";

?>

<div class="main">

    <!-- 1st row -->
    <div class="row">
    <!-- melhorar, usar div -->
    <h2 id="top-bar" class="top-label hidden-xs"><i class="glyphicon glyphicon-list-alt ml-2" aria-hidden="true"></i> Dashboard<span id="header-span" class="pull-right">Bem vindo <i class="fa fa-user-o" aria-hidden="true"></i>
            <?php echo $_SESSION['username'];?></span></h2>


        <?php
        # Notificações - Aniversários
        if(mysqli_num_rows($data_birthday) != 0) {
            if(!isset($_COOKIE['birthday_notification'])) {
                while ($row = mysqli_fetch_array($data_birthday)) {
                    echo '
                    <div class="alert alert-dismissable fade in dashboard-notification dashboard-notification-green">
                        <a href="#"  class="close mr-2" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong><i class="fa fa-birthday-cake" aria-hidden="true"></i></strong> Hoje é o aniversário de ' . $row['name'] . '
                    </div>';
                }
                setcookie('birthday_notification', 'notificado', time() + 3600);
            }
        }
        # Notificações - Projectos
        if(mysqli_num_rows($data_notifications_projects) != 0) {
            if(!isset($_COOKIE['project_notification'])) {
                while ($row = mysqli_fetch_array($data_notifications_projects)) {

                    $project_end_date = strtotime($row['project_end_date']);
                    $datediff = $project_end_date - $today;
                    $datediff = floor($datediff / (60 * 60 * 24));

                    # Verificar se o projecto acaba dentro de 5 dias
                    if($datediff <= 5 && $datediff >= 0) {
                        if($datediff > 0) {
                            echo '
                        <div style="background-color: orange;" class="alert alert-dismissable fade in dashboard-notification">
                            <a href="#" class="close mr-2" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong><i class="fa fa-line-chart" aria-hidden="true"></i></strong> O projecto ' . $row['project_name'] . ' acaba em ' . $datediff . ' dia(s)  
                        </div>';
                        }
                        # É suposto o projecto acabar hoje
                        else {
                            echo'
                        <div class="alert alert-dismissable fade in dashboard-notification">
                            <a href="#" class="close mr-2" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong><i class="fa fa-line-chart" aria-hidden="true"></i></strong> O projecto ' . $row['project_name'] . ' acaba hoje !
                        </div>';
                        }
                    }
                }
                setcookie('project_notification', 'notificado', time() + 3600);
            }
        }

        ?>
    </div>

    <div class="container" >

        <!-- 2nd row -->
        <div class="row mt-10">

            <!-- 1st col -->
            <div class="col-lg-6">

                <div class="card" >
                    <img class="card-img-top img-responsive dashboard-img" src="images/project.jpg" alt="Card image cap">
                    <div class="card-block">
                        <h3 class="card-title text-center main-label dashboard-list-header mt-4"><strong><i class="fa fa-line-chart" aria-hidden="true"></i> Os meus projectos</strong><button id="dashboard-chevron" type="button" class="confirm-btn pull-right"><i class="fa fa-chevron-down chevron" aria-hidden="true"></i></button></h3>

                        <div id="projects-div">
                        <?php
                        if ($projects !== NULL) {
                            echo '<ul class="list-group">';
                            foreach ($projects as $project) {
                                echo '<li class="list-group-item edit-wiki-list"><a href="project.php?project_id=' . $project['project_id'] . '"><i class="fa fa-arrow-right" aria-hidden="true"></i> ' . $project['project_name'] . '</a></li>';
                            }
                            echo '</ul>';
                        }
                        ?>
                        </div> <!-- projects div (list) -->
                    </div>
                </div>

            </div>

            <!-- 2nd column -->
            <div class="col-lg-6">
                <div class="card" >
                    <img class="card-img-top img-responsive dashboard-img" src="images/task.jpg" alt="Card image cap">
                    <div class="card-block">
                        <h3 class="card-title main-label text-center dashboard-list-header mt-4"><strong><i class="fa fa-tasks" aria-hidden="true"></i> As minhas tarefas</strong><button type="button" id="dashboard-chevron2" class="confirm-btn pull-right"><i class="fa fa-chevron-down chevron2" aria-hidden="true"></i></button></h3>

                        <div id="tasks-div">
                        <?php
                        if ($tasks !== NULL) {
                            echo '<ul class="list-group">';
                            foreach ($tasks as $task) {
                                $query ="SELECT it.task_name, ip.project_name 
                                          FROM intranet_task AS it
                                          INNER JOIN intranet_project AS ip USING(project_id) 
                                          WHERE it.task_id = '" . $task['task_id'] . "'";
                                $data = mysqli_query($dbc, $query);
                                $fetch = mysqli_fetch_assoc($data);
                                $value = $fetch['project_name'];
                                echo '<li class="list-group-item edit-wiki-list"><a href="task.php?task_id= ' . $task['task_id'] . '"><i class="fa fa-arrow-right" aria-hidden="true"></i> ' . $task['task_name'] . ' (' . $fetch['project_name'] . ')</li>';
                            }
                            echo '</ul>';
                        }
                        ?>
                        </div> <!-- tasks div(list) -->

                    </div>
                </div>

            </div>


        </div> <!-- end 2nd row -->

        <!-- 3rd row -->
        <div class="row">
            <div class="col-lg-6">
                <?php

                ?>
            </div>
        </div> <!-- 3rd row -->

    </div> <!-- end container -->



</div> <!-- end main -->




<?php
require_once('footer.php');
?>










