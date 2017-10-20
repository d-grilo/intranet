<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php
echo '<h2 style="color: red">Erro: ' . $_GET['erro'] . '</h2>';


if ($_GET['erro'] == 1) {
    echo '<h4>Falha ao conectar à base de dados</h4>';
}
else if ($_GET['erro'] == 2) {
    echo '<h4>Falha ao comunicar com a base de dados</h4>';
}

else {
    echo 'Aconteceram coisas, coisas sérias.';
}

echo '<h5><a href="' . $_SERVER['HTTP_REFERER'] . '">Voltar  atrás</a></h5>';


?>
</body>
</html>