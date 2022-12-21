<?php

include_once('../../config/conf.inc.php');     // arquivo de configuração
// acao.php é responsável por inserir, editar e excluir um registro no banco de dados
$acao =  isset($_GET['acao'])?$_GET['acao']:"";

if ($acao == 'excluir'){ // exclui um registro do banco de dados
    try{
        $id =  isset($_GET['id'])?$_GET['id']:0;  // se for exclusão o ID vem via GET        
        excluir($id);
    }catch(PDOException $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
        print("Erro ao conectar com o banco de dados...<br>".$e->getMessage());
        die();
    }
}else{ // então é para inserir ou atualizar
    // cria a conexão com o banco de dados 
    $conexao = criaConexao();
    $aluno = dadosFormularioParaVetor();
    // montar consulta
    if ($aluno)
        if ($id > 0) // se o ID está informado é atualização
            editar($aluno);
        else // senão será inserido um novo registro
            inserir($aluno);
    else
        echo "Erro. Dados não preenchidos";
}

function dadosFormularioParaVetor(){
    if (isset($_POST['nome']) && isset($_POST['dtnasc']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['telefone']) && isset($_POST['sexo']) && isset($_POST['turma'])){
        $aluno = array(    'nome'       =>  isset($_POST['nome'])?$_POST['nome']:"",
                           'dtnasc'     =>  isset($_POST['dtnasc'])?$_POST['dtnasc']:"",
                           'email'      =>  isset($_POST['email'])?$_POST['email']:"",
                           'senha'      =>  isset($_POST['senha'])?$_POST['senha']:"",
                           'telefone'   =>  isset($_POST['telefone'])?$_POST['telefone']:"",
                           'sexo'       =>  isset($_POST['sexo'])?$_POST['sexo']:"",
                           'turma'      =>  isset($_POST['turma'])?$_POST['turma']:"",
                           'id'         =>  isset($_POST['id'])?$_POST['id']:0);
        return $aluno;
    }else{
        return null;
    }
}

function criaConexao(){
    try{
        return new PDO(MYSQL_DSN,DB_USER,DB_PASSWORD);
    }catch(PDOException $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
            print("Erro ao conectar com o banco de dados...<br>".$e->getMessage());
            die();
    }catch(Exception $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
            print("Erro genérico...<br>".$e->getMessage());
            die();
    }
}

function excluir($id){
    // cria a conexão com o banco de dados 
    $conexao = criaConexao();
    $query = 'DELETE FROM tbaluno WHERE id = :id';
    $stmt = $conexao->prepare($query);
    $stmt->bindValue(':id',$id);
    // executar a consulta
    if ($stmt->execute())
        header('location: cadAluno.php');
    else
        echo 'Erro ao excluir dados';
}

function inserir($aluno){
        // cria a conexão com o banco de dados 
        $conexao = criaConexao();
        // montar consulta

        $query = 'INSERT INTO tbaluno (nome, dtnasc, email, senha, telefone, sexo, turma) 
                       VALUES (:nome, :dtnasc, :email, :senha, :telefone, :sexo, :turma)';
        // preparar consulta
        $stmt = $conexao->prepare($query);
        // vincular variaveis com a consulta
        $stmt->bindValue(':nome',$aluno['nome']);    
        $stmt->bindValue(':dtnasc',$aluno['dtnasc']);    
        $stmt->bindValue(':email',$aluno['email']);        
        $stmt->bindValue(':senha',$aluno['senha']);
        $stmt->bindValue(':telefone',$aluno['telefone']);
        $stmt->bindValue(':sexo',$aluno['sexo']);
        $stmt->bindValue(':turma',$aluno['turma']);

        // executar a consulta
        if ($stmt->execute())
            header('location: cadAluno.php');
        else
            echo 'Erro ao inserir/editar dados';
}

function editar($aluno){
    // cria a conexão com o banco de dados 
    $conexao = criaConexao();
    // montar consulta

    $query = 'UPDATE tbaluno 
                 SET nome = :nome, dtnasc = :dtnasc, email = :email, senha = :senha, telefone = :telefone, sexo = :sexo, turma = :turma, 
               WHERE id = :id';
    // preparar consulta
    $stmt = $conexao->prepare($query);
    // vincular variaveis com a consulta
    $stmt->bindValue(':nome',$aluno['nome']);  
    $stmt->bindValue(':dtnasc',$aluno['dtnasc']);        
    $stmt->bindValue(':email',$aluno['email']);        
    $stmt->bindValue(':senha',$aluno['senha']);
    $stmt->bindValue(':telefone',$aluno['telefone']);  
    $stmt->bindValue(':sexo',$aluno['sexo']);  
    $stmt->bindValue(':turma',$aluno['turma']);  
    $stmt->bindValue(':id',$aluno['id']);

    // executar a consulta
    if ($stmt->execute())
        header('location: cadAluno.php');
    else
        echo 'Erro ao inserir/editar dados';
}
?>