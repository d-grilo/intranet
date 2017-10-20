<?php
$page_title = 'Vista anual';
require_once('header.php');
require_once ('navbar.php');

# Data de hoje
$date = time();
$current_year = date('Y', $date);


$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('<h3>Falha ao conectar à base de dados</h3>');

$query_vacations = "SELECT ic.color_hex, iu.color, iu.name AS user_em_ferias, iu.user_id, iv.user_id,
                    YEAR(iv.vacation_start_date) AS vacation_ano_inicio, MONTH(iv.vacation_start_date) AS vacation_mes_inicio, DAY(iv.vacation_start_date) AS vacation_dia_inicio,
                    YEAR(iv.vacation_end_date) AS vacation_ano_fim, MONTH(iv.vacation_end_date) AS vacation_mes_fim, DAY(iv.vacation_end_date) AS vacation_dia_fim
                    FROM intranet_vacation AS iv
                    INNER JOIN intranet_user AS iu USING(user_id)
                    INNER JOIN intranet_color AS ic ON iu.color = ic.color_id";


$query_user_colors = "SELECT iu.name, iu.user_id, ic.color_hex FROM intranet_user AS iu
                      INNER JOIN intranet_color as ic ON iu.color = ic.color_id";




$data_vacation = mysqli_query($dbc, $query_vacations)
    or die('Erros graves: vacations');

$data_user_colors = mysqli_query($dbc, $query_user_colors)
    or die('Erros graves: cores');

$vacations = array();

while($row = mysqli_fetch_array($data_vacation)) {
    array_push($vacations, $row);
}

?>

<div class="main" >

    <div class="row">
        <!-- melhorar, usar div -->
        <h2 id="top-bar" class="top-label hidden-xs"><i class="fa fa-calendar ml-2" aria-hidden="true"></i> Calendário<span id="header-span" class="pull-right"><i class="fa fa-user-o" aria-hidden="true"></i>
                <?php echo $_SESSION['username'];?></span></h2>
    </div>

    <style type="text/css">

        .th-default-blue {
            background-color: #1A2129;
        }

        .find{
            color:#FFFFFF;
            background:#FF0000;
        }
        </style>

    <div class="container">


        <div class="row mt-3">

            <ol style="background-color: #1A2129;" class="breadcrumb mt-5">
                <li class="active">Vista anual (férias)</li>
                <li><a href="calendario.php">Vista mensal</a></li>

            </ol>

            <h2 class="top-label mt-3"><i class="fa fa-users" aria-hidden="true"></i> Utilizadores <button class="confirm-btn pull-right toggle-table"><i  class="fa fa-chevron-down chevron" aria-hidden="true"></i></button></h2>
            <ul class="list-group list-inline">
                <?php
                while($row = mysqli_fetch_array($data_user_colors)) {

                    $query_total_ferias = "SELECT * FROM  intranet_vacation WHERE YEAR(vacation_start_date) = '$current_year' AND user_id ='" . $row['user_id'] . "'";

                    $data_total_ferias = mysqli_query($dbc, $query_total_ferias);

                    $datediff = 0;
                    while ($row_ferias = mysqli_fetch_array($data_total_ferias)) {

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
                       }


                       }


                    echo '<li class="list-group-item edit-wiki-list ml-2 mt-2">' . $row['name'] . '&nbsp;';
                    echo '<span style="background-color:' . $row['color_hex'] . '" class="badge">';
                    echo floor($datediff / (60 * 60 * 24 ) ) . '</span></li>';
                }
                ?>
            </ul>

            <h2 class="top-label mt-5"><i class="fa fa-calendar" aria-hidden="true"></i> Vista anual</h2>


            <?php
    # Data de hoje
    $date = time();

    # Ano corrente
    $year = date('Y', $date);

    $find_dt='2017-08-08';

    for($i = 1 ; $i <= 12 ; $i++){
        calen($year, $i, $find_dt, $vacations);
        if($i == 3 || $i == 6 || $i == 9){
            echo '</div><div class="row">';
        }
    }


function calen($year,$month, $find_dt, $vacations){

    $find = 0;

    //find no.of days
    $last = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $d = new DateTime('' . $year . '-' . $month . '-01');
    $day = $d->format('w, F Y');

    $start = $day + 1;

    if(date('m',strtotime($find_dt)) == $month AND date('Y',strtotime($find_dt))==$year) {
        $find=1;
    }

    $div=array("1"=>"1","2"=>"0","3"=>"6","4"=>"5","5"=>"4","6"=>"3","7"=>"2","8"=>"1");

    $mons = array(1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");

    echo '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 mt-2"><table class="table-condensed" border="1 ">
    
    <tr bgcolor="#A9D4EA">
        <th class="th-default-blue" colspan="7"><a href="calendario.php?month=' . $month . '&year=' . $year . '">' . $mons[$month] . ' '. $year . ' </a></td></tr>
    <tr bgcolor="#FFFFCC">
        <th class="th-default-blue">Dom</th>
        <th class="th-default-blue">Seg</th>
        <th class="th-default-blue">Ter</th>
        <th class="th-default-blue">Qua</th><th class="th-default-blue">Qui</th>
        <th class="th-default-blue">Sex</th>
        <th class="th-default-blue">Sáb</th>
    </tr>';

    for($k = 1 ; $k < $start ; $k++){
        echo '<td></td>';
    }


    for($i = 1 ; $i <= $last ; $i++){
        $j = $i % 7;


        if ($j == $div[$start]) {
                echo '</tr><tr>';
            }

        foreach ($vacations as $vacation) {

            $erro = true;

            if ($i >= $vacation['vacation_dia_inicio'] && $i <= $vacation['vacation_dia_fim'] && $month == $vacation['vacation_mes_inicio'] && $year == $vacation['vacation_ano_inicio']) {
                echo '<td style="background-color: ' . $vacation['color_hex'] . '" align="center" width="15">' . $i . '</td>';
                $erro = false;
                break;
            }
            else if ($vacation['vacation_mes_fim'] != $vacation['vacation_mes_inicio']) {

                if ($vacation['vacation_dia_inicio'] <= $i && $vacation['vacation_mes_inicio'] == $month && $year == $vacation['vacation_ano_inicio']) {
                    echo '<td style="background-color: ' . $vacation['color_hex'] . '" align="center" width="15">' . $i . '</td>';
                    $erro = false;
                    break;
                }

                if ($vacation['vacation_dia_fim'] >= $i && $vacation['vacation_mes_fim'] == $month && $year == $vacation['vacation_ano_fim']) {
                    echo '<td style="background-color: ' . $vacation['color_hex'] . '" align="center" width="15">' . $i . '</td>';
                    $erro = false;
                    break;
                }
            }

        }

        if($erro)
            echo '<td align="center" width="15">' . $i . '</td>';


    }

    echo '</tr></table></div>';

    }
?>
        </div> <!-- end row -->
    </div> <!-- end container -->
</div> <!-- end main -->


<?php
require_once('footer.php');
?>
