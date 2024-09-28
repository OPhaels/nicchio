<?php
session_start();
//print_r($_REQUEST) 
if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    //acessa
    include_once('config.php');
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    //print_r('E-mail: ' . $email);
    //print_r('<br>');
    //print_r('Senha: '. $senha);

    $pesquisa = "SELECT * FROM cadastros WHERE email = '$email' and senha = '$senha';";
    $resultado = $conexao->query($pesquisa);

    if (mysqli_num_rows($resultado) < 1) {
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: acesso.php');
    } else {
        $_SESSION['email'] = $email;
        $_SESSION['senha'] = $senha;
        header('Location: index.php');
    }
} else {
    //Não acessa
    header('Location: acesso.php');
}
