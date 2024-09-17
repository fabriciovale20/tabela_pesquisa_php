<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="./css/style.css">

    <title>Tabela de Nomenclaturas</title>
</head>
<body>
    <h1>Tabela de Nomenclaturas</h1>

    <form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default">Padrão MAC / Nº de Série:</span>
            <input type="text" class="form-control" name="padrao" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            <button class="btn btn-primary">Buscar</button>
        </div>
    </form>
    <br>

<!-- INÍCIO SCRIPT PHP -->
<!-- Abaixo comando PHP para validar e buscar os equipamentos -->
<?php
    // Validando se o parâmetro de busca está sendo clicado
    if (!isset($_GET['padrao'])) {
        $padrao = "%%";
    } else {
        // Armazenando o valor digita na variável.
        // O símbolo de porcentagem % é utilizada para que retorne qualquer resultado que cotenha a informação inserida
        $padrao = "%".trim($_GET['padrao'])."%";
    }


    // Realizar conexão com o banco de dados
    $dbh = new PDO('mysql:host=127.0.0.1:3309;dbname=tabela_equipamentos', 'root', '');

    //  Realizar consulta no banco de dados, a função "prepare" recebe a consulta
    $sth = $dbh->prepare('SELECT * FROM `equipamentos` WHERE `padrao` LIKE :padrao');

    $sth->bindParam(':padrao', $padrao, PDO::PARAM_STR);

    // Executar o comando de consulta
    $sth->execute();

    // Armazenando o resultado da consulta do banco de dados
    $resultados = $sth->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- FIM SCRIPT PHP -->

    <table class="table table-dark table-hover">
        <tr>
            <td><strong>Equipamento</strong></td>
            <td><strong>Marca</strong></td>
            <td><strong>Modelo</strong></td>
            <td><strong>Padrão</strong></td>
            <td><strong>Valor R$</strong></td>
            <td><strong>Imagem</strong></td>
            <td><strong>Situação</strong></td>
        </tr>
    <?php
        // Condição para identificar se houve preenchimento do input
        if(count($resultados)) {
            // Percorrendo a lista dos equipamentos
            foreach($resultados as $Resultado) {
                // Retorno caso seja identificado algum equipamento
                echo "<tr>
                        <td>".$Resultado['equipamento']."</td>
                        <td>".$Resultado['marca']."</td>
                        <td>".$Resultado['modelo']."</td>
                        <td>".$Resultado['padrao']."</td>
                        <td>R$ ".number_format($Resultado['valor'], 2, ',', '.')."</td>
                        <td><img src='./imagens/".$Resultado['imagem']."' width='150px' height='50px'></td>
                        <td>".$Resultado['situacao']."</td>
                    </tr>";
            }
        // Retorno caso não tenha identificado nenhum equipamento com o padrão informado
        } else {
            echo "<tr>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>";
}
    ?>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>