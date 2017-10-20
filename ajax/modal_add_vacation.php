<?php
session_start();
require_once('../connectvars.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

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

            echo json_encode(1); // correu tudo bem

            # Fechar conexão
            mysqli_close($dbc);

        } // numrows== 0
        else {
            echo json_encode(2); // já existe férias para este utilizador durante este prazo
        }

    } // empty fields
    else {
        echo json_encode(4); // empty fields
    }


} // o utilizador existe
else {
    echo json_encode(3); // utilizador inexistente
}