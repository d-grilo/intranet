<?php
$page_title = 'Ver artigo';
require_once('header.php');
require_once ('navbar.php');
?>
    <div class="main" >

        <!-- 1st row -->
        <div class="row">
            <!-- melhorar, usar div -->
            <h2 id="top-bar" class="top-label hidden-xs"><i style="margin-left: 2rem;" class="glyphicon glyphicon-info-sign" aria-hidden="true"></i> Wiki<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                    <?php echo $_SESSION['username'];?></span></h2>
        </div> <!-- end 1st row -->


        <div class="container" >

            <div class="row mt-8 mb-3" id="section-see-article">

                <div class="col-sm-8 col-sm-offset-2">

                    <?php
                        if (isset($_GET['article_id'])) {

                            $article_id = $_GET['article_id'];

                            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                or die('<h3 style="color red;">Falha ao conectar à base de dados');

                            $query = "SELECT ia.* , ic.category_name AS category_name, iu.username 
                                      FROM intranet_wiki_article AS ia 
                                      INNER JOIN intranet_wiki_category AS ic USING (category_id)
                                      INNER JOIN intranet_user AS iu USING(user_id)
                                      WHERE ia.article_id =  '" . $_GET['article_id'] . "'";

                            $data = mysqli_query($dbc, $query)
                                or die('<h3 style="color red;">Falha ao comunicar com base de dados');

                            $row = mysqli_fetch_array($data);

                            echo '<ul class="list-group"><h3 class="main-label"><i class="fa fa-file" aria-hidden="true"></i> ' . $row['article_name'] . ' </h3>';
                                echo '<li style="background-color: inherit;" class="list-group-item"><i class="fa fa-user" aria-hidden="true"></i> '  . $row['username'] . '</li>';
                                echo '<li style="background-color: inherit;" class="list-group-item"><i class="fa fa-folder" aria-hidden="true"></i> '  . $row['category_name'] . '</li>';
                                echo '<li style="background-color: inherit;" class="list-group-item"><i class="fa fa-clock-o" aria-hidden="true"></i> '  . $row['article_creation_time'] . '</li>';
                                if($row['article_modification_time'] != NULL)
                                    echo '<li style="background-color: inherit;" class="list-group-item"><i class="fa fa-edit" aria-hidden="true"></i> ' . $row['article_modification_time'] . '</li>';
                                else
                                    echo '<li style="background-color: inherit;" class="list-group-item"><i class="fa fa-edit" aria-hidden="true"></i> O artigo ainda não foi modificado</li>';
                           // echo '<li style="background-color: inherit;" class="list-group-item"><i class="fa fa-info" aria-hidden="true"></i><textarea>'  . $row['article_content'] . '</textarea></li>';
                            echo '</ul>';
                            echo '<textarea class="form-control" rows="5" cols="5">' . $row['article_content'] . '</textarea>';



                    }
                    ?>
                    <!-- BUTTON GROUP -->
                    <div class="text-center">
                        <div class="btn-group">
                            <a href="<?php echo $_SERVER['HTTP_REFERER'];?>" class="btn confirm-btn mt-2 ml-2"><i class="fa fa-arrow-left" aria-hidden="true"></i>  Voltar  atrás</a></a>
                            <a href="edit_article.php?article_id=<?php echo $article_id;?>" id="edit-article-btn" style="margin-top: 2rem; margin-left: 2rem;" class="btn confirm-btn"><i class="fa fa-edit" aria-hidden="true"></i> Editar artigo</a>
                        </div>
                    </div>

                </div> <!-- end col -->

            </div> <!-- end 1st row -->

        </div> <!-- end container -->


    </div> <!-- end main -->


<?php
require_once('footer.php');
?>
