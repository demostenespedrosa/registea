<?php
// perfil_paciente.php
require 'conexao.php';
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar se o paciente foi passado via URL
if (isset($_GET['id'])) {
    $paciente_id = $_GET['id'];

    // Obter os dados do paciente
    $sql = "SELECT * FROM pacientes WHERE id = :id AND usuario_id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $paciente_id, PDO::PARAM_INT);
    $stmt->bindParam(':usuario_id', $_SESSION['usuario_id'], PDO::PARAM_INT);
    $stmt->execute();

    $paciente = $stmt->fetch();

    if (!$paciente) {
        // Paciente não encontrado ou não pertence ao usuário
        header("Location: pacientes.php");
        exit();
    }
} else {
    header("Location: pacientes.php");
    exit();
}
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Buscar pacientes do usuário logado
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM pacientes WHERE usuario_id = :usuario_id";
$stmt = $pdo->prepare($sql);

$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

try {
    $stmt->execute();
    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro na consulta: " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes</title>
    <style>
        /* Reset e Fonte */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            transition: background 0.3s, color 0.3s;
        }

        /* Tema Claro */
        body.light {
            background: #f8f8f8;
            color: #111;
        }

        /* Tema Escuro */
        body.dark {
            background: #111;
            color: #f8f8f8;
        }

        /* Layout */
        .container {
            padding: 40px 20px 80px; /* Espaço para navbar */
            text-align: center;
        }

        /* Barra de busca */
        .search-box {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            outline: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Lista de Pacientes */
        .patient-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 15px;
            margin: 10px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Botão de ação */
        .action-btn {
            background: #007AFF;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
        }

        .action-btn:hover {
            background: #005ecb;
        }

        /* Tema escuro */
        body.dark .patient-card {
            background: rgba(30, 30, 30, 0.9);
        }

        /* Botão de adicionar paciente */
        .add-btn {
            position: fixed;
            bottom: 80px;
            right: 20px;
            background: yellow;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 50%;
            font-size: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        /* Navbar inferior estilo iPhone */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 10px 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 12px 12px 0 0;
        }

        .nav-item {
            text-align: center;
            font-size: 14px;
            color: #555;
            text-decoration: none;
        }

        .nav-item i {
            font-size: 24px;
            display: block;
            margin-bottom: 2px;
        }
        /* Modal */
        .modal {
            display: none; /* Inicialmente escondido */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        /* Conteúdo do modal */
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Fechar o modal */
        .close-btn {
            font-size: 24px;
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 20px;
            cursor: pointer;
        }

        /* Estilo dos campos de entrada */
        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        /* Botão de ação */
        button[type="submit"] {
            background: #007AFF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        button[type="submit"]:hover {
            background: #005ecb;
        }
        .profile-card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: left;
            margin-top: 20px;
        }

        .profile-card h2 {
            text-align: center;
            color: #007AFF;
        }

        .profile-card p {
            font-size: 16px;
            margin: 10px 0;
        }

        .edit-btn {
            display: inline-block;
            background-color: #007AFF;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }

        .edit-btn:hover {
            background-color: #005ecb;
        }
        /* Link de exclusão */
        .delete-btn {
            background-color: red;
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

    </style>
</head>
<body>

    <div class="container">
    <h2>Perfil de Paciente</h2>
    <div class="profile-card">
        <h2><?php echo htmlspecialchars($paciente['nome']); ?></h2>
        <?php
        // Criar o objeto DateTime a partir da data de nascimento
        $data_nascimento = new DateTime($paciente['data_nascimento']);
        // Formatar a data para o formato "d/m/Y"
        $data_formatada = $data_nascimento->format('d/m/Y');
    ?>
        <p><strong>Data de Nascimento:</strong> <?php echo $data_formatada; ?></p>
        <p><strong>Nível de Suporte:</strong> <?php echo htmlspecialchars($paciente['nivel_suporte']); ?></p>
        <p><strong>Escola:</strong> <?php echo htmlspecialchars($paciente['escola']); ?></p>
        <p><strong>Ano Escolar:</strong> <?php echo htmlspecialchars($paciente['ano_escolar']); ?></p>

        <!-- Botão para editar o perfil -->
        <a href="editar_paciente.php?id=<?php echo $paciente['id']; ?>" class="edit-btn">Editar Perfil</a>
        <a href="excluir_paciente.php?id=<?php echo $paciente['id']; ?>" class="delete-btn" onclick="return confirm('Tem certeza que deseja excluir este perfil?')">Excluir Perfil</a>
    </div>

    <!-- Barra de Navegação Inferior -->
    <div class="bottom-nav">
        <a href="index.php" class="nav-item">
            <i>🏠</i>
            <span>Início</span>
        </a>
        <a href="#" class="nav-item">
            <i>👥</i>
            <span>Pacientes</span>
        </a>
        <a href="#" class="nav-item">
            <i>📄</i>
            <span>Relatórios</span>
        </a>
        <a href="#" class="nav-item">
            <i>⚙️</i>
            <span>Config.</span>
        </a>
        <a href="logout.php" class="nav-item">
            <i>🚪</i>
            <span>Sair</span>
        </a>
    </div>
</body>
</html>
