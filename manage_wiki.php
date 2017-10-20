<?php
$page_title = 'Administração Wiki';
require_once('header.php');
require_once ('redirect.php');
require_once ('navbar.php');

?>
<div class="main">

    <!-- 1st row -->
    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-wrench ml-2" aria-hidden="true"></i> Administração<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->

    <div class="container" >

        <div class="row row-eq-height mt-10">

            <div class="col-sm-6 col-sm-offset-3">

                <h3 class="secondary-label"><i class="fa fa-cogs" aria-hidden="true"></i> Administração Wiki</h3>

                <ul  class="list-group">
                    <a class="manage-links" href="manage_wiki_categories.php"><li class="list-group-item edit-wiki-list"><i class="fa fa-folder" aria-hidden="true"></i> Categorias</li></a>
                    <a class="manage-links" href="manage_wiki_articles.php"><li class="list-group-item edit-wiki-list"><i class="fa fa-file" aria-hidden="true"></i> Artigos</li></a>
                </ul>

                <!-- BUTTON GROUP -->
                <div class="text-center">
                    <div class="btn-group">
                        <a href="add_wiki_category.php" class="btn confirm-btn mt-2 ml-2"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar categoria</a></a>
                        <a href="add_wiki_article.php" class="btn confirm-btn mt-2 ml-2"><i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar artigo </a>
                    </div>
                </div>

            </div> <!-- col -->

        </div> <!--1st row -->


    </div> <!-- container -->

</div> <!-- main -->

<?php
require_once('footer.php');
?>

