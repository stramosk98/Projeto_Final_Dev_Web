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
    $professor = dadosFormularioParaVetor();
    // montar consulta
    if ($professor)
        if ($id > 0) // se o ID está informado é atualização
            editar($professor);
        else // senão será inserido um novo registro
            inserir($professor);
    else
        echo "Erro. Dados não preenchidos";
}

function dadosFormularioParaVetor(){
    if (isset($_POST['nome']) && isset($_POST['dtnasc']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['telefone']) && isset($_POST['sexo']) && isset($_POST['disciplina'])){
        $professor = array('nome'       =>  isset($_POST['nome'])?$_POST['nome']:"",
                           'dtnasc'     =>  isset($_POST['dtnasc'])?$_POST['dtnasc']:"",
                           'email'      =>  isset($_POST['email'])?$_POST['email']:"",
                           'senha'      =>  isset($_POST['senha'])?$_POST['senha']:"",
                           'telefone'   =>  isset($_POST['telefone'])?$_POST['telefone']:"",
                           'sexo'       =>  isset($_POST['sexo'])?$_POST['sexo']:"",
                           'disciplina' =>  isset($_POST['disciplina'])?$_POST['disciplina']:"",
                           'id'         =>  isset($_POST['id'])?$_POST['id']:0);
        return $professor;
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
    $query = 'DELETE FROM tbprofessor WHERE id = :id';
    $stmt = $conexao->prepare($query);
    $stmt->bindValue(':id',$id);
    // executar a consulta
    if ($stmt->execute())
        header('location: cadProfessor.php');
    else
        echo 'Erro ao excluir dados';
}

function inserir($professor){
        // cria a conexão com o banco de dados 
        $conexao = criaConexao();
        // montar consulta

        $query = 'INSERT INTO tbprofessor (nome, dtnasc, email, senha, telefone, sexo, disciplina) 
                       VALUES (:nome, :dtnasc, :email, :senha, :telefone, :sexo, :disciplina)';
        // preparar consulta
        $stmt = $conexao->prepare($query);
        // vincular variaveis com a consulta
        $stmt->bindValue(':nome',$professor['nome']);    
        $stmt->bindValue(':dtnasc',$professor['dtnasc']);    
        $stmt->bindValue(':email',$professor['email']);        
        $stmt->bindValue(':senha',$professor['senha']);
        $stmt->bindValue(':telefone',$professor['telefone']);
        $stmt->bindValue(':sexo',$professor['sexo']);
        $stmt->bindValue(':disciplina',$professor['disciplina']);

        // executar a consulta
        if ($stmt->execute())
            header('location: cadProfessor.php');
        else
            echo 'Erro ao inserir/editar dados';
}

function editar($professor){
    // cria a conexão com o banco de dados 
    $conexao = criaConexao();
    // montar consulta

    $query = 'UPDATE tbprofessor 
                 SET nome = alterado, dtnasc = :dtnasc, email = :email, senha = :senha, telefone = :telefone, sexo = :sexo, disciplina = :disciplina, 
               WHERE id = :id';
    // preparar consulta
    $stmt = $conexao->prepare($query);
    // vincular variaveis com a consulta
    $stmt->bindValue('alterado',$professor['nome']);  
    $stmt->bindValue(':dtnasc',$professor['dtnasc']);        
    $stmt->bindValue(':email',$professor['email']);        
    $stmt->bindValue(':senha',$professor['senha']);
    $stmt->bindValue(':telefone',$professor['telefone']);  
    $stmt->bindValue(':sexo',$professor['sexo']);  
    $stmt->bindValue(':disciplina',$professor['disciplina']);  
    $stmt->bindValue(':id',$professor['id']);

    // executar a consulta
    if ($stmt->execute())
        header('location: cadProfessor.php');
    else
        echo 'Erro ao inserir/editar dados';
}
?>