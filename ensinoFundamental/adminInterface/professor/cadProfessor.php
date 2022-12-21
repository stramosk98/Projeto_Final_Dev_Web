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
        $query = 'SELECT * FROM tbprofessor WHERE id = :id' ;
        // preparar consulta
        $stmt = $conexao->prepare($query);
        // vincular variaveis com a consult
        $stmt->bindValue(':id',$id); 
        // executa a consulta
        $stmt->execute();
        // pega o resultado da consulta - nesse caso a consulta retorna somente um registro pq estamos buscando pelo ID que é único 
        // por isso basta um fetch
        $professor = $stmt->fetch(); 
         
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
 
    <title>Cadastro de Professor</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/estilo.css"> 
</head>
    <body class='container'>
    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-body">

    <h1>Cadastrar novo Professor</h1>
        <section id='cadProfessor' class='row'>
        <!-- Formulário para cadastro e edição de dados do professor, 
        caso seja aberto a página com a ação de editar o formulário trará os campos preenchidos com os dados do registro selecionado  -->
      
            <form id="form-contato" action="acao.php" method="post">  <!-- esse formulário envia os dados para o arquivo acao.php -->
                    <div class='col-1'>
                        <label for="id">Id:</label>
                        <input type="text" class='form-control' style='width:50px' readonly name="id" id="id">
                    </div>
                    <div class='col'>
                        <label for="nome">Nome:</label>
                        <input type="text" class='form-control' name='nome' id='nome' placeholder="Informe seu nome completo..." >
                    </div>
                    <div class='col'>
                        <label for="dtnasc">Nascimento:</label>
                        <input type="date" class='form-control' name='dtnasc' id='dtnasc' placeholder="Informe sua data de nascimento..."  >
                    </div>
                    <div class='col'>
                        <label for="email">E-mail:</label>
                        <input type="email" class='form-control' name='email' id='email' placeholder="professor@mail.com..." >
                    </div>
                    <div class='col'>
                        <label for="senha">Senha:</label>
                        <input type="password" class='form-control' name='senha' id='senha' placeholder="Informe uma senha..." >
                    </div>
                    <div class='col'>
                        <label for="telefone">Telefone:</label>
                        <input type="tel" class='form-control' name='telefone' id='telefone' placeholder="(47)0000-0000" >
                    </div>
                    <div class='col'>
                    <label for="sexo">Sexo:</label>
                    <select name="sexo">
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                   </select>
                   </div>
                    <div class='col'>
                        <label for="disciplina">Disciplina:</label>
                        <input type="text" class='form-control' name='disciplina' id='disciplina' placeholder="Informe uma Disciplina..." >
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
                <div class='col-6'><h2>Professores Cadastrados</h2></div>
                  <div class='col-4'><input class='form-control' type="search" name='busca' id='busca'></div>
                    <div class='col'><button type="submit" class='btn btn-success' name='pesquisa' id='pesquisa'>Buscar</button></div>
                        </div>
                            </form>
                                <div class='row'>
            <!-- aqui montamos a tabela com os dados vindo do banco -->
            <table class='table table-striped table-hover'>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Nascimento</th>
                        <th>E-mail</th>
                        <th>Senha</th>
                        <th>Telefone</th>
                        <th>Sexo</th>
                        <th>Disciplina</th>
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
                        $query = 'SELECT * FROM tbprofessor';
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
                        $professors = $stmt->fetchAll();
                        foreach($professors as $professor){ // percorre o array com todos os usuários imprimindo as linhas da tabela
                            $editar = '<a class="btn btn-warning" href=cadProfessor.php?acao=editar&id='.$professor['id'].'>Alterar</a>';
                            $excluir = "<a class='btn btn-danger' href='#' onclick=excluir('acao.php?acao=excluir&id={$professor['id']}')>Excluir</a>";
                            echo '<tr><td>'.$professor['id'].'</td><td>'.$professor['nome'].'</td><td>'.$professor['dtnasc'].'</td><td>'.$professor['email'].'</td><td>'.$professor['senha'].'</td><td>'.$professor['telefone'].'</td><td>'.$professor['sexo'].'</td><td>'.$professor['disciplina'].'</td><td>'.$editar.'</td><td>'.$excluir.'</td></tr>';
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