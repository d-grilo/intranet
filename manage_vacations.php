<?php
error_reporting(0);
$page_title = 'Gerir férias';
require_once('header.php');
require_once ('navbar.php');

# Variáveis da página
$limit = 10;
$pageurl = 'manage_vacations';

# Data de hoje
$date = time();

# Ano corrente
$year = date('Y', $date);

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
                    <a href="manage_accounts.php" class="btn btn-default active bread-nav inactive-manage-link"><i class="fa fa-sitemap"></i></a>
                    <a href="manage_users.php" class="btn btn-default bread-nav inactive-manage-link">Gerir utilizadores</a>
                    <a href="manage_colors.php" class="btn btn-default bread-nav inactive-manage-link">Gerir cores</a>
                    <a id="active-manage-link" href="manage_vacations.php" class="btn btn-default bread-nav active"><span class="white-text">Gerir férias</span></a>
                </div> <!-- end breadcrumb -->

                <!-- Search bar -->
                <form class="pull-right" method="GET" id="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div id="search_container">
                        <div class="input-group stylish-input-group">
                            <input name="search_string" type="text" class="form-control"  placeholder="Procurar prazo de início..">
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

                $search_query = "SELECT iv.* , iu.name AS name
                    FROM intranet_vacation AS iv
                    INNER JOIN intranet_user AS iu USING (user_id)
                    WHERE iu.user_id = iv.user_id AND YEAR(vacation_start_date) = '$year' ORDER BY user_id";

                # Lista de palavras a procurar
                $where_list = array();

                # String intrudiza pelo utilizador
                $user_search = $_GET['search_string'];


                # Criar array com todas as palavras separadas com espaços
                $search_words = explode(' ', $user_search);

                # Percorrer o array de palavras e criar uma string com cada uma para juntar à query principal
                foreach ($search_words as $word) {
                    $where_list[] = "vacation_start_date LIKE '%$word%'";
                }

                # Juntar todas as palavras juntando 'OR' entre elas para fazer a query
                $where_clause = implode(' OR ', $where_list);

                # Caso a query não esteja vazia, pesquisar por todas as data
                if (!empty($search_string)) {
                    $search_query = "SELECT iv.* , iu.name AS name
                    FROM intranet_vacation AS iv
                    INNER JOIN intranet_user AS iu USING (user_id)
                    WHERE iu.user_id = iv.user_id AND YEAR(vacation_start_date) = '$year' AND $where_clause ORDER BY user_id LIMIT $start_from, $limit";
                } else {
                    $search_query = "SELECT iv.* , iu.name AS name
                    FROM intranet_vacation AS iv
                    INNER JOIN intranet_user AS iu USING (user_id)
                    WHERE iu.user_id = iv.user_id AND YEAR(vacation_start_date) = '$year' AND $where_clause ORDER BY user_id LIMIT $start_from, $limit";
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
                        <th class="nice-blue-th"><i class="fa fa-user" aria-hidden="true"></i> Utilizador</th>
                        <th class="nice-blue-th"><i class="fa fa-calendar" aria-hidden="true"></i> Prazo</th>
                        <th class="nice-blue-th"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</th>
                        <th class="nice-blue-th"><a title="Adicionar férias" href="add_vacation.php" class="btn confirm-btn center-block"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</a></th>
                    </tr>
                    </thead>
                    <tbody>';

                while($row = mysqli_fetch_array($data)) {

                    $beginDate = date("d-m-Y", strtotime($row['vacation_start_date']));
                    $endDate = date("d-m-Y", strtotime($row['vacation_end_date']));

                    echo '<tr>' .'<td>'. $row['name'] . '</td>';
                    echo '<td><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Início: ' . $beginDate . ' &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar-times-o" aria-hidden="true"></i> Fim: ' . $endDate . '</td>';

                    echo'<td><a title="Editar prazo" href="edit_vacation.php?vacation_id=' . $row['vacation_id'] . '"><i class="fa fa-pencil-square-o fa-2x table-icon" aria-hidden="true"></i></a></td>';


                    echo '<td><a title="Eliminar prazo de férias" style="margin-left: 1.5rem;" class="delete-vacation-btn" href="processar_accounts.php?vacation_id=' . $row['vacation_id'] . '"><i class="fa fa-times fa-2x table-icon" aria-hidden="true"></i></a></td></th></tr>';
                    echo "</tr></td>";
                }

                echo '</tbody>';
                echo '</table>';

                # Query paginação
                $query = "SELECT iv.* , iu.name AS name
                    FROM intranet_vacation AS iv
                    INNER JOIN intranet_user AS iu USING (user_id)
                    WHERE iu.user_id = iv.user_id AND YEAR(vacation_start_date) = '$year' AND $where_clause ORDER BY user_id";

                paginate($dbc, $query, $page, $limit, $pageurl, $search_string);


            }
            else {


                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                or die('<h3>Falha ao conectar á base de dados</h3>');


                $query = "SELECT iv.* , iu.name AS name
                    FROM intranet_vacation AS iv
                    INNER JOIN intranet_user AS iu USING (user_id)
                    WHERE iu.user_id = iv.user_id AND YEAR(vacation_start_date) = '$year' ORDER BY user_id";

                $data = mysqli_query($dbc, $query)
                or die('<h3>Falha ao comunicar com a base de dados</h3>');

                $vacations = array();

                while ($row = mysqli_fetch_array($data)) {
                    array_push($vacations, $row);
                }

                # Fechar conexão
                mysqli_close($dbc);

                $hold = $vacations[0]['name'];
                $hold_id = $vacations[0]['user_id'];

                echo '<h2 class="top-label"><i class="fa fa-user" aria-hidden="true"></i> ' . $hold . '<button class="confirm-btn pull-right toggle-table-edit"><i  class="fa fa-chevron-down chevron" aria-hidden="true"></i></button></h2>';
                echo '<table class="table table-bordered table-hover manage-table mt-2">';
                echo '<thead class="thead-inverse">
                <tr>
                    <th class="nice-blue-th"><i class="fa fa-calendar" aria-hidden="true"></i> Prazo</th>
                    <th class="nice-blue-th"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</th>
                    <th class="nice-blue-th"><a title="Adicionar prazo férias" href="add_vacation.php?user_id=' . $hold_id . '" class="btn confirm-btn center-block"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</a></th>
                </tr>
                </thead>
                <tbody>';

                foreach ($vacations as $vacation) {
                    if ($hold != $vacation['name']) {
                        $hold = $vacation['name'];

                        echo '</tbody></table></div>';
                        echo '<div class="row">';

                        echo '<h2 class="top-label mb-2"><i class="fa fa-user" aria-hidden="true"></i> ' . $hold . '<button class="confirm-btn pull-right toggle-table-edit"><i  class="fa fa-chevron-up chevron" aria-hidden="true"></i></button></h2>';
                        echo '<table style="display: none;" class="table table-bordered table-hover manage-table mt-2">';
                        echo '<thead class="thead-inverse">
                <tr>
                    <th class="nice-blue-th"><i class="fa fa-calendar" aria-hidden="true"></i> Prazo</th>
                    <th class="nice-blue-th"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</th>
                    <th class="nice-blue-th"><a href="add_vacation.php?user_id=' . $vacation['user_id'] . '" class="btn confirm-btn center-block"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</a></th>
                </tr>
                </thead>
                <tbody>';
                    }

                    $beginDate = date("d-m-Y", strtotime($vacation['vacation_start_date']));
                    $endDate = date("d-m-Y", strtotime($vacation['vacation_end_date']));


                    echo '<tr>';
                            if($beginDate == $endDate)
                                echo '<td><i class="fa fa-calendar-o" aria-hidden="true"></i> ' . $beginDate . '</td>';
                            else
                                echo '<td><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Início: ' . $beginDate . ' &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar-times-o" aria-hidden="true"></i> Fim: ' . $endDate . '</td>';

                            echo '<td><a href="edit_vacation.php?vacation_id=' . $vacation['vacation_id'] . '"><i class="fa fa-pencil-square-o fa-2x table-icon" aria-hidden="true"></i></a></td>
                                  <td><a class="delete-vacation-btn" href="processar_accounts.php?vacation_id=' . $vacation['vacation_id'] . '"><i class="fa fa-times fa-2x table-icon" aria-hidden="true"></i></a></td>
                          </tr>';
                }


            }

            ?>



        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>