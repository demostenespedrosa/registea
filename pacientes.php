<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'conexao.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

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
            text-decoration: none;
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

    </style>
</head>
<body>

    <div class="container">
        <h2>👥 Pacientes</h2>

        <!-- Barra de busca -->
        <input type="text" class="search-box" placeholder="🔍 Buscar paciente..." onkeyup="filterPatients()">

        <!-- Lista de Pacientes -->
        <div id="patient-list">
            <?php if ($pacientes): ?>
                <?php foreach ($pacientes as $paciente): ?>
                    <div class="patient-card">
                        <span><?php echo htmlspecialchars($paciente['nome']); ?></span>
                        <a href="perfil_paciente.php?id=<?php echo $paciente['id']; ?>" class="action-btn">Ver</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum paciente encontrado.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Botão de adicionar paciente -->
    <button class="add-btn" onclick="openModal()">➕</button>

    <!-- Modal de Adicionar Paciente -->
<div id="add-patient-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Adicionar Paciente</h2>
        
        <form action="adicionar_paciente.php" method="POST">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" required>

            <label for="data_nascimento">Data de Nascimento</label>
            <input type="date" id="data_nascimento" name="data_nascimento" required>

            <label for="nivel_suporte">Nível de Suporte</label>
            <select id="nivel_suporte" name="nivel_suporte" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>

            <label for="escola">Escola</label>
            <input type="text" id="escola" name="escola" required>

            <label for="ano_escolar">Ano Escolar</label>
            <input type="text" id="ano_escolar" name="ano_escolar" required>

            <button type="submit" class="action-btn">Salvar</button>
        </form>
    </div>
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

    <script>
        // Função de busca de pacientes
        function filterPatients() {
            let input = document.querySelector(".search-box").value.toLowerCase();
            let patients = document.querySelectorAll(".patient-card");

            patients.forEach(patient => {
                let name = patient.innerText.toLowerCase();
                patient.style.display = name.includes(input) ? "flex" : "none";
            });
        }
    </script>
    <script>
        // Abrir o modal
        function openModal() {
            document.getElementById('add-patient-modal').style.display = 'flex';
        }

        // Fechar o modal
        function closeModal() {
            document.getElementById('add-patient-modal').style.display = 'none';
        }
    </script>
</body>
</html>
