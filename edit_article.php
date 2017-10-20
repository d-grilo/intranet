<?php
$page_title = 'Editar artigo';
require_once('header.php');
require_once ('navbar.php');

$sucesso = false;

if(isset($_POST['submit_edit_article'])) {

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('<h3 style="color red;">Falha ao conectar à base de dados');

    $article_id =  mysqli_real_escape_string($dbc, trim($_POST['article_id']));
    $article_content = mysqli_real_escape_string($dbc, trim($_POST['article_content']));

    $query = "UPDATE intranet_wiki_article SET article_content = '$article_content'  WHERE article_id = '$article_id';";

    mysqli_query($dbc, $query)
        or die('<h3 style="color red;">Falha ao comunicar com a base de dados');

    # Fechar conexão
    mysqli_close($dbc);

    $sucesso = true;

}



?>
<div class="main" >

    <!-- 1st row -->
    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-info-circle ml-2" aria-hidden="true"></i> Wiki<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->

    <div class="container" >

        <div class="row mt-10 mb-3" id="section-edit-article">

            <div class="col-sm-6 col-sm-offset-3">
                <?php
                    if (isset($_GET['article_id'])) {
                        $article_id = $_GET['article_id'];

                        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                            or die('<h3>Falha ao conectar à base de dados</h3>');

                        $query = "SELECT ia.* , ic.category_name AS category_name, iu.username 
                                  FROM intranet_wiki_article AS ia 
                                  INNER JOIN intranet_wiki_category AS ic USING (category_id)
                                  INNER JOIN intranet_user AS iu USING(user_id)
                                  WHERE ia.article_id =  '" . $_GET['article_id'] . "'";

                        $data = mysqli_query($dbc, $query)
                            or die('<h3>Falha ao comunicar com base de dados');

                        $row = mysqli_fetch_array($data);
                        echo '<ul class="list-group"><h3 class="main-label"><i class="fa fa-file" aria-hidden="true"></i> ' . $row['article_name'] . ' </h3>';
                        echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-user" aria-hidden="true"></i> ' . $row['username'] . '</li>';
                        echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-folder" aria-hidden="true"></i> ' . $row['category_name'] . '</li>';
                        echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-clock-o" aria-hidden="true"></i> ' . $row['article_creation_time'] . '</li>';
                        if ($row['article_modification_time'] != NULL)
                            echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-edit" aria-hidden="true"></i> ' . $row['article_modification_time'] . '</li>';
                        else
                            echo '<li class="list-group-item edit-wiki-list"><i class="fa fa-edit" aria-hidden="true"></i> O artigo ainda não foi modificado</li>';
                        echo '</ul>';

                        ?>
                        <!-- Edit article form -->
                        <form method="post" id="edit-article-form" action="edit_article.php?article_id=<?php echo $article_id;?>">
                            <input type="hidden" name="article_id" value="<?php echo $article_id;?>">
                            <div class="form-group">
                                <label for="article-content" class="secondary-label"><i class="fa fa-info-circle" aria-hidden="true"></i> Conteúdo:</label>
                                <textarea id="article-content" name="article_content" class="form-control" rows="5"><?php echo $row['article_content'];?></textarea>
                            </div>
                        </form>

                        <?php
                    }
                        ?>
                <!-- BUTTON GROUP -->
                <div class="text-center">
                    <div class="btn-group">
                        <button type="submit" name="submit_edit_article" form="edit-article-form" id="edit-article-btn" style=" margin-top: 2rem; margin-left: 2rem;" class="btn confirm-btn"><i class="fa fa-edit" aria-hidden="true"></i> Submeter</button>
                    </div>
                </div>

                <?php
                    if($sucesso)
                        echo '<div id="div-erro" class="bg-success text-center mt-2">Artigo editado com sucesso</div>';
                ?>

                <script>
                    $(document).ready(function() {
                        if ($('#div-erro').length > 0){
                            $('html, body').animate({
                                scrollTop: $('#div-erro').offset().top
                            }, 'slow');
                        }
                    });
                </script>



            </div> <!-- end col -->

        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>