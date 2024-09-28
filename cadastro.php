<!--<?php
    include_once('config.php');

    function validarCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) != 11 || preg_match('/^(\d)\1+$/', $cpf)) {
            return false;
        }
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $resto = $resto < 2 ? 0 : 11 - $resto;
        if ($resto != $cpf[9]) {
            return false;
        }
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $resto = $resto < 2 ? 0 : 11 - $resto;
        if ($resto != $cpf[10]) {
            return false;
        }
        return true;
    }

    $msg = '';
    $msg_class = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome_cad'];
        $senha = $_POST['senha_cad'];
        $email = $_POST['email_cad'];
        $cpf = $_POST['cpf_cad'];

        if (!validarCPF($cpf)) {
            $msg = "CPF inválido.";
            $msg_class = 'show-msg error-msg';
        } elseif ($senha !== $_POST['senha_cad_conf']) {
            $msg = "As senhas não coincidem.";
            $msg_class = 'show-msg error-msg';
        } else {
            $stmt = $conexao->prepare("SELECT id FROM cadastros WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $msg = "E-mail já cadastrado.";
                $msg_class = 'show-msg error-msg';
            } else {
                $hashed_password = password_hash($senha, PASSWORD_BCRYPT);
                $stmt = $conexao->prepare("INSERT INTO cadastros (nome, senha, email, cpf) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $nome, $hashed_password, $email, $cpf);

                if ($stmt->execute()) {
                    $msg = 'Cadastro realizado com sucesso!';
                    $msg_class = 'show-msg success-msg';
                } else {
                    $msg = "Erro: " . $stmt->error;
                    $msg_class = 'show-msg error-msg';
                }
            }
            $stmt->close();
        }
        $conexao->close();
    }
    ?>
-->
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="icons/icon_login.png" type="image/x-icon">
    <title>Cadastre-se</title>
    <style>
        body {
            background: url(./icons/background_coffe.jpg);
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
        }


        fieldset {
            box-shadow: 0px 0px 5px white;
            width: 350px;
            padding: 5px;
            text-align: center;
            font-size: 10pt;
            margin: auto;
            border: none;
            border-radius: 10px;
            text-shadow: 1px 1px black;
            background: white;
        }

        input {
            margin: 5px;
            padding: 10px 10px;
            font-size: 10pt;
            border-radius: 2px;
            text-align: center;
            width: 150px;
            border-style: solid;
            border-color: black;
            border-width: 1px;
        }

        h1 {
            text-align: center;
            margin: 10px;
            font-size: 18pt;
            text-shadow: 1px 1px black;
            margin: 20px;
            color: white;
        }

        #submit {
            padding: 10px;
            border: none;
            color: white;
            background: rgb(23, 240, 23);
            text-shadow: 1px 1px 1px black;
            opacity: 80%;
            border-radius: 5px;
        }

        #login_btn {
            text-align: left;
            color: white;
            text-shadow: 1px 1px black;
            text-decoration: none;
            background: black;
            border-radius: 10px;
            padding: 5px;
            opacity: 50%;
            position: relative;
            top: 20px;
            left: 20px;
        }

        #login_btn:hover{
            opacity: 100%;
            background: white;
            color: black;
            text-shadow: none;
        }
        #submit:hover {
            transform: scale(1.1);
            transition: 500ms linear;
            opacity: 100%;
        }

        #msg {
            display: block;
            text-shadow: none;
            margin: 20px auto;
            padding: 5px;
            border-radius: 5px;
            text-align: center;
            width: 300px;
        }

        .show-msg {
            display: block;
            text-shadow: none;
        }

        .success-msg {
            color: green;
            background: #e0ffe0;
            text-shadow: none;
        }

        .error-msg {
            color: red;
            background: #ffe0e0;
            text-shadow: none;
        }

        input {
            margin-top: 25px;
        }

        .inputBox2 {
            margin: 25px;
        }
    </style>
</head>

<body>
    <nav>
        <a id="login_btn" href="acesso.php">Voltar para sua página de login</a>
    </nav>
    <header>
        <h1>CRIE SEU CADASTRO!</h1>
        <div id="msg" class="<?php echo $msg_class; ?>">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    </header>

    <div class="box">
        <form action="cadastro.php" method="POST">

            <fieldset>
                <tr>
                    <td><input type="text" name="nome_cad" id="nome_cad" placeholder="Nome" required></td>
                    <td><input type="text" name="cpf_cad" id="cpf_cad" placeholder="CPF" required></td>
                    <td><input type="email" name="email_cad" id="email_cad" placeholder="E-mail" required></td>
                    <td><input type="password" name="senha_cad" id="senha_cad" placeholder="Senha" required></td>
                    <td><input type="password" name="senha_cad_conf" id="senha_cad_conf" placeholder="Confirmar Senha" required></td>
                </tr>

                <div class="inputBox2">
                    <button type="submit" id="submit" name="submit">Cadastrar</button>
                </div>
            </fieldset>
        </form>
    </div>
</body>

</html>