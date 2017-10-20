<?php
$page_title = 'Adicionar artigo';
require_once('header.php');
require_once ('navbar.php');

# Variável de erros
$error = 5;

# Metodo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    # A form foi submetida
    if (isset($_POST['submit_article'])) {

        # Conexão à BD
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or header('Location: ' . $error_url . '?erro=1');

        $article_name = mysqli_real_escape_string($dbc, trim($_POST['category_name']));
        $article_category = mysqli_real_escape_string($dbc, trim($_POST['selected_category']));
        $article_content = mysqli_real_escape_string($dbc, trim($_POST['article_content']));
        $current_user = $_SESSION['user_id'];

        if(!empty($article_name) && !empty($article_content) && isset($article_category)) {

            $query = "INSERT INTO intranet_wiki_article(article_name, article_content, category_id, user_id)" .
                "VALUES('$article_name', '$article_content', '$article_category', '$current_user')";

            mysqli_query($dbc, $query)
                or header('Location: ' . $error_url . '?erro=2');

            $error = 0;

            # Fechar conexão
            mysqli_close($dbc);

        } // Há campos vazios
        else {
            $error = 1;
        }




    } // isset submit article

} // request method post

?>

<div class="main">

    <!-- 1st row -->
    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-info-circle ml-2" aria-hidden="true"></i> Wiki<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->

    <div class="container" >


        <div class="row mt-6 mb-3" id="section-add-article">

            <div class="col-sm-6 col-sm-offset-3">

                <h2 class="top-label mb-2"><i class="fa fa-file pull-left" aria-hidden="true"></i>Novo artigo</h2>

                <form method="post" id="add-category-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="category-name" class="control-label secondary-label"><i class="fa fa-clipboard"></i> Nome:</label>
                        <input id="category-name" name="category_name" type="text" class="form-control" placeholder="Nome do artigo" value="<?php if (!empty($article_name)) echo $article_name; ?>">
                    </div>

                    <hr>

                    <div class="form-group">
                        <label class="control-label secondary-label" for="selected-category"><i class="fa fa-folder"></i> Categoria:</label>
                        <select id="selected-category" name="selected_category" class="form-control">
                            <?php
                            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                or die('<h3>Falha ao conectar à base de dados');
                            $query = "SELECT * FROM intranet_wiki_category";
                            $data = mysqli_query($dbc, $query);

                            # Caso seja passado por GET o nome da categoria, mostra só a categoria pretendida
                            if(isset($_GET['category_name'])) {
                                while ($row = mysqli_fetch_array($data)) {
                                    if($row['category_name'] == $_GET['category_name'])
                                        echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                                }
                            }
                            # Senão, mostra todas as categorias
                            else {
                                while ($row = mysqli_fetch_array($data)) {
                                        echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="control-label secondary-label" for="article-content"><i class="fa fa-info-circle" aria-hidden="true"></i> Conteúdo:</label>
                        <textarea id="article-content" name="article_content" class="form-control" rows="5"></textarea>
                    </div>
                    <hr>

                    <!-- BUTTON GROUP -->
                    <div class="text-center">
                        <div class="btn-group">
                            <input id="submit-article" style="width: 130%;" type="submit" class="btn confirm-btn"  name="submit_article" value="Submeter">
                        </div>
                    </div> <!-- end button group -->
                </form>
                <?php
                    if($error == 1) {
                        echo '<div id="div-erro" class="bg-danger signup-error mt-2">Por favor preencha todos os campos</div>';
                    }
                    else if($error == 0) {
                        echo '<div id="div-erro" class="bg-success signup-error mt-2">O artigo foi introduzido com sucesso</div>';
                    }
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

        </div> <!-- 1st row -->


    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>
