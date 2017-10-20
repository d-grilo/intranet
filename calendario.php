<?php
error_reporting(0);
$page_title = 'Calendário';
require_once('header.php');
require_once ('navbar.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
or die('<h3 style="color red;">Falha ao conectar à base de dados');

$query_projects = "SELECT iap.*, ip.project_name, 
        YEAR(ip.project_start_date) AS project_ano_inicio, MONTH(ip.project_start_date) AS project_mes_inicio, DAY(ip.project_start_date) AS project_dia_inicio, 
        YEAR(ip.project_end_date) AS project_ano_fim, MONTH(ip.project_end_date) AS project_mes_fim, DAY(ip.project_end_date) AS project_dia_fim , iu.username 
        FROM intranet_assigned_project AS iap
        INNER JOIN intranet_project AS ip USING(project_id)
        INNER JOIN intranet_user AS iu USING(user_id)
        WHERE iap.user_id = '" . $_SESSION['user_id'] . "'";

$query_tasks = "SELECT iat.task_assign_id, iat.task_id, iat.user_id AS task_assign_id, it.task_name,
                YEAR(it.task_start_date) AS task_ano_inicio, MONTH(it.task_start_date) AS task_mes_inicio, DAY(it.task_start_date) AS task_dia_inicio,
                YEAR(it.task_end_date) AS task_ano_fim, MONTH(it.task_end_date) AS task_mes_fim, DAY(it.task_end_date) AS task_dia_fim,
                iu.username AS task_username
                FROM intranet_assigned_task AS iat
                INNER JOIN intranet_task AS it USING(task_id) 
                INNER JOIN intranet_user AS iu USING(user_id)
                WHERE iat.user_id = '" . $_SESSION['user_id'] . "'";

$query_birthdays = "SELECT ic.color_hex, color, name AS aniversariante, MONTH(birthday) AS birthday_month, DAY(birthday) AS birthday_day FROM intranet_user
INNER JOIN intranet_color AS ic ON intranet_user.color = ic.color_id;";

$query_vacations = "SELECT ic.color_hex, iu.color, iu.name AS user_em_ferias, iu.user_id, iv.user_id,
YEAR(iv.vacation_start_date) AS vacation_ano_inicio, MONTH(iv.vacation_start_date) AS vacation_mes_inicio, DAY(iv.vacation_start_date) AS vacation_dia_inicio,
YEAR(iv.vacation_end_date) AS vacation_ano_fim, MONTH(iv.vacation_end_date) AS vacation_mes_fim, DAY(iv.vacation_end_date) AS vacation_dia_fim
FROM intranet_vacation AS iv
INNER JOIN intranet_user AS iu USING(user_id)
INNER JOIN intranet_color AS ic ON iu.color = ic.color_id";

$query_notes = "SELECT note_id, note_content, note_type, user_id AS note_user, iu.name AS note_username,
 DAY(note_date) AS note_day, MONTH(note_date) AS note_month, YEAR(note_date) AS note_year
 FROM intranet_note
INNER JOIN intranet_user AS iu USING(user_id)";


$data_projectos = mysqli_query($dbc, $query_projects)
or die('Erros graves: projects');

$data_tasks = mysqli_query($dbc, $query_tasks)
or die('Erros graves: tasks');

$data_birthday = mysqli_query($dbc, $query_birthdays)
or die('Erros graves: birthdays');

$data_vacation = mysqli_query($dbc, $query_vacations)
or die('Erros graves: vacations');

$data_note = mysqli_query($dbc, $query_notes)
or die('Erros graves: notes');

?>

<div class="main" >

    <!-- 1st row -->
    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-calendar ml-2" aria-hidden="true"></i> Calendário<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->


    <div class="container" >

        <div class="row mt-3 mb-3" id="section-calendar">

            <ol style="background-color: #1A2129;" class="breadcrumb mt-5">
                <li class="active">Vista mensal</li>
                <li><a href="vista_anual.php">Vista anual (férias)</a></li>
            </ol>

            <?php



            /**************** VARIÁVEIS DE CALENDÁRIO ********************/

            # Data de hoje
            $date = time();

            # Separa a data em três variáveis
            $day = date('d', $date);

            # GET MES
            if(!isset($_GET['month'])) {
                $month = date('m', $date);

            }
            else {
                $month = $_GET['month'];
            }

            if(!isset($_GET['month'])) {
                $year = date('Y', $date);
            }
            else {
                $year = $_GET['year'];
            }

            $mes_anterior = $month - 1;
            $ano_anterior = $year;

            if($mes_anterior <= 0) {
                $mes_anterior = 12;
                $ano_anterior = $year - 1;
            }


            $mes_aseguir = $month + 1;
            $ano_aseguir = $year;

            if($mes_aseguir > 12) {
                $mes_aseguir = 1;
                $ano_aseguir = $year + 1;
            }

            # Gerar o primeiro dia do mês
            $first_day = mktime(0,0,0, $month, 1, $year);

            # Nome do mês
            $title = date('F', $first_day);

            /******************* DIAS DA SEMANA ************************/

            # Descobrir que dia da semana o primeiro dia do mês é
            $day_of_week = date('D', $first_day);

            # Quando soubermos em que dia calha, sabemos quantos dias em branco calham antes deles
            # Se o primeiro dia da semana for Domingo, então é zero.

            switch($day_of_week) {
                case "Sun": $blank = 0; break;
                case "Mon": $blank = 1; break;
                case "Tue": $blank = 2; break;
                case "Wed": $blank = 3; break;
                case "Thu": $blank = 4; break;
                case "Fri": $blank = 5; break;
                case "Sat": $blank = 6; break;
            }

            switch($title) {
                case "January": $title = 'Janeiro'; break;
                case "February": $title = 'Fevereiro'; break;
                case "March": $title = 'Março'; break;
                case "April": $title = 'Abril'; break;
                case "May": $title = 'Maio'; break;
                case "June": $title = 'Junho'; break;
                case "July": $title = 'Julho'; break;
                case "August": $title = 'Agosto'; break;
                case "September": $title = 'Setembro'; break;
                case "October": $title = 'Outubro'; break;
                case "November": $title = 'Novembro'; break;
                case "December": $title = 'Dezembro'; break;
            }

            # Determinar quantos dias o mês actual tem
            $days_in_month = cal_days_in_month(0, $month, $year);

            /******************* HEADINGS E DIAS EM BRANCO *******************************/

            //echo '<h2 style="border-bottom: solid 1px white; margin-bottom: 2rem;" ><i class="fa fa-calendar" aria-hidden="true"></i> Calendário</h2>';
            # Começar a construir a tabela
            echo '<div class="table-responsive mt-3">';
            echo '<table class="table calendar" border=1 width=294>';
            echo "<tr><th class='text-center' style=\"background-color:  #1A2129\" colspan=7>
                <a class='pull-left' href='calendario.php?month=$mes_anterior&year=$ano_anterior'><i class=\"fa fa-arrow-left\" aria-hidden=\"true\"></i></a>$title $year <a class='pull-right' href='calendario.php?month=$mes_aseguir&year=$ano_aseguir'><i class=\"fa fa-arrow-right\" aria-hidden=\"true\"></i></a></th></tr>";
            echo "<tr>
                <td class='bg-yellowgreen' width=42><span class='g-color'>Domingo</span></td>
                <td class='bg-yellowgreen' width=42><span class='g-color'>Segunda</span></td>
                <td class='bg-yellowgreen' width=42><span class='g-color'>Terça</span></td>
                <td class='bg-yellowgreen' width=42><span class='g-color'>Quarta</span></td>
                <td class='bg-yellowgreen' width=42><span class='g-color'>Quinta</span></td>
                <td class='bg-yellowgreen' width=42><span class='g-color'>Sexta</span></td>
                <td class='bg-yellowgreen' width=42><span class='g-color'>Sábado</span></td>
                </tr>";

            # Contar os dias da semana, até 7
            $day_count = 1;

            echo "<tr>";

            # Primeiro, mostrar os dias em branco
            while ($blank > 0) {
                echo "<td></td>";
                $blank = $blank - 1;
                $day_count++;
            }

            /************************* DIAS DO MÊS ******************************/

            # Primeiro dia do mês = 1
            $day_num = 1;

            # -> (below) Contar os dias do mês até os ter todos

            # Criar arrays
            $projectos = array();
            $tarefas = array();
            $birthdays = array();
            $vacations = array();
            $notes = array();


            # Popular o respectivo array com a informação de cada query
            while($row = mysqli_fetch_array($data_projectos)) {
                array_push($projectos, $row);
            }

            while($row = mysqli_fetch_array($data_tasks)) {
                array_push($tarefas, $row);
            }

            while($row = mysqli_fetch_array($data_birthday)) {
                array_push($birthdays, $row);
            }

            while($row = mysqli_fetch_array($data_vacation)) {
                array_push($vacations, $row);
            }

            while($row = mysqli_fetch_array($data_note)) {
                array_push($notes, $row);
            }


            # Juntar todos os arrays num array de eventos
            $eventos = array_merge($projectos, $tarefas, $birthdays, $vacations, $notes);


            while ($day_num <= $days_in_month) {

                echo '<td class="calendar-hover">';

                echo  '<a data-toggle="modal" data-id="' . $day_num . '" href="#view_contact" class="view-admin">' . '<span style="font-size: medium;">' . $day_num . '</span></a><br>';

                foreach($eventos as $evento) {
                    # Popula o calendário com a informação dos projectos
                    if($evento['project_dia_inicio'] == $day_num && $evento['project_mes_inicio'] == $month && $evento['project_ano_inicio'] == $year) {
                        echo '<a href="project.php?project_id=' . $evento['project_id'] . '"> <i class="fa fa-line-chart" aria-hidden="true"></i> ' . $evento['project_name'] . '</a><br>';
                    }
                    else if($evento['project_dia_fim'] == $day_num && $evento['project_mes_fim'] == $month && $evento['project_ano_fim'] == $year) {
                        echo ' <i class="fa fa-calendar-times-o" aria-hidden="true"></i> ' . $evento['project_name'] . '<br>';
                    }
                    # Popula calendário com a informação das tarefas
                    if($evento['task_dia_inicio'] == $day_num && $evento['task_mes_inicio'] == $month && $evento['task_ano_fim'] == $year) {
                        echo ' <a href="task.php?task_id=' . $evento['task_id'] . '"> <i class="fa fa-tasks" aria-hidden="true"></i> ' . $evento['task_name'] . '</a><br>';
                    }
                    else if($evento['task_dia_fim'] == $day_num && $evento['task_mes_fim'] == $month && $evento['task_ano_fim'] == $year) {
                        echo ' <i class="fa fa-calendar-times-o" aria-hidden="true"></i> ' . $evento['task_name'] . '<br>';
                    }
                    # Popula calendário com informação de aniversário
                    if($evento['birthday_day'] == $day_num && $evento['birthday_month'] == $month) {
                        echo '<span style="color: ' . $evento['color_hex'] . '"><i class="fa fa-birthday-cake" aria-hidden="true"></i> ' . $evento['aniversariante'] . '</span><br>';
                    }
                    if($day_num >= $evento['vacation_dia_inicio'] && $day_num <= $evento['vacation_dia_fim'] && $month == $evento['vacation_mes_inicio'] && $year == $evento['vacation_ano_inicio'] ) {
                        echo '<span style="color: ' . $evento['color_hex'] . '"><i class="fa fa-palm-tree" aria-hidden="true"></i> ' . $evento['user_em_ferias'] . '</span><br>';
                    }
                    else if ($evento['vacation_mes_fim'] != $evento['vacation_mes_inicio']) {
                        if($evento['vacation_dia_inicio'] <= $day_num && $evento['vacation_mes_inicio'] == $month && $year == $evento['vacation_ano_inicio']) {
                            echo '<span style="color: ' . $evento['color_hex'] . '"><i class="fa fa-palm-tree" aria-hidden="true"></i> ' . $evento['user_em_ferias'] . '</span><br>';

                        }
                        if($evento['vacation_dia_fim'] >= $day_num && $evento['vacation_mes_fim'] == $month && $year == $evento['vacation_ano_fim']) {
                            echo '<span style="color: ' . $evento['color_hex'] . '"><i class="fa fa-palm-tree" aria-hidden="true"></i> ' . $evento['user_em_ferias'] . '</span><br>';

                        }
                    }
                    # Popula calendário com a informação das notas
                    if ($evento['note_day'] == $day_num && $evento['note_month'] == $month && $evento['note_year'] == $year && $evento['note_type'] == 1 && $evento['note_user'] == $_SESSION['user_id']) {
                        echo '<a class="hover-hand" data-toggle="modal" data-target="#view-modal-note" data-id="' . $evento['note_id'] . '" id="openNote"><i class="glyphicon glyphicon-pushpin" aria-hidden="true"></i></a><br>';

                    }
                    else if($evento['note_day'] == $day_num && $evento['note_month'] == $month && $evento['note_year'] == $year && $evento['note_type'] == 2) {
                        echo '<a class="hover-hand" data-toggle="modal" data-target="#view-modal-note" data-id="' . $evento['note_id'] . '" id="openNote"><i class="glyphicon glyphicon-pushpin" aria-hidden="true"></i> ' . $evento['note_username'] . '</a><br>';
                    }


                } // end foreach


                echo "</td>";

                $day_num++;
                $day_count++;

                # Começar uma nova tr a cada semana
                if ($day_count > 7) {
                    echo "</tr><tr>";
                    $day_count = 1;
                }

            }



            /***************** ACABAR O CALENDÁRIO *******************************/
            echo "</tr></table></div>";


            ?>

            <a data-toggle="modal" data-id="1" href="#view_contact" class="view-admin"></a>

            <div style="color: #1a2129;" class="modal fade" id="view_contact" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header main-label white-text">
                            <h4><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Novo evento<i class="fa fa-lg fa-times-circle pull-right modal-img" data-dismiss="modal"></i></h4>

                        </div>

                        <div class="modal-body">
                            <?php $selected_day ='<a style="color: black;" id="showid"></a>';?>

                            <div class="col-sm-3 text-center"><img id="project-img" class="img-circle modal-img"  style="height:220px; max-width: 110%;" src="images/project.jpg" /></><br><span class="ml-2"><br><strong>Projecto</strong></span></div>
                            <div class="col-sm-3 text-center"><img id="task-img" class="img-circle modal-img" style="height:220px; max-width: 110%;" src="images/task.jpg" /><br> <span class="ml-2"><br><strong>Tarefa</strong></span></div>
                            <div class="col-sm-3 text-center"><img id="note-img" class="img-circle modal-img" style="height:220px; max-width: 110%;" src="images/note.png" /><br> <span class="ml-2"><br><strong>Nota</strong></span></div>
                            <div class="col-sm-3 text-center"><img id="vacation-img" class="img-circle modal-img" style="height:220px; max-width: 110%;" src="images/vacation.jpg" /><br> <span class="ml-2"><br><strong>Férias</strong></span></div>

                        <!-- Variáveis em javascript (data)-->
                        <script>
                            year = '<?php echo $year ;?>';
                            month = '<?php echo $month; ?>';
                        </script>


                            <div class="clearfix"></div>

                            <!-- PROJECT DIV --->

                            <div id="project-div" class="selection-div mt-5"  style="display: none;">
                                <form method="post" id="modal-project-form" action="#">
                                    <div class="form-group">
                                        <label for="project-name" class="control-label"><i class="fa fa-clipboard"></i> Nome:</label>
                                        <input id="project-name" name="project_name" type="text" class="form-control" placeholder="Nome do projecto" value="<?php if (!empty($project_name)) echo $project_name; ?>">
                                    </div>

                                    <hr>

                                    <div class="form-group">
                                        <label for="modal_project_start_date" class="control-label"><i class="fa fa-calendar"></i> Data ínicio:</label>
                                        <input id="modal_project_start_date" name="project_start_date" type="text" class="form-control datepicker" placeholder="Data de ínicio" value="">
                                    </div>

                                    <div class="form-group">
                                        <label for="modal_project_end_date" class="control-label"><i class="fa fa-calendar-times-o"></i> Data fim:</label>
                                        <input id="modal_project_end_date" name="project_end_date" type="text" class="form-control datepicker2" placeholder="Data fim" value="<?php if (!empty($project_end_date)) echo $project_end_date; ?>">
                                    </div>

                                    <hr>

                                    <div class="form-group">
                                        <h3 class="mt-2"><i class="fa fa-users" aria-hidden="true"></i> Membros<button type="button" class="confirm-btn pull-right edit-chevron"><i  class="fa fa-chevron-down chevron" aria-hidden="true"></i></button></h3>

                                        <?php
                                        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                            or die('<h3>Falha ao conectar à base de dados</h3>');

                                        $query_users = "SELECT * FROM intranet_user";

                                        $data_users = mysqli_query($dbc, $query_users)
                                            or die('<h3>Falha ao comunicar com a base de dados</h3>');

                                        $numero = 0;
                                        while($row = mysqli_fetch_array($data_users)) {
                                            echo '<div class="checkbox user-list">
                                            <label><input name="users[]" type="checkbox" value="' . $row['user_id'] . '">' . $row['name'] . ' </label></li>
                                            </div>';
                                        }
                                        ?>

                                        <hr>

                                    </div>

                                    <div class="form-group">
                                        <label for="project-summary" class="control-label"><i class="fa fa-info-circle"></i> Sumário:</label>
                                        <textarea id="project-summary" name="project_summary" class="form-control" rows="5"></textarea>
                                    </div>

                                    <hr>

                                    <div class="text-center">
                                        <div class="btn-group">
                                            <input id="submit-project" style="width: 130%;" type="submit" class="btn confirm-btn"  name="submit_project" value="Submeter">
                                        </div>
                                    </div>

                                </form>

                            </div> <!-- end project div -->

                            <!-- TASK DIV --->
                            <div id="task-div" class="selection-div" style="display: none;">
                                <form method="post" id="modal-task-form" action="#">
                                    <div class="form-group">
                                        <label for="task-name" class="control-label"><i class="fa fa-clipboard aria-hidden="true"></i> Nome:</label>
                                        <input id="task-name" name="task_name" type="text" class="form-control" placeholder="Nome da tarefa" value="<?php if (!empty($task_name)) echo $task_name; ?>">
                                    </div>

                                    <hr>

                                    <div class="form-group">
                                        <label for="selected-project" class="control-label"><i class="fa fa-folder"></i> Projecto:</label>
                                        <select id="selected-project" name="selected_project" class="form-control">
                                            <?php
                                            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                            or die('<h3 style="color red;">Falha ao conectar à base de dados');

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
                                        <label for="modal_task_start_date" class="control-label"><i class="fa fa-calendar"></i> Data ínicio:</label>
                                        <input id="modal_task_start_date" name="task_start_date" type="text" class="form-control datepicker" placeholder="Data de ínicio" value="<?php if (!empty($task_start_date)) echo $task_start_date; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="modal_task_end_date" class="control-label"><i class="fa fa-calendar-times-o"></i> Data fim:</label>
                                        <input id="modal_task_end_date" name="task_end_date" type="text" class="form-control datepicker2" placeholder="Data fim" value="<?php if (!empty($task_end_date)) echo $task_end_date; ?>">
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <h3 class="mt-2"><i class="fa fa-users" aria-hidden="true"></i> Membros<button type="button"  class="confirm-btn pull-right edit-chevron"><i  class="fa fa-chevron-down chevron" aria-hidden="true"></i></button></h3>

                                        <?php
                                        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                        or die('<h3>Falha ao conectar à base de dados</h3>');

                                        $query_users = "SELECT * FROM intranet_user";

                                        $data_users = mysqli_query($dbc, $query_users)
                                        or die('<h3>Falha ao comunicar com a base de dados</h3>');

                                        $numero = 0;
                                        while($row = mysqli_fetch_array($data_users)) {
                                            echo '<div class="checkbox user-list">
                                            <label><input name="users[]" type="checkbox" value="' . $row['user_id'] . '">' . $row['name'] . ' </label></li>
                                            </div>';
                                        }
                                        ?>

                                    </div>

                                    <hr>

                                    <div class="form-group">
                                        <label for="task-summary" class="control-label"><i class="fa fa-info-circle"></i> Sumário:</label>
                                        <textarea id="task-summary" name="task_summary" class="form-control" rows="5"></textarea>
                                    </div>

                                    <div class="text-center">
                                        <div class="btn-group">
                                            <input id="submit-task" style="width: 130%;" type="submit" class="btn confirm-btn"  name="submit_task" value="Submeter">
                                        </div>
                                    </div>

                                </form>
                            </div> <!-- end task div -->

                            <!-- NOTE DIV --->
                            <div id="note-div" class="selection-div" style="display: none;">

                                <form method="post" id="modal-note-form" action="#">
                                    <div class="form-group">
                                        <label for="note-type" class="control-label"><i class="fa fa-clipboard aria-hidden="true"></i> Tipo de nota:</label>
                                        <select id="note-type" name="note_type" class="form-control">
                                            <option value="1">Pessoal</option>
                                            <option value="2">Universal</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="modal_note_date" class="control-label"><i class="fa fa-calendar"></i> Data: </label>
                                        <input id="modal_note_date" name="note_date" type="text" class="form-control datepicker" placeholder="Data de ínicio" value="<?php if (!empty($task_start_date)) echo $task_start_date; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="note-content" class="control-label"><i class="fa fa-clipboard aria-hidden="true"></i> Conteúdo:</label>
                                        <textarea id="note-content" name="note_content" class="form-control" rows="5"></textarea>
                                    </div>

                                    <div class="text-center">
                                        <div class="btn-group">
                                            <input id="submit-note" style="width: 130%;" type="submit" class="btn confirm-btn"  name="submit_note" value="Criar nota">
                                        </div>
                                    </div>


                                </form>


                            </div> <!-- end note div -->

                            <!-- VACATION DIV --->
                            <div id="vacation-div" class="selection-div" style="display: none;">

                                <form method="post" id="modal-vacation-form" action="#">

                                    <div class="form-group">
                                        <div style="width: 100%;" class="search-box-vacation ajax-search-box">
                                            <label for="search-box-input" class="control-label"><i class="fa fa-clipboard aria-hidden="true"></i> Nome:</label>

                                            <?php
                                            $ii = $_SESSION['user_id'];
                                            $query = "SELECT name FROM intranet_user WHERE user_id = '$ii'";
                                            $data = mysqli_query($dbc, $query);
                                            $nom = mysqli_fetch_assoc($data);

                                            ?>

                                            <input class="search-box-input" name="username" type="text" value="<?php echo $nom['name']; ?>" autocomplete="off" placeholder="Procurar utilizador.."  />

                                            <div class="result-vacation ajax-result">
                                            </div>
                                        </div>


                                        <hr>

                                        <div class="form-group">
                                            <label for="modal_vacation_start_date" class="control-label"><i class="fa fa-calendar" aria-hidden="true"></i> Data Ínicio:</label>
                                            <input id="modal_vacation_start_date" name="vacation_start_date" type="text" class="form-control datepicker" placeholder="Data de ínicio" value="">


                                        </div>
                                        <div class="form-group">
                                            <label for="vacation-end-date" class="control-label"><i class="fa fa-calendar" aria-hidden="true"></i> Data fim:</label>
                                            <input id="vacation-end-date" name="vacation_end_date" type="text" class="form-control datepicker" value="<?php ?>">
                                        </div>

                                        <hr>

                                        <div class="text-center">
                                            <div class="btn-group">
                                                <input id="submit-vacation" style="width: 130%;" type="submit" class="btn confirm-btn"  name="submit_vacation" value="Submeter">
                                            </div>
                                        </div>

                                    </div>

                                </form>
                            </div> <!-- end vacation div -->



                        <div id="modal-error" class="signup-error mt-2"></div>


                    </div> <!-- modal body -->
                    <div class="clearfix"></div>

                    <div class="modal-footer main-label">
                        <a class="btn btn-default" data-dismiss="modal">Fechar</a>
                    </div>

                </div>

            </div>

        </div> <!-- end modal -->

        <!-- MODAL NOTE -->
        <div style="color: #1a2129;"  id="view-modal-note" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div style="color: white;" class="modal-header main-label">
                        <h4><i class="fa fa-sticky-note" aria-hidden="true"></i> Nota<i class="fa fa-lg fa-times-circle pull-right modal-img" data-dismiss="modal"></i></h4>

                    </div>

                    <div class="modal-body">
                        <div id="modal-loader" style="display: none; text-align: center;">
                            <!-- ajax loader -->
                            <img src="images/817.gif">
                        </div>

                        <!-- mysql data will be load here -->
                        <div id="dynamic-content"></div>
                    </div>

                    <div class="modal-footer main-label">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>

                </div>
        </div>
    </div> <!-- end 2nd modal -->

</div> <!-- end col -->

</div> <!-- end row -->

</div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>
