function verSenha(){
    var verSenha = window.document.getElementById('bnt_ver')
    var inputSenha = window.document.getElementById('senha')
    if(inputSenha.type === 'password'){
        inputSenha.setAttribute('type', 'text')
        verSenha.classList.replace('bi-eye-slash-fill', 'bi-eye-fill')
    } else{
        inputSenha.setAttribute('type', 'password')
        verSenha.classList.replace('bi-eye-fill', 'bi-eye-slash-fill')
    }
}


function validarCPF(cpf) {
    // Remove caracteres não numéricos
    cpf = cpf.replace(/[^\d]+/g, '');

    // Verifica se o CPF tem 11 dígitos
    if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) {
        return false;
    }

    let soma = 0;
    let resto;

    // Validação do primeiro dígito
    for (let i = 0; i < 9; i++) {
        soma += parseInt(cpf.charAt(i)) * (10 - i);
    }
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.charAt(9))) return false;

    soma = 0;
    // Validação do segundo dígito
    for (let i = 0; i < 10; i++) {
        soma += parseInt(cpf.charAt(i)) * (11 - i);
    }
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.charAt(10))) return false;

    return true;
}

function cadastrar() {
    document.getElementById('submit').addEventListener('submit', function(event) {
        event.preventDefault();
        
        var inputSenha = document.getElementById('senha_cad').value;
        var inputSenhaConf = document.getElementById('senha_cad_conf').value;
        var inputCPF = document.getElementById('cpf_cad').value;
        
        // Verifica se as senhas são iguais
        if (inputSenha !== inputSenhaConf) {
            document.getElementById('error-message').textContent = 'As senhas não coincidem!';
        } else if (!validarCPF(inputCPF)) {
            // Verifica se o CPF é válido
            document.getElementById('error-message').textContent = 'CPF inválido! Deve ter 11 dígitos e ser válido.';
        } else {
            document.getElementById('error-message').textContent = '';
            // Manda o formulário
            this.submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    cadastrar();
});





