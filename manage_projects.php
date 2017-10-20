<?php
$page_title = 'Gerir projectos';
require_once('header.php');
require_once ('navbar.php');

# Variáveis da página
$limit = 5;
$pageurl = 'manage_projects';

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

            <div id="projects-snapshot" class="row mt-5">

                <!-- Breadcrumb navigation -->
                <div class="row mt-5">
                    <div class="btn-group btn-breadcrumb mb-3">
                        <a title="Menu" href="manage_project_manager.php" class="btn btn-default active bread-nav inactive-manage-link"><i class="fa fa-sitemap"></i></a>
                        <a id="active-manage-link" href="manage_projects.php" class="btn btn-default bread-nav active"><span class="white-text">Gerir Projectos</span></a>
                        <a href="manage_tasks.php" class="btn btn-default bread-nav inactive-manage-link">Gerir Tarefas</a>
                    </div> <!-- end breadcrumb -->

                    <!-- Search bar -->
                    <form class="pull-right" method="GET" id="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div id="search_container">
                            <div class="input-group stylish-input-group">
                                <input name="search_string" type="text" class="form-control"  placeholder="Procurar projecto...">
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
                    $search_query = "SELECT * FROM intranet_project";

                    # Lista de palavras a procurar
                    $where_list = array();

                    # String inroduzida pelo utilizador
                    $user_search = $_GET['search_string'];

                    # Criar array com todas as palavras separadas com espaços
                    $search_words = explode(' ', $user_search);

                    # Criar array com todas as palavras e criar uma string com cada uma para juntar à query principal
                    foreach($search_words as $word ) {
                        $where_list[] = "project_name LIKE '%$word%'";
                    }

                    # Juntar todas as palavras juntando 'OR' entre elas para fazer a query
                    $where_clause = implode(' OR ', $where_list);

                    # Caso a query não esteja vazia, pesquisar por todas as palvras introduzidas
                    if (!empty($search_string)) {
                        $search_query = "SELECT * FROM intranet_project WHERE $where_clause ORDER BY project_progress ASC LIMIT $start_from, $limit";
                    }
                    else {
                        $search_query = "SELECT * FROM intranet_project WHERE $where_clause ORDER BY project_progress ASC LIMIT $start_from, $limit";
                    }

                    $data = mysqli_query($dbc, $search_query)
                        or die('<h3>Falha ao comunicar a base de dados</h3>');

                    if(mysqli_num_rows($data) == 0) {
                        echo '<h2 class="text-center mt-5">Não foram encontrados resultados <i class="fa fa-frown-o" aria-hidden="true"></i></h2>';
                        require_once('footer.php');
                        exit();
                    }

                   echo '<h2 class="top-label"><i class="fa fa-line-chart" aria-hidden="true"></i> Projectos</h2>
                <table class="table table-bordered table-hover manage-table mt-2">
                    <thead class="thead-inverse">
                    <tr>
                        <th class="nice-blue-th"><i class="fa fa-clipboard" aria-hidden="true"></i> Nome</th>
                        <th class="nice-blue-th"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</th>
                        <th class="nice-blue-th"><i class="fa fa-heartbeat" aria-hidden="true"></i> Estado</th>
                        <th class="nice-blue-th"><a title="Adicionar novo projecto" href="add_project.php" class="btn confirm-btn center-block"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</a></th>
                    </tr>
                    </thead>
                    <tbody>';

                    # Preenche a tabela com os dados na bd
                    while($row = mysqli_fetch_array($data)) {
                        echo '<tr>
                            <td><a class="dark-link" href="project.php?project_id=' . $row['project_id'] . '">' . $row['project_name'] . '</a></td>
                            <td><a title="Editar projecto" href="edit_project.php?project_id=' . $row['project_id'] . '"><i class="fa fa-pencil-square-o fa-2x table-icon" aria-hidden="true"></i></a></td>';
                        if($row['project_progress'] == 0) {
                            echo '<td> Por atribuir</td>';
                        }
                        else if($row['project_progress'] == 1) {
                            echo '<td> Em progresso</td>';
                        }
                        else if($row['project_progress'] == 2) {
                            echo '<td> Completo</td>';
                        }

                        echo '<td><a title="Marcar como completo" class="complete-project-btn" href="processar_gestor.php?project_id=' . $row['project_id'] . '&complete=1"><i class="fa fa-check fa-2x table-icon" aria-hidden="true"></i></a>
                                <a title="Colocar em progresso" class="progress-project-btn" href="processar_gestor.php?project_id=' . $row['project_id'] . '&progress=1"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></a>
                                <a title="Eliminar projecto" style="margin-left: 1.5rem;" class="delete-project-btn" href="processar_gestor.php?project_id=' . $row['project_id'] . '&delete=2"><i class="fa fa-times fa-2x table-icon" aria-hidden="true"></i></a>
                                
                                </td></th></tr>';
                    }
                    echo '</tbody></table>';

                    # Query paginação
                    $query = "SELECT * FROM intranet_project WHERE $where_clause";

                    paginate($dbc, $query, $page, $limit, $pageurl, $search_string);


                }
                else {

                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                        or die('<h3>Falha ao conectar á base de dados</h3>');

                    $query = "SELECT * FROM intranet_project ORDER BY project_progress ASC LIMIT $start_from, $limit";

                    $data = mysqli_query($dbc, $query)
                        or die('<h3>Falha ao comunicar com a base de dados</h3>');

                    ?>

                    <h2 class="top-label"><i class="fa fa-line-chart" aria-hidden="true"></i> Projectos</h2>
                    <table class="table table-bordered table-hover manage-table mt-2">
                        <thead class="thead-inverse">
                        <tr>
                            <th class="nice-blue-th"><i class="fa fa-clipboard" aria-hidden="true"></i> Nome</th>
                            <th class="nice-blue-th"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</th>
                            <th class="nice-blue-th"><i class="fa fa-heartbeat" aria-hidden="true"></i> Estado</th>
                            <th class="nice-blue-th"><a title="Adicionar novo projecto" href="add_project.php" class="btn confirm-btn center-block"><i class="fa fa-plus" aria-hidden="true"></i>Adicionar</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        # Preenche a tabela com os dados na bd
                        while ($row = mysqli_fetch_array($data)) {
                            echo '<tr>
                                <td><a class="dark-link" href="project.php?project_id=' . $row['project_id'] . '">' . $row['project_name'] . '</a></td>
                            <td><a title="Editar projecto" href="edit_project.php?project_id=' . $row['project_id'] . '"><i class="fa fa-pencil-square-o fa-2x table-icon" aria-hidden="true"></i></a></td>';
                            if ($row['project_progress'] == 0) {
                                echo '<td> Por atribuir</td>';
                            } else if ($row['project_progress'] == 1) {
                                echo '<td> Em progresso</td>';
                            } else if ($row['project_progress'] == 2) {
                                echo '<td> Completo</td>';
                            }

                            echo '<td><a title="Marcar como completo" class="complete-project-btn mr-2" href="processar_gestor.php?project_id=' . $row['project_id'] . '&complete=1"><i class="fa fa-check fa-2x table-icon" aria-hidden="true"></i></a>
                                <a title="Colocar em progresso" class="progress-project-btn mr-2" href="processar_gestor.php?project_id=' . $row['project_id'] . '&progress=1"><i class="fa fa-bolt fa-2x table-icon" aria-hidden="true"></i></a>
                                <a title="Eliminar projecto" class="delete-project-btn" href="processar_gestor.php?project_id=' . $row['project_id'] . '&delete=2"><i class="fa fa-times fa-2x table-icon" aria-hidden="true"></i></a>
                                </td></th></tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php

                    # Query paginação
                    $query = "SELECT project_id FROM intranet_project";

                    # Função paginação
                    paginate($dbc, $query, $page, $limit, $pageurl);

                }

                ?>

        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>