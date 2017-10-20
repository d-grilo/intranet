<?php
$page_title = 'Wiki';
require_once('header.php');
require_once ('navbar.php');

$limit = 10;
$pageurl = 'wiki';

if (isset($_GET['page'])) {
    $page  = $_GET['page'];
}
else {
    $page = 1;
}

$start_from = ($page - 1) * $limit;

?>
<div  class="main" >

    <!-- 1st row -->
    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="glyphicon glyphicon-info-sign ml-2" aria-hidden="true"></i> Wiki<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->



    <div class="container">

        <div id="categories-snapshot" class="row mt-2">

            <!-- Search bar -->
            <form class="mt-6" method="GET" id="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div id="search_container">
                    <div class="input-group stylish-input-group">
                        <input name="search_string" type="text" class="form-control"  placeholder="Procurar.." >
                        <span class="input-group-addon">
                            <button  type="submit" value="true">
                                <span class="fa fa-search-plus"></span>
                            </button>
                        </span>
                    </div>
                </div>
            </form> <!-- end search -->

            <?php
            # Conteúdo gerado se a form de pesquisa for submetida
            if(isset($_GET['search_string'])) {

                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                    or die('<h3>Falha ao conectar á base de dados</h3>');

                $search_string = trim($_GET['search_string']);

                $search_query = "SELECT ia.*, ic.category_name AS category_name FROM intranet_wiki_article AS ia
                INNER JOIN intranet_wiki_category AS ic USING(category_id)";

                # Lista de palavras a procurar
                $where_list = array();

                # String introduzida pelo utilizador
                $user_search = $_GET['search_string'];

                # Criar array com todas as palavras separadas com espaços
                $search_words = explode(' ', $user_search);

                # Percorrer o array de palavras e criar uma string com cada uma para juntar à query principal
                foreach($search_words as $word) {
                    $where_list[] = "article_content LIKE '%$word%' OR article_name LIKE '%$word%'";
                }

                # Juntar todas as palavras juntando 'OR' entre elas para fazer a query
                $where_clause = implode(' OR ', $where_list);

                # Caso a query não esteja vazia, pesquisar por todas as palavras introduzidas
                if(!empty($search_string)) {
                    $search_query = "SELECT ia.*, ic.category_name AS category_name FROM intranet_wiki_article AS ia
                INNER JOIN intranet_wiki_category AS ic USING(category_id) WHERE $where_clause LIMIT $start_from, $limit";
                }
                # Caso contrário, pesquisar tudo.
                else {
                    $search_query = "SELECT ia.*, ic.category_name AS category_name FROM intranet_wiki_article AS ia
                INNER JOIN intranet_wiki_category AS ic USING(category_id) WHERE $where_clause LIMIT $start_from, $limit";
                }


                $data = mysqli_query($dbc, $search_query)
                    or die('<h3>Falha ao comunicar a base de dados</h3>');

                $numero_resultados = mysqli_num_rows($data);

               # Verificar se foi encontrado algum resultado, caso nao tenha sido, mostrar mensagem
                if(mysqli_num_rows($data) == 0) {
                    echo '<h2 class="text-center mt-3">Não foram encontrados resultados <i class="fa fa-frown-o" aria-hidden="true"></i></h2>';
                    require_once('footer.php');
                    exit();
                }


                echo '<table class="table table-bordered table-hover mt-5">';
                    echo '<thead>';
                        echo '<th class="nice-blue-th"><i class="fa fa-folder" aria-hidden="true"></i> Categoria</th>';
                        echo '<th class="nice-blue-th"><i class="fa fa-file" aria-hidden="true"></i> Artigo</th>';

                echo '</thead>';
                echo '<tbody>';

                while($row = mysqli_fetch_array($data)) {

                    echo '<tr class="hover-hand" onclick="window.document.location=\'' . 'wiki_article.php?article_id=' . $row['article_id']  .   '\'">' .'<td>'. $row['category_name'] . '</td>';
                    echo '<td>' . $row['article_name'] . '</td>';
                    echo "</tr></td>";
                }

                echo '</tbody>';
                echo '</table>';


                # Query para a paginação
                $query = "SELECT ia.*, ic.category_name AS category_name FROM intranet_wiki_article AS ia
                INNER JOIN intranet_wiki_category AS ic USING(category_id) WHERE $where_clause";

                # Função de paginação
                paginate($dbc, $query, $page, $limit, $pageurl, $search_string);

                # Fechar conexão à bd
                mysqli_close($dbc);

            }
            else {

                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                or die('<h3>Falha ao conectar à base de dados');

                $query = "SELECT * FROM intranet_wiki_category";
                $query2 = "SELECT article_name, article_id, category_id, article_creation_time FROM intranet_wiki_article ORDER BY article_creation_time DESC";

                $data_category = mysqli_query($dbc, $query)
                    or die('<h3>Falha ao comunicar com a base de dados');

                $data_article = mysqli_query($dbc, $query2)
                    or die('<h3>Falha ao comunicar a base de dados');

                # Fechar conexão
                mysqli_close($dbc);


                $categories = array();
                $articles = array();

                while ($row = mysqli_fetch_array($data_category)) {
                    array_push($categories, $row);
                }

                while ($row2 = mysqli_fetch_array($data_article)) {
                    array_push($articles, $row2);
                }

                $hold = $categories[0]['category_name'];

                echo '<div class="col-lg-6 mt-5">
            <a class="category-link" href=wiki_category.php?category_id=' . $categories[0]['category_id'] . '>' . '
            <h3 class="main-label"><i class="fa fa-folder" aria-hidden="true"></i> ' . $hold . '</a><button class="confirm-btn pull-right toggle-table"><i  class="fa fa-chevron-down chevron" aria-hidden="true"></i></button></h2><ul id="first-group" class="list-group">';

                $controlo = 0;
                foreach ($categories as $category) {
                    if ($hold != $category['category_name']) {
                        $hold = $category['category_name'];
                        echo '</ul></div><div class="col-lg-6 mt-5">' . '<a class="category-link" href=wiki_category.php?category_id=' . $category['category_id'] . '>' . '<h3 class="main-label"><i class="fa fa-folder" aria-hidden="true"></i> ' . $category['category_name'] . '</a><button class="confirm-btn pull-right toggle-table"><i  class="fa fa-chevron-down chevron" aria-hidden="true"></i></button></h2><ul class="list-group">';
                        $controlo = 0;

                    }

                    foreach ($articles as $article) {

                        if ($category['category_id'] == $article['category_id']) {
                            if ($controlo >= 4) {
                                $controlo = 0;
                                break;
                            }

                            echo '<li class="list-group-item edit-wiki-list"><a href="wiki_article.php?article_id=' . $article['article_id'] . '"><i class="fa fa-file" aria-hidden="true"></i>    ' . $article['article_name'] . '</a></li>';
                            $controlo = $controlo + 1;
                        }
                    }
                }

            } // else -> a form não foi submetida
            ?>

        </div> <!-- col -->


    </div><!-- 1st row -->

</div> <!-- container -->

</div><!-- main -->

<?php
require_once('footer.php');
?>
