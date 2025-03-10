<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>
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
            text-decoration: none;
        }

        /* Ícones da Navbar */
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

        /* Botão de alternância de tema */
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #222;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .theme-toggle.light-mode {
            background: #fff;
            color: #222;
        }

        /* Layout do Dashboard */
        .container {
            padding: 40px 20px 80px; /* Espaço para a navbar */
            text-align: center;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            margin: 10px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Tema escuro para os cards */
        body.dark .card {
            background: rgba(30, 30, 30, 0.9);
        }

    </style>
</head>
<body>

    <!-- Botão para alternar entre claro e escuro -->
    <button class="theme-toggle" onclick="toggleTheme()">🌙</button>

    <!-- Conteúdo do Dashboard -->
    <div class="container">
        <h2>Bem-vindo, <?php echo $_SESSION['usuario_nome']; ?>!</h2>
        <p>Resumo do dia</p>

        <div class="card">
            <h3>📊 Interações</h3>
            <p>5 interações positivas</p>
        </div>

        <div class="card">
            <h3>✅ Comportamento</h3>
            <p class="green">Estável</p>
        </div>

        <div class="card">
            <h3>📈 Progresso Geral</h3>
            <p>Gráfico aqui</p>
        </div>

        <div class="card">
            <h3>🎯 Pontos Fortes</h3>
            <p>Melhora na comunicação</p>
        </div>
    </div>

    <!-- Barra de Navegação Inferior -->
    <div class="bottom-nav">
        <a href="#" class="nav-item">
            <i>🏠</i>
            <span>Início</span>
        </a>
        <a href="pacientes.php" class="nav-item">
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
        // Alternância de tema claro/escuro com localStorage
        function toggleTheme() {
            let body = document.body;
            let button = document.querySelector(".theme-toggle");

            if (body.classList.contains("dark")) {
                body.classList.remove("dark");
                body.classList.add("light");
                button.innerHTML = "🌙";
                button.classList.remove("light-mode");
                localStorage.setItem("theme", "light");
            } else {
                body.classList.remove("light");
                body.classList.add("dark");
                button.innerHTML = "☀️";
                button.classList.add("light-mode");
                localStorage.setItem("theme", "dark");
            }
        }

        // Aplicar o tema salvo no localStorage
        (function () {
            let savedTheme = localStorage.getItem("theme") || "light";
            document.body.classList.add(savedTheme);
            let button = document.querySelector(".theme-toggle");

            if (savedTheme === "dark") {
                button.innerHTML = "☀️";
                button.classList.add("light-mode");
            }
        })();
    </script>

</body>
</html>
