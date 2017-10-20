<?php
$page_title = 'Marcação de férias';
require_once('header.php');
require_once ('navbar.php');

$error = 5;

# Método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    # A form foi submetida
    if (isset($_POST['submit_vacation'])) {

        # Conexão à BD
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or die('<h3>Falha ao conectar à base de dados</h3>');

        $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $vacation_start = mysqli_real_escape_string($dbc, trim($_POST['vacation_start_date']));
        $vacation_end = mysqli_real_escape_string($dbc, trim($_POST['vacation_end_date']));

        # Obter o user_id do utilizador escolhido
        $query = "SELECT user_id FROM intranet_user WHERE name = '$username'";
        $data = mysqli_query($dbc, $query);


        # Só continuar caso todos os campos estejam preenchidos
        if (!empty($username) && !empty($vacation_start) && !empty($vacation_end)) {

            # Só continuar caso exista um utilizador com este nome
            if(mysqli_num_rows($data) == 1) {

                $id = mysqli_fetch_assoc($data);

                $user_id = $id['user_id'];



                $query = "SELECT vacation_start_date, vacation_end_date, user_id FROM intranet_vacation
                          WHERE vacation_start_date = '$vacation_start' AND vacation_end_date = '$vacation_end' AND user_id = '$user_id'";

                $data = mysqli_query($dbc, $query)
                or die('<h3>Falha ao comunicar com a base de dados</h3>');

                # Apenas criar o período de férias caso não seja repetido
                if (mysqli_num_rows($data) == 0) {

                    $query = "INSERT INTO intranet_vacation(vacation_start_date, vacation_end_date, user_id)" .
                        "VALUES ('$vacation_start', '$vacation_end', '$user_id')";

                    mysqli_query($dbc, $query)
                    or die('<h3>Falha ao comunicar com a base de dados</h3>');

                    $error = 0;

                    # Fechar conexão
                    mysqli_close($dbc);



                } // numrows== 0
                else {
                    $error = 1;
                }

            } // empty fields
            else {
                $error = 3;
            }


        } // o utilizador existe
        else {
            $error = 2;
        }


    } // isset submit vacation


} // post request


?>

<div class="main" >

    <!-- 1st row -->
    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-wrench ml-2" aria-hidden="true"></i> Administração<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div> <!-- end 1st row -->

    <div class="container" >

        <div class="row mt-6 mb-3   " id="section-add-vacation">

            <div class="col-sm-6 col-sm-offset-3">

                <h2 class="mb-2 top-label"><i class="fa fa-palm-tree pull-left" aria-hidden="true"></i>Férias</h2>

                <form method="post" id="add-task-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                    <div class="form-group">
                        <div style="width: 100%;" class="search-box-vacation ajax-search-box">
                            <label for="search-box-input" class="control-label secondary-label"><i class="fa fa-clipboard aria-hidden="true"></i> Nome:</label>

                            <?php
                            if(isset($_GET['user_id'])) {

                                # Conexão à BD
                                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                    or die('<h3>Falha ao conectar à base de dados</h3>');

                                $ui = $_GET['user_id'];
                                $query = "SELECT name FROM intranet_user WHERE user_id = '$ui'";

                                $data = mysqli_query($dbc, $query);
                                $u_name = mysqli_fetch_assoc($data);
                                $name = $u_name['name'];
                            }
                            ?>
                            <input class="search-box-input" name="username" type="text" autocomplete="off" placeholder="Procurar utilizador.." value="<?php if(isset($name)) echo $name;?>">

                            <div class="result-vacation ajax-result"></div>
                        </div>
                        <hr>

                        <div class="form-group">
                            <label for="vacation-start-date" class="secondary-label"><i class="fa fa-calendar" aria-hidden="true"></i> Data Ínicio:</label>
                            <input id="vacation-start-date" name="vacation_start_date" class="form-control datepicker" value="<?php if (!empty($vacation_start)) echo $vacation_start;?>">
                        </div>
                        <div class="form-group">
                            <label for="vacation-end-date" class="secondary-label"><i class="fa fa-calendar" aria-hidden="true"></i> Data fim:</label>
                            <input id="vacation-end-date" name="vacation_end_date" class="form-control datepicker" value="<?php if (!empty($vacation_end)) echo $vacation_end;?> ">
                        </div>

                        <hr>

                        <div class="text-center">
                            <div class="btn-group">
                                <input id="submit-vacation" style="width: 130%;" type="submit" class="btn confirm-btn"  name="submit_vacation" value="Submeter">
                            </div>
                        </div>

                    </div>

                </form>

                <?php
                if($error == 1) {
                    echo '<div class="bg-danger signup-error mt-2">Já existe um prazo de férias previstas para este utilizador neste espaço de tempo</div>';
                }
                else if($error == 2) {
                    echo '<div class="bg-danger signup-error mt-2">Por favor preencha todos os campos</div>';
                }
                else if($error == 3) {
                    echo '<div class="bg-danger signup-error mt-2">Utilizador inexistente</div>';
                }
                else if($error == 0) {
                    echo '<div class="bg-success signup-error mt-2">O prazo de férias foi criado com sucesso</div>';
                }
                ?>

            </div> <!-- end col -->

        </div> <!-- end row -->

    </div> <!-- end container -->

</div> <!-- end main -->

<?php
require_once('footer.php');
?>