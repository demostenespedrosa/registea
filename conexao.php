<?php
$host = 'localhost'; // Endereço do banco de dados
$dbname = 'registea'; // Nome do banco de dados
$username = 'root'; // Usuário do banco de dados
$password = ''; // Senha do banco de dados

try {
    // Estabelecendo a conexão com o banco de dados usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Definindo modo de erro
} catch (PDOException $e) {
    // Caso ocorra um erro na conexão, ele será exibido
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>

