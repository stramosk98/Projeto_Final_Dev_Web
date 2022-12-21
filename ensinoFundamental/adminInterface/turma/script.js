
        // floreio -- para o usuário confirmar a exclusão
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
            for(turma of dados){
                editar = '<a href=cadTurma.php?acao=editar&id='+turma.id+'>Alt</a>';
                excluir = "<a href='#' onclick=excluir('acao.php?acao=excluir&id="+turma.id+"}')>Excluir</a>";
                str += "<tr><td>"+turma.id+"</td><td>"+turma.idprofessor+"</td><td>"+turma.capacidade+'</td><td>'+turma.serie+'</td><td>'+editar+'</td><td>'+excluir+'</td></tr>';
            }
            document.getElementById('corpo').innerHTML = str;
        }

        $('#form-contato').submit(function(){
            var idprofessor = $('#idprofessor');
            var capacidade = $('#capacidade');
            var serie = $('#serie');
            var erro = $('.alert');
            var campo = $('#campo-erro');
          
            // removendo o elemento da tela sempre que tentar submeter o formulario
            erro.addClass('d-none');
            $('.is-invalid').removeClass('is-invalid');
          
            // valida o campo idProfessor
            if (idprofessor.val() == '' || idprofessor.val() <= 0) {
              erro.removeClass('d-none');
              campo.html('idprofessor'); // nome do campo que não foi preenchido!
              idprofessor.focus();
              idprofessor.addClass('is-invalid');
              return false;
            }
        
            // valida o campo capacidade
            if (capacidade.val() == '' || capacidade.val() <= 0) {
                erro.removeClass('d-none');
                campo.html('capacidade'); // nome do campo que não foi preenchido!
                capacidade.focus();
                capacidade.addClass('is-invalid');
                return false;
            }
            
            // valida o campo serie
            if (serie.val() == '' || serie.val() <= 0) {
              erro.removeClass('d-none');
              campo.html('serie'); // nome do campo que não foi preenchido!
              serie.focus();
              serie.addClass('is-invalid');
              return false;
            } 
        
            // se chegar aqui pode enviar os dados!
            return true;
          });
