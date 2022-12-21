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
    $escola = dadosFormularioParaVetor();
    // montar consulta
    if ($escola)
        if ($id > 0) // se o ID está informado é atualização
            editar($escola);
        else // senão será inserido um novo registro
            inserir($escola);
    else
        echo "Erro. Dados não preenchidos";
}

function dadosFormularioParaVetor(){
    if (isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['telefone']) && isset($_POST['endereco'])){
        $escola = array(   'nome'       =>  isset($_POST['nome'])?$_POST['nome']:"",
                           'email'      =>  isset($_POST['email'])?$_POST['email']:"",
                           'telefone'   =>  isset($_POST['telefone'])?$_POST['telefone']:"",
                           'endereco'   =>  isset($_POST['endereco'])?$_POST['endereco']:"",
                           'id'         =>  isset($_POST['id'])?$_POST['id']:0);
        return $escola;
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
    $query = 'DELETE FROM tbescola WHERE id = :id';
    $stmt = $conexao->prepare($query);
    $stmt->bindValue(':id',$id);
    // executar a consulta
    if ($stmt->execute())
        header('location: cadEscola.php');
    else
        echo 'Erro ao excluir dados';
}

function inserir($escola){
        // cria a conexão com o banco de dados 
        $conexao = criaConexao();
        // montar consulta

        $query = 'INSERT INTO tbescola (nome, email, telefone, endereco) 
                       VALUES (:nome, :email, :telefone, :endereco)';
        // preparar consulta
        $stmt = $conexao->prepare($query);
        // vincular variaveis com a consulta
        $stmt->bindValue(':nome',$escola['nome']);        
        $stmt->bindValue(':email',$escola['email']); 
        $stmt->bindValue(':telefone',$escola['telefone']);
        $stmt->bindValue(':endereco',$escola['endereco']);

        // executar a consulta
        if ($stmt->execute())
            header('location: cadEscola.php');
        else
            echo 'Erro ao inserir/editar dados';
}

function editar($escola){
    // cria a conexão com o banco de dados 
    $conexao = criaConexao();
    // montar consulta

    $query = 'UPDATE tbescola 
                 SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco, 
               WHERE id = :id';
    // preparar consulta
    $stmt = $conexao->prepare($query);
    // vincular variaveis com a consulta
    $stmt->bindValue(':nome',$escola['nome']);         
    $stmt->bindValue(':email',$escola['email']);  
    $stmt->bindValue(':telefone',$escola['telefone']);
    $stmt->bindValue(':endereco',$escola['endereco']);  
    $stmt->bindValue(':id',$escola['id']);

    // executar a consulta
    if ($stmt->execute())
        header('location: cadEscola.php');
    else
        echo 'Erro ao inserir/editar dados';
}
?>