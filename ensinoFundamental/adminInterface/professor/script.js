
        function excluir(url){
            if (confirm("Confirma a exclusão?"))
                window.location.href = url; //redireciona para o arquivo que irá efetuar a exclusão
        }

        window.onload = (function (){
            carregaDados();
            document.getElementById('fpesquisa').addEventListener('submit',function(ev){
                ev.preventDefault();
                carregaDados();
            });
            document.getElementById('busca').addEventListener('keyup',carregaDados);
        });

        function carregaDados(){
            busca = document.getElementById('busca').value;
            const xhttp = new XMLHttpRequest();  // cria o objeto que fará a conexão assíncrona
            xhttp.onload = function() {  // executa essa função quando receber resposta do servidor
                dados = JSON.parse(this.responseText); // os dados são convertidos para objeto javascript
                montaTabela(dados);
            }
            // configuração dos parâmetros da conexão assíncrona
            xhttp.open("GET", "pesquisa.php?busca=" + busca, true);  // arquivo que será acessado no servidor remoto  
            xhttp.send(); // parâmetros para a requisição

        }
        function montaTabela(dados){
            str = "";
            for(professor of dados){
                editar = '<a href=cadProfessor.php?acao=editar&id='+professor.id+'>Alterar</a>';
                excluir = "<a href='#' onclick=excluir('acao.php?acao=excluir&id="+professor.id+"}')>Excluir</a>";
                str += "<tr><td>"+professor.id+"</td><td>"+professor.nome+'</td><td>'+professor.dtnasc+'</td><td>'+professor.email+'</td><td>'+professor.senha+'</td><td>'+professor.telefone+'</td><td>'+professor.sexo+'</td><td>'+professor.disciplina+'</td><td>'+editar+'</td><td>'+excluir+'</td></tr>';
            }
            document.getElementById('corpo').innerHTML = str;
        }

        $('#form-contato').submit(function(){
            var nome = $('#nome');
            var dtnasc = $('#dtnasc');
            var email = $('#email');
            var senha = $('#senha');
            var telefone = $('#telefone');
            var disciplina = $('#disciplina');
            var erro = $('.alert');
            var campo = $('#campo-erro');
          
            // removendo o elemento da tela sempre que tentar submeter o formulario
            erro.addClass('d-none');
            $('.is-invalid').removeClass('is-invalid');
          
            // valida o campo nome
            if (nome.val() == '') {
              erro.removeClass('d-none');
              campo.html('nome'); // nome do campo que não foi preenchido!
              nome.focus();
              nome.addClass('is-invalid');
              return false;
            }
        
            // valida o campo dataNascimento
            if (dtnasc.val() == '') {
                erro.removeClass('d-none');
                campo.html('data nascimento'); // nome do campo que não foi preenchido!
                dtnasc.focus();
                dtnasc.addClass('is-invalid');
                return false;
            }
            
            // valida o campo e-mail
            if (email.val() == '') {
              erro.removeClass('d-none');
              campo.html('email'); // nome do campo que não foi preenchido!
              email.focus();
              email.addClass('is-invalid');
              return false;
            } 
        
            // valida o campo senha
            if (senha.val() == '') {
              erro.removeClass('d-none');
              campo.html('senha'); // nome do campo que não foi preenchido!
              senha.focus();
              senha.addClass('is-invalid');
              return false;
            }
            
            // valida o campo telefone
            if (telefone.val() == '') {
                erro.removeClass('d-none');
                campo.html('telefone'); // nome do campo que não foi preenchido!
                telefone.focus();
                telefone.addClass('is-invalid');
                return false;
              }  
        
              // valida o campo disciplina
            if (disciplina.val() == '') {
                erro.removeClass('d-none');
                campo.html('disciplina'); // nome do campo que não foi preenchido!
                disciplina.focus();
                disciplina.addClass('is-invalid');
                return false;
              }  
            
            // se chegar aqui pode enviar os dados!
            return true;
          });