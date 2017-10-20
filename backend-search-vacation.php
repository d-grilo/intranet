<?php
session_start();

# Inclui as constantes da aplicação
require_once('connectvars.php');

$page_title = NULL;

# Script para redirecionar caso o utilizador não esteja autenticado
require_once ('redirect.php');


# Attemp MySQL server connection.
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

# Check connection
if ($link === FALSE) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

if(isset($_REQUEST['utilizadores'])) {

    # Prepare a select statement
    $sql = "SELECT name, user_id FROM intranet_user WHERE name LIKE ? LIMIT 2";

    if($stmt = mysqli_prepare($link, $sql)) {
        # Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_term);

        # Set the parameters
        $param_term = $_REQUEST['utilizadores'] . '%';

        # Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            # Check the number of rows in the result set
            if (mysqli_num_rows($result) > 0) {
                # Fetch result rows as an associative array
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo "<p style=\"color: ;\" width: 100%;>" . $row["name"] . "</p>";

                }
            } else {
                echo "<p>Sem resultados</p>";
            }

        } else {
            echo "ERROR: Could not execute $sql. " . mysqli_error($link);
        }

    } // stmt mysqliprepare

    # Close statement
    mysqli_stmt_close($stmt);

} // isset request

# Close connection
mysqli_close($link);

?>