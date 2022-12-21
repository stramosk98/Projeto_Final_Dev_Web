<?php

session_start();
if(!empty($_POST['usuario']) && !empty($_POST['senha'])){
    
    include_once('../alunoInterface/acaologin.php');
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $sql = "SELECT * FROM tbadmin WHERE usuario = '$usuario' and senha = '$senha'";
   //$idAluno = "SELECT id FROM aluno WHERE email = '$email' and senha = '$senha'";
    $result = $conexao->query($sql);

    

    if(mysqli_num_rows($result) < 1) {
        unset($_SESSION['usuario']);
        unset($_SESSION['senha']);
        header('Location: loginAdmin.html');
    }

    else{
            $_SESSION['usuario'] = $usuario;
            $_SESSION['senha'] = $senha;
            header('Location: index.html');
        }
}
else {
    header('Location: loginAdmin.html');
}
    
?>