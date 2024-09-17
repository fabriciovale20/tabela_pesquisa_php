<?php
    session_start();

    // Para hospedagem compartilhada
    ob_start();

    // Início da conexão com o banco de dados utilizando PDO
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "tabela_equipamentos";
    $port = 3309;

    try {
        // Conexão com a porta
        $conn = new PDO("mysql:host=$host;port=$port;dbname=". $dbname, $user, $pass);

        // Conexão sem a porta
        // $conn = new PDO("mysql:host=$host;dbname=".$dbname, $user, $pass);
        // echo "Conexão com banco de dados realizada com sucesso.";
    } catch (PDOException $err) {
        echo "Erro: Conexão com banco de dados não realizado com sucesso. Erro gerado" . $err->getMessage();
    }

    // Fim da conexão com o banco de dados utilizando PDO

    $arquivo = $_FILES['arquivo'];
    // var_dump($arquivo);

    $primeira_linha = true;
    $linhas_importadas = 0;
    $linhas_nao_importadas = 0;
    $equipamentos_nao_importado = "";

    if ($arquivo['type'] == "text/csv") {
        $dados_arquivo = fopen($arquivo['tmp_name'], "r");

        while ($linha = fgetcsv($dados_arquivo, 1000,";")) {

            if ($primeira_linha) {
                $primeira_linha = false;
                continue;
            }

            array_walk_recursive($linha, 'converter');
            // echo "<pre>".var_dump($linha)."</pre>";

            $query_equipamento = "INSERT INTO equipamentos (equipamento, marca, modelo, padrao, valor, imagem, situacao) VALUES (:equipamento, :marca, :modelo, :padrao, :valor, :imagem, :situacao)";

            $cad_equipamento = $conn->prepare($query_equipamento);
            $cad_equipamento->bindValue(':equipamento', ($linha[0] ?? "NULL"));
            $cad_equipamento->bindValue(':marca', ($linha[1] ?? "NULL"));
            $cad_equipamento->bindValue(':modelo', ($linha[2] ?? "NULL"));
            $cad_equipamento->bindValue(':padrao', ($linha[3] ?? "NULL"));
            $cad_equipamento->bindValue(':valor', ($linha[4] ?? "NULL"));
            $cad_equipamento->bindValue(':imagem', ($linha[5] ?? "NULL"));
            $cad_equipamento->bindValue(':situacao', ($linha[6] ?? "NULL"));
            $cad_equipamento->execute();

            if ($cad_equipamento->rowCount()) {
                $linhas_importadas++;
            } else {
                $linhas_nao_importadas++;
                $equipamentos_nao_importado = $equipamentos_nao_importado . ", " .($linha[0] ?? "NULL");
            }
        }

        if (!empty($equipamentos_nao_importado)) {
            $equipamentos_nao_importado = "Equipamentos não importados: $equipamentos_nao_importado.";
        }

        $_SESSION['msg'] = "<p style='color: green;'>$linhas_importadas linha(s) importada(s), $linhas_nao_importadas linha(s) não importada(s).</p>";
        header("Location: import.php");
    } else {
        $_SESSION['msg'] = "<p style='color: red;'>Não é um arquivo csv!</p>";
        header("Location: import.php");
    }

    // Criar função valor por referência, isto é, quando alterar o valor de dentro da função, vale para a variável fora da função
    function converter($dados_arquivo) {
        // Converter dados de ISO-8859-1 para UTF-8
        $dados_arquivo = mb_convert_encoding($dados_arquivo, "UTF-8", "ISO-8859-1");
    }
?>