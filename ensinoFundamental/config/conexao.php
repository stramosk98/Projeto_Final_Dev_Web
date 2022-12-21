<?php
include "conf.inc.php";
try{
    $conexao =  new PDO(MYSQL_DSN,DB_USER,DB_PASSWORD);
}catch(PDOException $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
        print("Erro ao conectar com o banco de dados...<br>".$e->getMessage());
        die();
}catch(Exception $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
        print("Erro genérico...<br>".$e->getMessage());
        die();
}
?>