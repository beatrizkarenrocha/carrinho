<?php
session_start();
include 'conexao.php';

// Buscar produtos do banco
$stmt = $pdo->query("SELECT * FROM produtos");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Adicionar produto no carrinho
if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];

    // Verifica se produto existe no banco
    $stmt = $pdo->prepare("SELECT id FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    if ($stmt->rowCount() > 0) {
        if (isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id]++;
        } else {
            $_SESSION['carrinho'][$id] = 1;
        }
        header('Location: index.php'); 
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Loja Simples</title>
    <link rel="stylesheet" href="css/estilo.css" />
</head>
<body>
    <h1>Produtos</h1>
    <ul>
        <?php foreach ($produtos as $produto): ?>
            <li>
                <strong><?= htmlspecialchars($produto['nome']) ?></strong> -
                R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                <a href="index.php?add=<?= $produto['id'] ?>">Adicionar ao carrinho</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="carrinho.php">Ver Carrinho (<?= isset($_SESSION['carrinho']) ? array_sum($_SESSION['carrinho']) : 0 ?>)</a>
</body>
</html>
