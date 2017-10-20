<?php
require_once('../connectvars.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('falha');

if (isset($_REQUEST['id'])) {

    $id = intval($_REQUEST['id']);
    $query = "SELECT intranet_note.* , iu.name
              FROM intranet_note
              INNER JOIN intranet_user AS iu USING(user_id)
              WHERE note_id= '$id'";

    $data = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($data);
    extract($row);

    ?>

    <div class="table-responsive">

        <table class="table table-striped table-bordered">
            <tr>
                <th>Utilizador</th>
                <td><?php echo $row['name'] ?></td>
            </tr>
            <tr>
                <th>Conteúdo</th>
                <td><?php echo $row['note_content']; ?></td>
            </tr>
        </table>

    </div>

    <?php
}