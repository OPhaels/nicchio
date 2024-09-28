<?php
session_start();
include_once('config.php');

// Desabilitar cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Inatividade de 10 minutos
$inatividadeLimite = 10 * 60; // 10 minutos em segundos
if (isset($_SESSION['ultimoAtivo']) && (time() - $_SESSION['ultimoAtivo'] > $inatividadeLimite)) {
    session_unset();
    session_destroy();
    header('Location: acesso.php');
    exit();
}
$_SESSION['ultimoAtivo'] = time(); // Atualiza o último tempo ativo

// Verifica se o usuário está logado
if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
    header('Location: acesso.php');
    exit();
}

$logado = $_SESSION['email'];

// Verifica se a conexão com o banco de dados foi estabelecida
if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $campo = $_POST['campo'];
        $novoValor = $_POST['novoValor'];
        $sacos = $_POST['sacos'];
        $tabela = $_POST['tabela'];

        // Atualiza o valor no banco de dados
        $updateSql = "UPDATE $tabela SET $campo = '$novoValor' WHERE SACOS = '$sacos'";
        
        if ($conexao->query($updateSql) === TRUE) {
            echo "success";
        } else {
            echo "Error: " . $conexao->error; // Mensagem de erro
        }
        exit;
    } elseif (isset($_POST['buscar'])) {
        $saida = $_POST['saida'];
        $destino = $_POST['destino'];
        $sacos = $_POST['sacos'];

        // Determina a tabela correta com base na saída e destino
        $tabela = '';
        switch ("$saida|$destino") {
            case "Colatina|Rio de Janeiro":
                $tabela = 'colrio';
                break;
            case "Colatina|Vitória":
                $tabela = 'colvit';
                break;
            case "Minas Gerais|Rio de Janeiro":
                $tabela = 'minrio';
                break;
            case "Minas Gerais|Vitória":
                $tabela = 'minvit';
                break;
        }

        // Consulta SQL para obter todos os valores
        $sql = "SELECT THC, CERTIFICADO, FUMIGACAO, CCC, CECAFE, OVACAO, DESPACHO, 
                       TAXA_PORTO_ISPS, TAXA_BL, LACRE, RETIRADA, TAXA_SCANNER, 
                       TAXA_ELF, FORRACAO, PESAGEM 
                FROM $tabela 
                WHERE SACOS = '$sacos'";

        $result = $conexao->query($sql);
        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();
            // Divide os valores pela quantidade de sacos
            foreach ($data as $key => $value) {
                $data[$key] = $value; // Manter o valor original para cálculo posterior
            }
            $response = ['data' => $data, 'tabela' => $tabela, 'sacos' => $sacos];
            echo json_encode($response);
            exit;
        } else {
            echo json_encode(['error' => 'Tabela não encontrada para o valor inserido (50kg).']);
            exit;
        }
    }
}
print_r($_SESSION['email'])
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Consulta</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: url('./icons/background_coffe.jpg');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            color: #fff;
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            transition: background 0.5s ease;
        }

        nav {
            width: 100%;
            background-color: black;
            padding: 15px;
            display: flex;
            justify-content: space-around;
            position: fixed;
            top: 0;
            z-index: 1;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background 0.3s, transform 0.3s;
        }

        nav a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .navbar-item {
            font-size: 18px;
        }

        .logout {
            background: red;
            border-radius: 10px;
            padding: 10px 20px;
            transition: background 0.3s;
        }

        .logout:hover {
            background: darkred;
        }

        .formulario {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 80px; /* para compensar a navbar fixa */
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .formulario select,
        .formulario button {
            margin: 5px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            background: rgba(255, 255, 255);
            color: black;
            transition: background 0.3s, transform 0.2s;
        }

        .formulario select:hover,
        .formulario button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        #alerta {
            margin-top: 20px;
            padding: 10px;
            background-color: green;
            color: white;
            border-radius: 5px;
            display: none; /* Oculta por padrão */
            transition: opacity 0.5s ease;
        }

        .tabela {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            overflow-y: auto;
            animation: slideIn 0.5s ease-in;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .tabela th,
        .tabela td {
            border: 1px solid #fff;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            transition: background 0.3s;
        }

        .tabela th {
            background-color: rgba(255, 255, 255);
            color: black;
        }

        .tabela td {
            background: rgba(255, 255, 255);
            color: black;
        }

        .tabela tr:hover td {
            background: rgba(255, 255, 255, 0.4);
        }

        input[type="number"] {
            width: 70px;
            transition: border 0.3s;
        }

        input[type="number"]:focus {
            border: 2px solid #4B5D67;
            outline: none;
        }

        .icon-button {
            background: transparent;
            border: none;
            cursor: pointer;
        }

        .icon-button img {
            transition: filter 0.3s;
        }

        .icon-button:hover img {
            filter: brightness(0.8); /* Altera a cor ao passar o mouse */
        }
    </style>
</head>
<body>

    <nav>
        <a href="acesso.php" class="navbar-item logout">Sair</a>
    </nav>

    <div class="formulario">
        <form id="buscar-form">
            <select name="saida" id="saida" required>
                <option value="" disabled selected>Saída</option>
                <option value="Minas Gerais">Minas Gerais</option>
                <option value="Colatina">Colatina</option>
            </select>
            <select name="destino" id="destino" required>
                <option value="" disabled selected>Destino</option>
                <option value="Vitória">Vitória</option>
                <option value="Rio de Janeiro">Rio de Janeiro</option>
            </select>
            <select name="sacos" id="sacos" required>
                <option value="" disabled selected>Quantidade de Sacos</option>
                <option value="320">320</option>
                <option value="360">360</option>
                <option value="440">440</option>
            </select>
            <button type="submit">Buscar</button>
        </form>
        <div id="alerta"></div>
    </div>

    <table class="tabela" id="tabela" style="display: none;">
        <thead>
            <tr>
                <th>Campo</th>
                <th>Valor Original</th>
                <th>Preço por Saco</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody id="tabelaCorpo">
        </tbody>
    </table>

    <script>
        document.getElementById('buscar-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const saida = document.getElementById('saida').value;
            const destino = document.getElementById('destino').value;
            const sacos = document.getElementById('sacos').value;

            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    buscar: true,
                    saida,
                    destino,
                    sacos
                })
            })
            .then(response => response.json())
            .then(data => {
                const alerta = document.getElementById('alerta');
                if (data.error) {
                    alerta.innerText = data.error;
                    alerta.style.display = 'block';
                    setTimeout(() => alerta.style.display = 'none', 3000);
                    document.getElementById('tabela').style.display = 'none';
                } else {
                    alerta.innerText = '';
                    alerta.style.display = 'none';
                    document.getElementById('tabela').style.display = 'table';
                    const tabelaCorpo = document.getElementById('tabelaCorpo');
                    tabelaCorpo.innerHTML = '';

                    Object.entries(data.data).forEach(([key, value]) => {
                        tabelaCorpo.innerHTML += `
                        <tr>
                            <td>${key}</td>
                            <td><input type="number" id="${key}" value="${value}" step="0.01" /></td> 
                            <td id="resultado-${key}">${(value / sacos).toFixed(2)}</td> 
                            <td>
                                <button class="icon-button" onclick="atualizar('${key}', ${sacos}, '${data.tabela}')">
                                    <i class="fas fa-edit" style="font-size: 20px; color: white;"></i>
                                </button>
                            </td>
                        </tr>`;
                    });
                }
            });
        });

        function atualizar(campo, sacos, tabela) {
            const novoValor = document.getElementById(campo).value;

            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    update: true,
                    campo,
                    novoValor,
                    sacos,
                    tabela
                })
            })
            .then(response => response.text())
            .then(data => {
                const alerta = document.getElementById('alerta');
                if (data === 'success') {
                    document.getElementById(`resultado-${campo}`).innerText = (novoValor / sacos).toFixed(2);
                    alerta.innerText = 'Alteração realizada com sucesso!';
                    alerta.style.display = 'block';
                    setTimeout(() => alerta.style.display = 'none', 3000);
                } else {
                    alert('Erro ao atualizar.');
                }
            });
        }
    </script>

</body>
</html>
