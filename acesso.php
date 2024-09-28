<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="icons/icon_login.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Login</title>


    <style>
        body {
            background: url(./icons/background_coffe.jpg);
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
        }

        .box {
            position: relative;
            text-align: center;
        }

        main {
            position: relative;
            top: 100px;
        }

        h1 {
            font-size: 20pt;
            text-decoration: overline 2px;
            margin-top: 50px;
        }

        fieldset {
            background: white;
            box-shadow: 0px 0px 5px white;
            margin: auto;
            width: 350px;
            border-radius: 10px;
            border: none;
        }

        #cadastro {
            color: red;
            text-shadow: 0px 0px 1px white;
            text-decoration: none;
        }

        #cadastro:hover {
            text-decoration: underline;
        }

        .inputBox,
        .inputBtn {
            width: 230px;
            margin: auto;
            text-align: center;
            position: relative;
        }

        input {
            border-radius: 2px;
            padding: 5px 20px 5px 20px;
            outline: 0;
            border: 1;
            border-style: inset;
            border-width: 1px;
        }

        #submit {
            width: 80px;
            height: 40px;
            margin: auto;
            color: white;
            opacity: 80%;
            border-radius: 5px;
            background: rgb(36, 222, 36);
            border: none;
            margin: 20px;
        }

        #submit:hover {
            opacity: 100%;
            transform: scale(1.1);
            transition: 500ms linear;
        }

        label {
            margin: 0;
            font-size: 8pt;
            position: relative;
            right: 80px;
        }

        .inputBox i {
            position: absolute;
            cursor: pointer;
            top: 50%;
            right: 10%;
            color: #ccc;
        }
    </style>
</head>

<body>
    <main>
        <section>
            <form action="testeLogin.php" method="POST">
                <fieldset>
                    <div class="box">
                        <h1>Bem-vindo!</h1>
                        <p>Entre com seu login ou <a href="cadastro.php" id="cadastro">Cadastre-se</a></p>
                    </div>
                    <div class="inputBox">
                        <label for="email">E-mail</label>
                        <input type="text" name="email" id="email" placeholder="Digíte aqui o seu e-mail!" required>
                    </div><br>
                    <div class="inputBox">
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" id="senha" placeholder="Digíte aqui a sua senha!" required>
                        <i class="bi bi-eye-slash-fill" id="bnt_ver" onclick="verSenha()"></i>

                    </div>
                    <div class="inputBtn">
                        <button type="submit" name="submit" id="submit">Entrar</button>
                    </div>
                </fieldset>
            </form>
        </section>
    </main>
    <script src="main.js"></script>
</body>

</html>