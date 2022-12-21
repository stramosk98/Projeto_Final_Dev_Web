<?php

include_once "../config/conf.inc.php";   

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
        $query = 'SELECT * FROM tbaluno WHERE id = :id' ;
        // preparar consulta
        $stmt = $conexao->prepare($query);
        // vincular variaveis com a consult
        $stmt->bindValue(':id',$id); 
        // executa a consulta
        $stmt->execute();
        // pega o resultado da consulta - nesse caso a consulta retorna somente um registro pq estamos buscando pelo ID que é único 
        // por isso basta um fetch
        $aluno = $stmt->fetch(); 
         
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

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilo.css"> 

    </head>
    <body class='container'>
    <div class="row">
      <div class="col">
        <div class="card" style="width: 60rem;">
          <div class="card-body">
            <form action="" method="get" id='fpesquisa'> <!-- esse formulário submte para essa mesma página para recarregar com o resultado da busca -->
             <div class='row'>
                <div class='col-5'><h2>Alunos</h2></div>
                  <div class='col-4'><input class='form-control' type="search" name='busca' id='busca'></div>
                    <div class='col'><button type="submit" class='btn btn-success' name='pesquisa' id='pesquisa'>Buscar</button>
                    <a href="indexAluno.html"><button type="button" class="btn btn-primary">Menu</button></a>
                        </div>
                          </div>
                            </form>
                                <div class='row'>
            <!-- aqui montamos a tabela com os dados vindo do banco -->
            <table class='table table-striped table-hover'>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Turma</th>
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
                        $query = 'SELECT * FROM tbaluno';
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
                        $alunos = $stmt->fetchAll();
                        foreach($alunos as $aluno){ // percorre o array com todos os usuários imprimindo as linhas da tabela
                            echo '<tr><td>'.$aluno['id'].'</td><td>'.$aluno['nome'].'</td><td>'.$aluno['email'].'</td><td>'.$aluno['telefone'].'</td><td>'.$aluno['turma'].'</td><td>';
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

</body>
</html>