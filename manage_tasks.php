<?php
$page_title = 'Gerir tarefas';
require_once('header.php');
require_once ('navbar.php');

# Variáveis da página
$limit = 3;
$pageurl = 'manage_tasks';

if (isset($_GET['page'])) {
    $page  = $_GET['page'];
}
else {
    $page = 1;
}

$start_from = ($page - 1) * $limit;

?>
    <div class="main" >

        <!-- 1st row -->
        <div class="row">
            <!-- melhorar, usar div -->
            <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-wrench ml-2" aria-hidden="true"></i> Administração<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                    <?php echo $_SESSION['username'];?></span></h2>
        </div> <!-- end 1st row -->

        <div class="container" >

            <div class="row mt-5">

                <!-- Breadcrumb navigation -->
                <div class="row mt-5">
                    <div class="btn-group btn-breadcrumb mb-3">
                        <a href="manage_project_manager.php" class="btn btn-default active bread-nav inactive-manage-link"><i class="fa fa-sitemap"></i></a>
                        <a href="manage_projects.php" class="btn btn-default bread-nav inactive-manage-link">Gerir Projectos</a>
                        <a id="active-manage-link" href="manage_tasks.php" class="btn btn-default bread-nav active"><span style="color: white;">Gerir Tarefas</span></a>
                    </div> <!-- end breadcrumb -->

                    <!-- Search bar -->
                    <form class="pull-right" method="GET" id="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div id="search_container">
                            <div class="input-group stylish-input-group">
                                <input name="search_string" type="text" class="form-control"  placeholder="Procurar tarefa..." >
                                <span class="input-group-addon">
                                    <button  type="submit" value="true">
                                        <span class="fa fa-search-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                </form> <!-- end search -->

            </div> <!-- end row -->

            <?php

            if(isset($_GET['search_string'])) {

                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                    or die('<h3>Falha ao conectar á base de dados</h3>');

                $search_string = trim($_GET['search_string']);

                $search_query = "SELECT it.*, ip.project_name AS project_name FROM intranet_task AS it 
                INNER JOIN intranet_project AS ip USING(project_id)";

                # Lista de palavras a procurar
                $where_list = array();

                # String introduzida pelo utilizador
                $user_search = $_GET['search_string'];

                # Criar array com todas as palavras separadas com espaços
                $search_words = explode(' ', $user_search);

                # Percorrer o array de palavras e criar uma string com cada uma para junta à query principal
                foreach($search_words as $word) {
                    $where_list[] = "task_name LIKE '%$word%'";
                }

                # Juntar todas as palavras juntando 'OR' entre elas para fazer a query
                $where_clause = implode(' OR ', $where_list);

                # Caso a query não esteja vazia, pesquisar por todas as palavras introduzidas
                if(!empty($search_string)) {

                    $search_query = "SELECT it.*, ip.project_name AS project_name FROM intranet_task AS it 
                    INNER JOIN intranet_project AS ip USING(project_id) WHERE $where_clause ORDER BY project_name LIMIT $start_from, $limit";
                }
                # Caso contrário, pesquisar tudo
                else {
                    $search_query = "SELECT it.*, ip.project_name AS project_name FROM intranet_task AS it 
                    INNER JOIN intranet_project AS ip USING(project_id) ORDER BY project_name LIMIT $start_from, $limit";
                }

                $data = mysqli_query($dbc, $search_query)
                    or die('<h3>Falha ao comunicar a base de dados</h3>');

                if(mysqli_num_rows($data) == 0) {
                    echo '<h2 class="text-center mt-5">Não foram encontrados resultados <i class="fa fa-frown-o" aria-hidden="true"></i></h2>';
                    require_once('footer.php');
                    exit();
                }

                echo '<h2 class="top-label"><i class="fa fa-search-plus" aria-hidden="true"></i> Resultados<button class="confirm-btn pull-right toggle-table-edit"><i  class="fa fa-chevron-down chevron" aria-hidden="true"></i></button></h2>';
                echo '<table class="table table-bordered table-hover manage-table mt-2">';
                echo '<thead class="thead-inverse">
                    <tr>
                        <th class="nice-blue-th"><i class="fa fa-line-chart" aria-hidden="true"></i> Projecto</th>
                        <th class="nice-blue-th"><i class="fa fa-clipboard" aria-hidden="true"></i> Nome</th>
                        <th class="nice-blue-th"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</th>
                        <th class="nice-blue-th"><i class="fa fa-heartbeat" aria-hidden="true"></i> Estado</th>
                        <th class="nice-blue-th"><a title="Adicionar tarefa a este projecto" href="add_task.php" class="btn confirm-btn center-block"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</a></th>
                    </tr>
                    </thead>
                    <tbody>';

                while($row = mysqli_fetch_array($data)) {

                    echo '<tr>' .'<td><a class="dark-link" href="project.php?project_id=' . $row['project_id'] . '">'. $row['project_name'] . '</a></td>';
                    echo '<td><a class="dark-link" href="task.php?task_id=' . $row['task_id'] . '">' . $row['task_name'] . '</a></td>';
                    echo'<td><a title="Editar tarefa" href="edit_task.php?task_id=' . $row['task_id'] . '"><i class="fa fa-pencil-square-o fa-2x table-icon" aria-hidden="true"></i></a></td>';
                    if ($row['task_progress'] == 0) {
                        echo '<td> Por atribuir</td>';
                    } else if ($row['task_progress'] == 1) {
                        echo '<td> Em progresso</td>';
                    } else if ($row['task_progress'] == 2) {
                        echo '<td> Completa</td>';
                    }


                    echo '<td><a title="Marcar tarefa como completa" class="complete-task-btn" href="processar_gestor.php?task_id=' . $row['task_id'] . '&complete=1"><i class="fa fa-check fa-2x table-icon" aria-hidden="true"></i></a>';

                    echo '<a title="Eliminar tarefa" style="margin-left: 1.5rem;" class="delete-task-btn" href="processar_gestor.php?task_id=' . $row['task_id'] . '&delete=2"><i class="fa fa-times fa-2x table-icon" aria-hidden="true"></i></a></td></th></tr>';
                    echo "</tr></td>";
                }

                echo '</tbody>';
                echo '</table>';

                # Query para paginação
                $query = "SELECT it.*, ip.project_name AS project_name FROM intranet_task AS it 
                    INNER JOIN intranet_project AS ip USING(project_id) WHERE $where_clause";

                # Função de paginação
                paginate($dbc, $query, $page, $limit, $pageurl, $search_string);

            }
            else {

                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                    or die('<h3>Falha ao conectar á base de dados</h3>');

                $query = "SELECT it.*, ip.project_name AS project_name
                  FROM intranet_task AS it
                  INNER JOIN intranet_project AS ip USING(project_id)
                  WHERE ip.project_id = it.project_id ORDER BY project_name";

                $data = mysqli_query($dbc, $query)
                or die('<h3>Falha ao comunicar com a base de dados</h3>');

                $tasks = array();

                while($row = mysqli_fetch_array($data)) {
                    array_push($tasks, $row);
                }

                # Fechar conexão
                mysqli_close($dbc);

                $hold = $tasks[0]['project_name'];

                echo '<h2 class="top-label"><a href="project.php?project_id=' . $tasks[0]['project_id'] . '"><i class="fa fa-line-chart" aria-hidden="true"></i> ' . $hold . '</a><button class="confirm-btn pull-right toggle-table-edit"><i  class="fa fa-chevron-down chevron" aria-hidden="true"></i></button></h2>';
                echo '<table class="table table-bordered table-hover manage-table mt-2">';
                echo '<thead class="thead-inverse">
                    <tr>
                        <th class="nice-blue-th"><i class="fa fa-clipboard" aria-hidden="true"></i> Nome</th>
                        <th class="nice-blue-th"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</th>
                        <th class="nice-blue-th"><i class="fa fa-heartbeat" aria-hidden="true"></i> Estado</th>
                        <th class="nice-blue-th"><a title="Adicionar tarefa a este projecto" href="add_task.php?project_name=' . $hold . '" class="btn confirm-btn center-block"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</a></th>
                    </tr>
                    </thead>
                    <tbody>';

                foreach ($tasks as $task) {
                    if ($hold != $task['project_name']) {
                        $hold = $task['project_name'];

                        echo '</tbody></table></div>';
                        echo '<div class="row">';
                        echo '<h2 class="top-label"><a href="project.php?project_id=' . $task['project_id'] . '"><i class="fa fa-line-chart" aria-hidden="true"></i> ' . $hold . '</a><button class="confirm-btn pull-right toggle-table-edit"><i  class="fa fa-chevron-up chevron" aria-hidden="true"></i></button></h2>';
                        echo '<table style="display: none;" class="table table-bordered table-hover manage-table mt-2">';
                        echo '<thead class="thead-inverse">
                    <tr>
                        <th class="nice-blue-th"><i class="fa fa-clipboard" aria-hidden="true"></i> Nome</th>
                        <th class="nice-blue-th"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</th>
                        <th class="nice-blue-th"><i class="fa fa-heartbeat" aria-hidden="true"></i> Estado</th>
                        <th class="nice-blue-th"><a title="Adicionar tarefa a este projecto" href="add_task.php?project_name=' . $task['project_name'] . '" class="btn confirm-btn center-block"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</a></th>
                    </tr>
                    </thead>
                    <tbody>';
                    }

                    echo '<tr>
                            <td><a class="dark-link" href="task.php?task_id=' . $task['task_id'] . '">' . $task['task_name'] . '</a></td>
                            <td><a title="Editar tarefa" href="edit_task.php?task_id=' . $task['task_id'] . '"><i class="fa fa-pencil-square-o fa-2x table-icon" aria-hidden="true"></i></a></td>';
                    if ($task['task_progress'] == 0) {
                        echo '<td> Por atribuir</td>';
                    } else if ($task['task_progress'] == 1) {
                        echo '<td> Em progresso</td>';
                    } else if ($task['task_progress'] == 2) {
                        echo '<td> Completada</td>';
                    }

                    echo '<td><a title="Marcar tarefa como completa" class="complete-task-btn" href="processar_gestor.php?task_id=' . $task['task_id'] . '&complete=1"><i class="fa fa-check fa-2x table-icon" aria-hidden="true"></i></a>';

                    echo '<a title="Eliminar tarefa" style="margin-left: 1.5rem;" class="delete-task-btn" href="processar_gestor.php?task_id=' . $task['task_id'] . '&delete=2"><i class="fa fa-times fa-2x table-icon" aria-hidden="true"></i></a></td></th></tr>';
                }

            }

            ?>
            </div> <!-- end row -->
        </div> <!-- end container -->
    </div> <!-- end main -->

<?php
require_once('footer.php');
?>