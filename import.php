<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Importar Excel .csv</h1>

    <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
    ?>

    <form action="processa.php" method="post" enctype="multipart/form-data">
        <label>Arquivo</label>
        <input type="file" name="arquivo" id="arquivo" accept="text/csv"><br><br>

        <input type="submit" value="Enviar">
    </form>
</body>
</html>