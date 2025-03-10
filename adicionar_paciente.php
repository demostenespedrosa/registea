<?php
// adicionar_paciente.php
require 'conexao.php';
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obter os dados do formulário
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
    $nivel_suporte = $_POST['nivel_suporte'];
    $escola = $_POST['escola'];
    $ano_escolar = $_POST['ano_escolar'];
    $usuario_id = $_SESSION['usuario_id']; // ID do usuário logado

    // Preparar a consulta para inserir o paciente
    $sql = "INSERT INTO pacientes (usuario_id, nome, data_nascimento, nivel_suporte, escola, ano_escolar) 
            VALUES (:usuario_id, :nome, :data_nascimento, :nivel_suporte, :escola, :ano_escolar)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
    $stmt->bindParam(':nivel_suporte', $nivel_suporte, PDO::PARAM_STR);
    $stmt->bindParam(':escola', $escola, PDO::PARAM_STR);
    $stmt->bindParam(':ano_escolar', $ano_escolar, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Redirecionar para a página de pacientes após inserir
        header("Location: pacientes.php");
        exit();
    } else {
        echo "Erro ao adicionar paciente.";
    }
}
?>
