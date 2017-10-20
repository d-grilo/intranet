<?php
$page_title = 'Utilizadores';
require_once('header.php');
require_once ('navbar.php');

# Variáveis da página
$limit = 5;
$pageurl = 'view_users';

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
        <h2 id="top-bar" class="top-label hidden-xs"><i style="margin-left: 2rem;" class="fa fa-users" aria-hidden="true"></i> Utilizadores<span style=" font-size: medium; margin-top: 1rem; margin-right: 1rem;" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->

    <div class="container" >

        <div class="row mt-6 mb-3" id="section-add-task">

            <div class="col-sm-6 col-sm-offset-3">

                <h2 class="mb-2"><i class="fa fa-users" aria-hidden="true"></i> Perfil

                    <!-- Search bar -->
                    <form method="GET" id="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="mt-2 mb-2" id="search_container">
                            <div class="input-group stylish-input-group">
                                <input name="search_string" type="text" class="form-control"  placeholder="Procurar utilizador...">
                                <span class="input-group-addon">
                                <button  type="submit" value="true">
                                    <span class="fa fa-search-plus"></span>
                                </button>
                            </span>
                            </div>
                        </div>
                    </form> <!-- end search -->
                </h2>

                <?php

                    if(isset($_GET['search_string'])) {

                        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                        or die('<h3>Falha ao conectar á base de dados</h3>');

                        $search_string = trim($_GET['search_string']);

                        $search_query = "SELECT * FROM intranet_user";

                        # Lista de palavras a procurar
                        $where_list = array();

                        # String introduzida pelo utilizador
                        $user_search = $_GET['search_string'];

                        # Criar array com todas as palavras separadas com espaços
                        $search_words = explode(' ', $user_search);

                        # Percorrer o array de palavras e criar uma string com cada uma para junta à query principal
                        foreach($search_words as $word) {
                            $where_list[] = "name LIKE '%$word%'";
                        }

                        # Juntar todas as palavras juntando 'OR' entre elas para fazer a query
                        $where_clause = implode(' OR ', $where_list);

                        # Caso a query não esteja vazia, pesquisar por todas as palavras introduzidas
                        if(!empty($search_string)) {

                            $search_query = "SELECT * FROM intranet_user WHERE $where_clause ORDER BY name LIMIT $start_from, $limit";
                        }
                        else {
                            $search_query = "SELECT * FROM intranet_user WHERE $where_clause ORDER BY name LIMIT $start_from, $limit";
                        }

                        $data = mysqli_query($dbc, $search_query)
                            or die('<h3>Falha ao comunicar a base de dados</h3>');

                        if(mysqli_num_rows($data) == 0) {
                            echo '<h2 class="text-center mt-5">Não foram encontrados resultados <i class="fa fa-frown-o" aria-hidden="true"></i></h2>';
                            require_once('footer.php');
                            exit();
                        }


                        echo '<table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 50%;" class="main-label">Nome</th>
                                    <th class="text-center main-label">Perfil</th>
                                </tr>
                            </thead>
                        <tbody>';

                        # Preenche a tabela de acordo com o que foi pesquisado
                        while ($row = mysqli_fetch_array($data)) {
                            ?>
                            <tr style="background-color: inherit;">
                                <td><?php echo $row['name'] ?></td>
                                <td class="text-center">
                                    <button style="width: 60%; background-color: yellowgreen;" data-toggle="modal"
                                            data-target="#view-modal-users" data-id="<?php echo $row['user_id']; ?>"
                                            id="openProfile" class="btn confirm-btn"><i
                                                class="glyphicon glyphicon-eye-open"></i></button>
                                </td>
                            </tr>
                            <?php
                        }

                        echo '</tbody></table>';

                        # Query para a paginação
                        $query = "SELECT * FROM intranet_user WHERE $where_clause";

                        # Função de paginação
                        paginate($dbc, $query, $page, $limit, $pageurl, $search_string);

                    }
                    # Caso não tenha sido nada pesquisado
                    else {

                        echo '<table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 50%;" class="main-label">Nome</th>
                                    <th class="text-center main-label">Perfil</th>
                                </tr>
                            </thead>
                        <tbody>';

                        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                            or die('<h3 style="color red;">Falha ao conectar à base de dados');

                        $query = "SELECT * FROM intranet_user ORDER BY user_id LIMIT $start_from, $limit";
                        $data = mysqli_query($dbc, $query);

                        while ($row = mysqli_fetch_array($data)) {
                            ?>
                            <tr style="background-color: inherit;">
                                <td><?php echo $row['name'] ?></td>
                                <td class="text-center">
                                    <button style="width: 60%; background-color: yellowgreen;" data-toggle="modal"
                                            data-target="#view-modal-users" data-id="<?php echo $row['user_id']; ?>"
                                            id="openProfile" class="btn confirm-btn"><i
                                                class="glyphicon glyphicon-eye-open"></i></button>
                                </td>
                            </tr>
                            <?php
                        }

                        ?>

                        </tbody>
                    </table>

                    <?php
                        # Query paginação
                        $query = "SELECT * FROM intranet_user";

                        # Função paginação
                        paginate($dbc, $query, $page, $limit, $pageurl);

                    }

                    ?>

                <!-- MODAL -->
                <div style="color: #1a2129;" id="view-modal-users" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header main-label white-text">
                                <h4><i class="fa fa-user" aria-hidden="true"></i> Perfil<i class="fa fa-lg fa-times-circle pull-right modal-img" data-dismiss="modal"></i></h4>
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

                        </div> <!-- end modal content -->
                    </div> <!-- end modal dialog -->
                </div> <!-- end modal -->

            </div> <!-- end col -->

        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>


