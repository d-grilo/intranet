<?php
$page_title = 'Gerir utilizadores';
require_once('header.php');
require_once ('navbar.php');

# Variáveis da página
$limit = 5;
$pageurl = 'manage_users';

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

        <div id="users-snapshot" class="row mt-4">

            <!-- Breadcrumb navigation -->
            <div class="row mt-5">
                <div class="btn-group btn-breadcrumb mb-3">
                    <a title="Menu" href="manage_accounts.php" class="btn btn-default active bread-nav inactive-manage-link"><i class="fa fa-sitemap"></i></a>
                    <a id="active-manage-link" href="manage_users.php" class="btn btn-default bread-nav active"><span class="white-text">Gerir utilizadores</span></a>
                    <a href="manage_colors.php" class="btn btn-default bread-nav inactive-manage-link">Gerir Cores</a>
                    <a href="manage_vacations.php" class="btn btn-default bread-nav inactive-manage-link">Gerir Férias</a>

                </div>
            </div> <!-- end breadcrumb -->

            <?php
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                or die('<h3>Falha ao conectar á base de dados</h3>');

            $query = "SELECT * FROM intranet_user LIMIT $start_from, $limit";

            $data = mysqli_query($dbc, $query)
                or die('<h3>Falha ao comunicar com a base de dados</h3>');

            ?>

            <h2 class="top-label"><i class="fa fa-users" aria-hidden="true"></i> Utilizadores</h2>

            <table class="table table-bordered table-hover manage-table mt-2">
                <thead class="thead-inverse">
                <tr>
                    <th class="nice-blue-th">#</th>
                    <th class="nice-blue-th"><i class="fa fa-clipboard" aria-hidden="true"></i> Nome</th>
                    <th class="nice-blue-th"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</th>
                    <th class="nice-blue-th"><i class="fa fa-gavel" aria-hidden="true"></i> Nivel</th>
                    <th class="nice-blue-th"><a title="Adicionar novo utilizador" href="signup.php" class="btn confirm-btn center-block"><i class="fa fa-plus" aria-hidden="true"></i> Adicionar</a></th>
                </tr>
                </thead>
                <tbody>

                <?php
                # Preenche a tabela com os dados na bd
                while($row = mysqli_fetch_array($data)) {
                    echo '<tr><th scope="row">' . $row['user_id'] .
                        '<td>' . $row['name'] . '</td>
                        <td><a title="Editar conta" href="manage_account.php?user_id=' . $row['user_id'] . '"><i class="fa fa-pencil-square-o fa-2x table-icon" aria-hidden="true"></i></a></td>';
                    if($row['level'] == 0) {
                        echo '<td> Administrador</td>';
                    }
                    else if($row['level'] == 1) {
                        echo '<td> Usuário</td>';
                    }
                    else if($row['level'] == 2) {
                        echo '<td> Guest</td>';
                    }

                    echo '<td><a title="Eliminar utilizador" style="margin-left: 1.5rem;" class="delete-user-btn" href="processar_accounts.php?user_id=' . $row['user_id'] . '"><i class="fa fa-times fa-2x table-icon" aria-hidden="true"></i></a>
                        </td></th></tr>';
                }
                ?>
                </tbody>
            </table>

            <?php

            # Query paginação
            $query = "SELECT user_id FROM intranet_user";

            # Função paginação
            paginate($dbc, $query, $page, $limit, $pageurl);

            ?>


        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>


