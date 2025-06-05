<?php
// conexao.php
$host = 'localhost';     // servidor do banco, geralmente localhost
$db   = 'loja';          // nome do banco
$user = 'root';          // seu usuário do banco
$pass = '';              // senha do banco

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // Ativa exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco: " . $e->getMessage());
}
?>
