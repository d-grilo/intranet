<?php
$page_title = 'Gerir cores';
require_once('header.php');
require_once ('navbar.php');

# Variáveis da página
$limit = 5;
$pageurl = 'manage_colors';

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

        <div id="colors-snapshot" class="row mt-4">

            <!-- Breadcrumb navigation -->
            <div class="row mt-5">
                <div class="btn-group btn-breadcrumb mb-3">
                    <a href="manage_accounts.php" class="btn btn-default active bread-nav inactive-manage-link"><i class="fa fa-sitemap"></i></a></button>
                    <a href="manage_users.php" class="btn btn-default bread-nav inactive-manage-link">Gerir utilizadores</a>
                    <a id="active-manage-link" href="manage_colors.php" class="btn btn-default bread-nav active"><span class="white-text">Gerir cores</span></a>
                    <a href="manage_vacations.php" class="btn btn-default bread-nav inactive-manage-link">Gerir Férias</a>

                </div> <!-- end breadcrumb -->

                <!-- Search bar -->
                <form class="pull-right" method="GET" id="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div id="search_container">
                        <div class="input-group stylish-input-group">
                            <input name="search_string" type="text" class="form-control"  placeholder="Procurar cor...">
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
                $search_query = "SELECT * FROM intranet_color";

                # Lista de palavras a procurar
                $where_list = array();

                # String inroduzida pelo utilizador
                $user_search = $_GET['search_string'];

                # Criar array com todas as palavras separadas com espaços
                $search_words = explode(' ', $user_search);

                # Criar array com todas as palavras e criar uma string com cada uma para juntar à query principal
                foreach ($search_words as $word) {
                    $where_list[] = "color_name LIKE '%$word%'";
                }

                # Juntar todas as palavras juntando 'OR' entre elas para fazer a query
                $where_clause = implode(' OR ', $where_list);

                # Caso a query não esteja vazia, pesquisar por todas as palvras introduzidas
                if (!empty($search_string)) {
                    $search_query = "SELECT * FROM intranet_color WHERE $where_clause ORDER BY color_name LIMIT $start_from, $limit";
                } else {
                    $search_query = "SELECT * FROM intranet_color WHERE $where_clause ORDER BY color_name LIMIT $start_from, $limit";
                }

                $data = mysqli_query($dbc, $search_query)
                    or die('<h3>Falha ao comunicar a base de dados</h3>');

                if (mysqli_num_rows($data) == 0) {
                    echo '<h2 class="text-center mt-5">Não foram encontrados resultados <i class="fa fa-frown-o" aria-hidden="true"></i></h2>';
                    require_once('footer.php');
                    exit();
                }

                echo '<h2 class="top-label"><i class="fa fa-paint-brush" aria-hidden="true"></i> Cores</h2>
            <table class="table table-bordered table-hover manage-table mt-2">
                <thead class="thead-inverse">
                <tr>
                    <th class="nice-blue-th">#</th>
                    <th class="nice-blue-th"><i class="fa fa-clipboard" aria-hidden="true"></i> Nome</th>
                    <th class="nice-blue-th"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</th>
                    <th class="nice-blue-th" style="width: 20%;"><i class="fa fa-paint-brush" aria-hidden="true"></i> Resultado</th>
                    <th class="nice-blue-th"><a title="Adicionar nova cor" href="add_color.php" class="btn confirm-btn center-block"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</a></th>
                </tr>
                </thead>
                <tbody>';

                # Preenche a tabela com os dados na bd
                while($row = mysqli_fetch_array($data)) {
                    echo '<tr><th scope="row">' . $row['color_id'] .
                        '<td>' . $row['color_name'] . '</td>';
                    echo '<td><a title="Editar cor" href="edit_color.php?color_id=' . $row['color_id'] . '"><i class="fa fa-pencil-square-o fa-2x table-icon" aria-hidden="true"></i></a></td>';
                    echo '<td ><div style="background-color: ' . $row['color_hex'] . ';width: 10%; margin: 0 auto;">&nbsp;</div></td>';
                    echo '<td><a class="delete-color-btn" href="processar_accounts.php?color_id=' . $row['color_id'] . '"><i class="fa fa-times fa-2x table-icon" aria-hidden="true"></i></a></td></th></tr>';

                }
                echo '</tbody></table>';

                # Query paginação
                $query = "SELECT color_id FROM intranet_color WHERE $where_clause";

                # Função paginação
                paginate($dbc, $query, $page, $limit, $pageurl, $search_string);

            }
            else {


                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                or die('<h3>Falha ao conectar á base de dados</h3>');

                $query = "SELECT * FROM intranet_color LIMIT $start_from, $limit";

                $data = mysqli_query($dbc, $query)
                or die('<h3>Falha ao comunicar com a base de dados</h3>');

                ?>

                <h2 class="top-label"><i class="fa fa-paint-brush" aria-hidden="true"></i> Cores</h2>
                <table class="table table-bordered table-hover manage-table mt-2">
                    <thead class="thead-inverse">
                    <tr>
                        <th class="nice-blue-th">#</th>
                        <th class="nice-blue-th"><i class="fa fa-clipboard" aria-hidden="true"></i> Nome</th>
                        <th class="nice-blue-th"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</th>
                        <th class="nice-blue-th" style="width: 20%;"><i class="fa fa-paint-brush" aria-hidden="true"></i> Resultado</th>
                        <th class="nice-blue-th"><a title="Adicionar nova cor" href="add_color.php" class="btn confirm-btn center-block"><i class="fa fa-plus" aria-hidden="true"></i>Adicionar</a></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    # Preenche a tabela com os dados na bd
                    while ($row = mysqli_fetch_array($data)) {
                        echo '<tr><th scope="row">' . $row['color_id'] .
                            '<td>' . $row['color_name'] . '</td>';
                        echo '<td><a title="Editar cor" href="edit_color.php?color_id=' . $row['color_id'] . '"><i class="fa fa-pencil-square-o fa-2x table-icon" aria-hidden="true"></i></a></td>';
                        echo '<td ><div style="background-color: ' . $row['color_hex'] . ';width: 10%; margin: 0 auto;">&nbsp;</div></td>';
                        echo '<td><a class="delete-color-btn" href="processar_accounts.php?color_id=' . $row['color_id'] . '"><i class="fa fa-times fa-2x table-icon" aria-hidden="true"></i></a></td></th></tr>';

                    }

                    echo ' </tbody></table>';

                    # Query paginação
                    $query = "SELECT color_id FROM intranet_color";

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
