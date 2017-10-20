<?php
$page_title = 'Adicionar categoria';
require_once('header.php');
require_once ('navbar.php');

# Variável de erros
$error = 5;

# Metodo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    # A form foi submetida
    if (isset($_POST['submit_category'])) {

        # Conexão à BD
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or header('Location: ' . $error_url . '?erro=1');

        $category_name = mysqli_real_escape_string($dbc, trim($_POST['category_name']));

        if(!empty($category_name)) {

            $query = "SELECT category_name FROM intranet_wiki_category WHERE category_name = '$category_name'";

            $data = mysqli_query($dbc, $query)
            or header('Location: ' . $error_url . '?erro=2');

            if(mysqli_num_rows($data) == 0) {

                $query = "INSERT INTO intranet_wiki_category(category_name)" .
                    "VALUES ('$category_name')";

                mysqli_query($dbc, $query)
                or header('Location: ' . $error_url . '?erro=2');

                $error = 0;


            } // ja existe esta categoria
            else{
                $error = 1;
            }

        } // empty category name
        else {
            $error = 2;
        }

    } // isset submit category


} // post method


?>

<div class="main">

    <!-- 1st row -->
    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-info-circle ml-2" aria-hidden="true"></i> Wiki<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->

    <div class="container" >

        <div class="row mt-6 mb-3" id="section-add-category">

            <div class="col-sm-6 col-sm-offset-3">

                <h2 class="mb-2 top-label"><i class="fa fa-folder pull-left" aria-hidden="true"></i> Nova categoria</h2>

                <form method="post" id="add-category-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                    <div class="form-group">
                        <label for="category-name" class="control-label secondary-label"><i class="fa fa-clipboard"></i> Nome:</label>
                        <input id="category-name" name="category_name" type="text" class="form-control" placeholder="Nome da categoria" value="<?php if (!empty($category_name)) echo $category_name; ?>">
                    </div>

                    <hr>

                    <div class="text-center">
                        <div class="btn-group">
                            <input id="submit-category" type="submit" class="btn confirm-btn"  name="submit_category" value="Submeter">
                        </div>
                    </div>

                </form>
                <?php
                if($error == 1) {
                    echo '<div id="div-erro" class="bg-danger signup-error mt-2">A categoria escolhida já existe</div>';
                }
                else if($error == 2) {
                    echo '<div id="div-erro" class="bg-danger signup-error mt-2">Por favor introduza o nome da categoria</div>';

                }
                else if($error == 0) {
                    echo '<div id="div-erro" class="bg-success signup-error mt-2">A categoria foi introduzida com sucesso</div>';

                }

                ?>

            </div> <!-- end col -->

        </div> <!-- 1st invisible row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>

