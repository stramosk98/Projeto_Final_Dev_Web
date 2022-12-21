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
    $turma = dadosFormularioParaVetor();
    // montar consulta
    if ($turma)
        if ($id > 0) // se o ID está informado é atualização
            editar($turma);
        else // senão será inserido um novo registro
            inserir($turma);
    else
        echo "Erro. Dados não preenchidos";
}

function dadosFormularioParaVetor(){
    if (isset($_POST['idprofessor']) && isset($_POST['capacidade']) && isset($_POST['serie'])){
        $turma = array('idprofessor' =>  isset($_POST['idprofessor'])?$_POST['idprofessor']:"",
                       'capacidade'  =>  isset($_POST['capacidade'])?$_POST['capacidade']:"",
                           'serie'   =>  isset($_POST['serie'])?$_POST['serie']:"",
                           'id'      =>  isset($_POST['id'])?$_POST['id']:0);
        return $turma;
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
    $query = 'DELETE FROM tbturma WHERE id = :id';
    $stmt = $conexao->prepare($query);
    $stmt->bindValue(':id',$id);
    // executar a consulta
    if ($stmt->execute())
        header('location: cadTurma.php');
    else
        echo 'Erro ao excluir dados';
}

function inserir($turma){
        // cria a conexão com o banco de dados 
        $conexao = criaConexao();
        // montar consulta

        $query = 'INSERT INTO tbturma (idprofessor, capacidade, serie) 
                       VALUES (:idprofessor, :capacidade, :serie)';
        // preparar consulta
        $stmt = $conexao->prepare($query);
        // vincular variaveis com a consulta
        $stmt->bindValue(':idprofessor',$turma['idprofessor']);    
        $stmt->bindValue(':capacidade',$turma['capacidade']);    
        $stmt->bindValue(':serie',$turma['serie']);

        // executar a consulta
        if ($stmt->execute())
            header('location: cadTurma.php');
        else
            echo 'Erro ao inserir/editar dados';
}

function editar($turma){
    // cria a conexão com o banco de dados 
    $conexao = criaConexao();
    // montar consulta

    $query = 'UPDATE tbturma 
                 SET idprofessor = :idprofessor, capacidade = :capacidade, serie = :serie, 
               WHERE id = :id';
    // preparar consulta
    $stmt = $conexao->prepare($query);
    // vincular variaveis com a consulta
    $stmt->bindValue(':idprofessor',$turma['idprofessor']);  
    $stmt->bindValue(':capacidade',$turma['capacidade']);  
    $stmt->bindValue(':serie',$turma['serie']); 
    $stmt->bindValue(':id',$turma['id']);

    // executar a consulta
    if ($stmt->execute())
        header('location: cadTurma.php');
    else
        echo 'Erro ao inserir/editar dados';
}
?>