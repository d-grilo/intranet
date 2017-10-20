<?php
$page_title = 'Editar categoria';
require_once('header.php');
require_once ('navbar.php');

$sucesso = false;

if(isset($_POST['submit_edit_category'])) {

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        or die('<h3>Falha ao conectar à base de dados');

    $category_id =  mysqli_real_escape_string($dbc, trim($_POST['category_id']));
    $category_name = mysqli_real_escape_string($dbc, trim($_POST['edit_category_name']));

    $query = "UPDATE intranet_wiki_category SET category_name = '$category_name'  WHERE category_id = '$category_id';";

    mysqli_query($dbc, $query)
        or die('<h3>Falha ao comunicar com a base de dados');

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

        <div class="row mt-6 mb-3" id="section-edit-article">

            <div class="col-sm-6 col-sm-offset-3">

                <?php
                if (isset($_GET['category_id'])) {
                    $category_id = $_GET['category_id'];

                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                    or die('<h3>Falha ao conectar à base de dados');

                    $query = "SELECT * FROM intranet_wiki_category WHERE category_id = '$category_id'";

                    $data = mysqli_query($dbc, $query)
                    or die('<h3>Falha ao comunicar com base de dados');

                    $row = mysqli_fetch_assoc($data);

                    echo '<h3 class="top-label mb-2"><i class="fa fa-folder" aria-hidden="true"></i> ' . $row['category_name'] . ' </h3>';
                }
                ?>

                <!-- Edit category form -->
                <form method="post" id="edit-category-form" action="edit_category.php?category_id=<?php echo $category_id;?>">
                    <input type="hidden" name="category_id" value="<?php echo $category_id;?>">
                    <div class="form-group">
                        <label for="edit-category-name" class="control-label secondary-label" ><i class="fa fa-info-circle" aria-hidden="true"></i> Nome:</label>
                        <input id="edit-category-name" name="edit_category_name" class="form-control" value="<?php echo $row['category_name'];?>">
                    </div>
                    <!-- BUTTON GROUP -->
                    <div class="text-center">
                        <div class="btn-group">
                            <button name="submit_edit_category" style="width: 130%;" type="submit" class="btn confirm-btn"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</button>
                        </div>
                    </div>
                </form>

                <?php
                if($sucesso)
                    echo '<div id="div-erro" class="bg-success text-center mt-2">Categoria editada com sucesso</div>';
                ?>

            </div> <!-- end col -->

        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>