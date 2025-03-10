<?php
require 'conexao.php';
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar se o paciente a ser excluído foi enviado via GET
if (isset($_GET['id'])) {
    $id_paciente = $_GET['id'];

    // Garantir que o paciente pertence ao usuário logado
    $usuario_id = $_SESSION['usuario_id'];
    $sql = "SELECT * FROM pacientes WHERE id = :id AND usuario_id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_paciente, PDO::PARAM_INT);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $paciente = $stmt->fetch();

    if ($paciente) {
        // Excluir o paciente do banco de dados
        $deleteSql = "DELETE FROM pacientes WHERE id = :id";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->bindParam(':id', $id_paciente, PDO::PARAM_INT);
        if ($deleteStmt->execute()) {
            // Redirecionar após a exclusão
            header("Location: pacientes.php");
            exit();
        } else {
            echo "Erro ao excluir o paciente. Tente novamente.";
        }
    } else {
        echo "Paciente não encontrado ou não autorizado a excluir.";
    }
} else {
    // Se a requisição não foi feita corretamente
    echo "Requisição inválida.";
}
?>
