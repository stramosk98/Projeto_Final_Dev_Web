<?php

session_start();
if(!empty($_POST['email']) && !empty($_POST['senha'])){
    
    include_once('acaologin.php');
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $sql = "SELECT * FROM tbaluno WHERE email = '$email' and senha = '$senha'";
    
    $result = $conexao->query($sql);

    if(mysqli_num_rows($result) < 1) {
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: loginAluno.html');
    }

    else{
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            header('Location: indexAluno.html');
        }
}
else {
    header('Location: loginAluno.html');
}

?>