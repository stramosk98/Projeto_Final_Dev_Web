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
        campo.html('dtnasc'); // nome do campo que não foi preenchido!
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
