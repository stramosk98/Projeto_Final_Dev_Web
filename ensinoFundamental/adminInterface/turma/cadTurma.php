<?php

include_once('../../config/conf.inc.php');


// pega variáveis enviadas via GET - são enviadas para edição de um registro
$acao = isset($_GET['acao'])?$_GET['acao']:"";
$id = isset($_GET['id'])?$_GET['id']:"";
// verifica se está editando um registro
if ($acao == 'editar'){
    // buscar dados do usuário que estamos editando
    try{
        // cria a conexão com o banco de dados 
        $conexao = new PDO(MYSQL_DSN,DB_USER,DB_PASSWORD);
        // montar consulta
        $query = 'SELECT * FROM tbturma WHERE id = :id' ;
        // preparar consulta
        $stmt = $conexao->prepare($query);
        // vincular variaveis com a consult
        $stmt->bindValue(':id',$id); 
        // executa a consulta
        $stmt->execute();
        // pega o resultado da consulta - nesse caso a consulta retorna somente um registro pq estamos buscando pelo ID que é único 
        // por isso basta um fetch
        $turma = $stmt->fetch(); 
         
    }catch(PDOException $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
        print("Erro ao conectar com o banco de dados...<br>".$e->getMessage());
        die();
    }  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
 
    <title>Cadastro de Turma</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/estilo.css"> 
</head>
    <body class='container'>
    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-body">

    <h1>Cadastrar novo Turma</h1>
        <section id='cadTurma' class='row'>
        <!-- Formulário para cadastro e edição de dados do turma, 
        caso seja aberto a página com a ação de editar o formulário trará os campos preenchidos com os dados do registro selecionado  -->
      
            <form id="form-contato" action="acao.php" method="post">  <!-- esse formulário envia os dados para o arquivo acao.php -->
                    <div class='col-1'>
                        <label for="id">Id:</label>
                        <input type="text" class='form-control' style='width:50px' readonly name="id" id="id">
                    </div>
                    <div class='col-2'>
                        <label for="name">IdProfessor:</label>
                        <input type="number" class='form-control' style='width:80px' name='idprofessor' id='idprofessor' placeholder="5" value=<?php if(isset($turma)) echo $turma['idprofessor'] ?> >
                    </div>
                    <div class='col-2'>
                        <label for="name">Capacidade:</label>
                        <input type="number" class='form-control' style='width:80px' name='capacidade' id='capacidade' placeholder="35"  value=<?php if(isset($turma)) echo $turma['capacidade'] ?> >
                    </div>
                    <div class='col-2'>
                        <label for="name">Serie:</label>
                        <input type="number" class='form-control' style='width:80px' name='serie' id='serie' placeholder="7"  value=<?php if(isset($turma)) echo $turma['serie'] ?> >
                    </div>
                    <div class='col'>  
                        <br>                  
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">ENVIAR</button>               
                            <a href="..\index.html"><button type="button" class="btn btn-primary">MENU</button></a>
                          </div>
                         <div class="alert alert-danger d-none">
                            Preencha o campo <span id="campo-erro"></span>!
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <hr>

    <div class="row">
      <div class="col-xl">
        <div class="card" style="width: 80rem;">
          <div class="card-body">
            <form action="" method="get" id='fpesquisa'> <!-- esse formulário submte para essa mesma página para recarregar com o resultado da busca -->
             <div class='row'>
                <div class='col-6'><h2>Turmas Cadastrados</h2></div>
                  <div class='col-4'><input class='form-control' type="search" name='busca' id='busca'></div>
                    <div class='col'><button type="submit" class='btn btn-success' name='pesquisa' id='pesquisa'>Buscar</button></div>
                        </div>
                            </form>
                                <div class='row'>
            <!-- aqui montamos a tabela com os dados vindo do banco -->
            <table class='table table-striped table-hover'>
                <thead>
                    <tr>
                        <th>IdTurma</th>
                        <th>IdProfessor</th>
                        <th>Capacidade</th>
                        <th>Serie</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider" id='corpo'>
                    
                <?php             
                    try{
                        // cria  a conexão com o banco de dados 
                        $conexao = new PDO(MYSQL_DSN,DB_USER,DB_PASSWORD);
                        // pega o valor informado pelo usuário no formulário de pesquisa
                        $busca = isset($_GET['busca'])?$_GET['busca']:"";
                        // monta consulta
                        $query = 'SELECT * FROM tbturma';
                        if ($busca != ""){ // se o usuário informou uma pesquisa
                            $busca = '%'.$busca.'%'; // concatena o curiga * na pesquisa
                            $query .= ' WHERE nome like :busca' ; // acrescenta a clausula where
                        }
                        // prepara consulta
                        $stmt = $conexao->prepare($query);
                        // vincular variaveis com a consulta
                        if ($busca != "") // somente se o usuário informou uma busca
                            $stmt->bindValue(':busca',$busca);
                        // executa a consuta 
                        $stmt->execute();
                        // pega todos os registros retornados pelo banco
                        $turmas = $stmt->fetchAll();
                        foreach($turmas as $turma){ // percorre o array com todos os usuários imprimindo as linhas da tabela
                            $editar = '<a class="btn btn-warning" href=cadTurma.php?acao=editar&id='.$turma['id'].'>Alterar</a>';
                            $excluir = "<a class='btn btn-danger' href='#' onclick=excluir('acao.php?acao=excluir&id={$turma['id']}')>Excluir</a>";
                            echo '<tr><td>'.$turma['id'].'</td><td>'.$turma['idprofessor'].'</td><td>'.$turma['capacidade'].'</td><td>'.$turma['serie'].'</td><td>'.$editar.'</td><td>'.$excluir.'</td></tr>';
                        }
                    }catch(PDOException $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
                        print("Erro ao conectar com o banco de dados...<br>".$e->getMessage());
                        die();
                    }           
                ?>  
                </tbody>      
            </table>
        </div>
    </div>
    </div>
    </div>
</section>
    <script src="../../js/jquery-3.4.1.slim.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="script.js"></script>
</body>
</html>