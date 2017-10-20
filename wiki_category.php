<?php
$page_title = 'Ver categoria';
require_once('header.php');
require_once ('navbar.php');

# Variáveis da página
$limit = 10;
$pageurl = 'wiki_category';
$category_id = $_GET['category_id'];

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
        <h2 id="top-bar" class="top-label hidden-xs"><i style="margin-left: 2rem;" class="glyphicon glyphicon-info-sign" aria-hidden="true"></i> Wiki<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->

    <div class="container" >

        <div class="row mt-8 mb-3" id="section-see-category">


            <div class="col-sm-6 col-sm-offset-3">
                <?php
                if (isset($_GET['category_id'])) {

                    $category_id = $_GET['category_id'];

                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                    or die('<h3 style="color red;">Falha ao conectar à base de dados');

                    $query = "SELECT article_name, article_id FROM intranet_wiki_article WHERE category_id = '" . $_GET['category_id'] . "' ORDER BY article_name LIMIT $start_from, $limit";
                    $query2 = "SELECT category_name FROM intranet_wiki_category WHERE category_id = '" . $_GET['category_id'] . "'";


                    $data = mysqli_query($dbc, $query)
                        or die('<h3 style="color red;">Falha ao comunicar com base de dados');

                    $data2 = mysqli_query($dbc, $query2)
                        or die('<h3 style="color red;">Falha ao comunicar com base de dados');


                    $row = mysqli_fetch_array($data);
                    $row2 = mysqli_fetch_assoc($data2);

                    $category_name = $row2['category_name'];

                    if($row2 == NULL) {
                        echo '<h2 style="width: 60%; margin: 0 auto; margin-top: 5rem;">Categoria inexistente <i class="fa fa-thumbs-down" aria-hidden="true"></i> </h2>';
                        exit();
                    }


                    echo '<ul class="list-group"><h3 class="main-label"><i class="fa fa-folder" aria-hidden="true"></i> ' . $row2['category_name'] . ' </h3>';
                    echo '<li style="background-color: inherit;" class="list-group-item">' . '<a href="wiki_article.php?article_id=' . $row['article_id'] . '">' . '<i class="fa fa-file" aria-hidden="true"></i> '  . $row['article_name'] . '</a></li>';


                    while ($row = mysqli_fetch_array($data)) {
                        echo '<li style="background-color: inherit;" class="list-group-item">' . '<a href="wiki_article.php?article_id=' . $row['article_id'] . '">' . ' <i class="fa fa-file" aria-hidden="true"></i> '  . $row['article_name'] . '</a></li>';
                    }
                    echo '</ul>';
                }

                # Query paginação
                $query_num_results = "SELECT article_name, article_id FROM intranet_wiki_article WHERE category_id = '" . $_GET['category_id'] . "'";

                # Função paginação
                paginate($dbc, $query_num_results, $page, $limit, $pageurl, null, $category_id);

                ?>



                <!-- BUTTON GROUP -->
                <div class="text-center">
                    <div class="btn-group">
                        <a href="<?php echo $_SERVER['HTTP_REFERER'];?>" class="btn confirm-btn mt-2 ml-2"><i class="fa fa-arrow-left" aria-hidden="true"></i>  Voltar  atrás</a></a>
                        <a href="add_wiki_article.php?category_name=<?php echo $category_name;?>" class="btn confirm-btn mt-2 ml-2 "><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar artigo</a></a>

                    </div>
                </div>


            </div> <!-- end col -->

        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>
