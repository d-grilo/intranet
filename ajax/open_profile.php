<?php
require_once('../connectvars.php');

# Data de hoje
$date = time();

$year = date('Y', $date);

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
or die('falha');

if (isset($_REQUEST['id'])) {

    $id = intval($_REQUEST['id']);

    # Utilizadores
    $query = "SELECT * FROM intranet_user WHERE user_id= '$id'";

    $data = mysqli_query($dbc, $query);

    $row = mysqli_fetch_array($data);

    extract($row);

    # Férias
    $query_ferias ="SELECT * FROM  intranet_vacation WHERE YEAR(vacation_start_date) = '$year' AND user_id = '$id'";

    $data_ferias = mysqli_query($dbc, $query_ferias);

    ?>

    <div class="table-responsive">

        <table class="table table-striped table-bordered">
            <tr>
                <th>Nome</th>
                <td><?php echo $row['name'] ?></td>
            </tr>
            <tr>
                <th>Username</th>
                <td><?php echo $row['username'] ?></td>
            </tr>
            <tr>
                <th>Data Nascimento</th>
                <td><?php echo $row['birthday'] ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $row['email']; ?></td>
            </tr>
            <tr>
                <th>Férias Marcadas</th>
                <td><?php
                    $datediff = 0;
                    while ($row_ferias = mysqli_fetch_array($data_ferias)) {

                        $year_begin = date('Y', strtotime($row_ferias['vacation_start_date']));
                        $year_end = date('Y', strtotime($row_ferias['vacation_end_date']));

                        if($year_begin != $year_end) {

                            $originalDate = $row_ferias['vacation_start_date'];
                            $originalDate2 = $row_ferias['vacation_end_date'];
                            $newDate = date("d-m-Y", strtotime($originalDate));
                            $newDate2 = date("d-m-Y", strtotime($originalDate2));

                            $first_date = strtotime($newDate);
                            $second_date = strtotime($newDate2);

                            $original_date_beggining_year = '01-01-' . $year_end;
                            $new = date("d-m-Y", strtotime($original_date_beggining_year));
                            $date_beggining_year = strtotime($new);

                            $datediff += $second_date - $first_date;

                            $datediff -= $second_date - $date_beggining_year;


                            $datediff += 86400;
                            $datediff -= 86400;


                        }
                        else {


                            $originalDate = $row_ferias['vacation_start_date'];
                            $originalDate2 = $row_ferias['vacation_end_date'];
                            $newDate = date("d-m-Y", strtotime($originalDate));
                            $newDate2 = date("d-m-Y", strtotime($originalDate2));

                            $first_date = strtotime($newDate);
                            $second_date = strtotime($newDate2);

                            $datediff += $second_date - $first_date;


                            $datediff += 86400;
                            if ($newDate == $newDate2)
                                echo $newDate . '<br>';
                            else
                                echo 'De: ' . $newDate . ' Até: ' . $newDate2 . '<br>';
                        }
                    }
                    if (mysqli_num_rows($data_ferias) == 0) { echo 'Sem férias marcadas'; }

                    ?>
                </td>

            </tr>
            <tr>
                <th>Dias de férias marcados</th>
                <td><?php echo floor($datediff / (60 * 60 * 24 ) ); ?></td>
            </tr>
        </table>

    </div>

    <?php
}
