<?php
// editar_paciente.php
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

// Atualizar dados quando o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
    $nivel_suporte = $_POST['nivel_suporte'];
    $escola = $_POST['escola'];
    $ano_escolar = $_POST['ano_escolar'];

    // Preparar a consulta para atualizar o paciente
    $sql = "UPDATE pacientes SET nome = :nome, data_nascimento = :data_nascimento, nivel_suporte = :nivel_suporte, escola = :escola, ano_escolar = :ano_escolar WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
    $stmt->bindParam(':nivel_suporte', $nivel_suporte, PDO::PARAM_STR);
    $stmt->bindParam(':escola', $escola, PDO::PARAM_STR);
    $stmt->bindParam(':ano_escolar', $ano_escolar, PDO::PARAM_STR);
    $stmt->bindParam(':id', $paciente_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirecionar para a página de perfil após a edição
        header("Location: perfil_paciente.php?id=$paciente_id");
        exit();
    } else {
        echo "Erro ao atualizar os dados do paciente.";
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<div class="container">
    <h1>Editar Paciente</h1>
    <div class="profile-card">
        <form action="editar_paciente.php?id=<?php echo $paciente['id']; ?>" method="POST">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($paciente['nome']); ?>" required>

            <label for="data_nascimento">Data de Nascimento</label>
            <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($paciente['data_nascimento']); ?>" required>

            <label for="nivel_suporte">Nível de Suporte</label>
            <select id="nivel_suporte" name="nivel_suporte" required>
                <option value="1" <?php if ($paciente['nivel_suporte'] == '1') echo 'selected'; ?>>1</option>
                <option value="2" <?php if ($paciente['nivel_suporte'] == '2') echo 'selected'; ?>>2</option>
                <option value="3" <?php if ($paciente['nivel_suporte'] == '3') echo 'selected'; ?>>3</option>
            </select>

            <label for="escola">Escola</label>
            <input type="text" id="escola" name="escola" value="<?php echo htmlspecialchars($paciente['escola']); ?>" required>

            <label for="ano_escolar">Ano Escolar</label>
            <input type="text" id="ano_escolar" name="ano_escolar" value="<?php echo htmlspecialchars($paciente['ano_escolar']); ?>" required>

            <button type="submit" class="action-btn">Atualizar</button>
        </form>
    </div>
</div>

</body>
</html>
