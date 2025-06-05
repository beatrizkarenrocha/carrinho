<?php
session_start();
include 'conexao.php';

// Remover produto
if (isset($_GET['remover'])) {
    $id = (int)$_GET['remover'];
    if (isset($_SESSION['carrinho'][$id])) {
        unset($_SESSION['carrinho'][$id]);
    }
    header('Location: carrinho.php');
    exit;
}

$carrinho = $_SESSION['carrinho'] ?? [];

$produtos = [];
if (!empty($carrinho)) {
    // Buscar dados dos produtos no carrinho
    $ids = implode(',', array_keys($carrinho));
    $stmt = $pdo->query("SELECT * FROM produtos WHERE id IN ($ids)");
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Reorganiza produtos por id para facilitar acesso
    $produtos_assoc = [];
    foreach ($produtos as $p) {
        $produtos_assoc[$p['id']] = $p;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Carrinho</title>
    <link rel="stylesheet" href="css/estilo.css" />
</head>
<body>
    <h1>Seu Carrinho</h1>
    <?php if (empty($carrinho)): ?>
        <p>O carrinho está vazio.</p>
        <a href="index.php">Voltar para produtos</a>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço Unitário</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($carrinho as $id => $qtd):
                    $produto = $produtos_assoc[$id];
                    $subtotal = $produto['preco'] * $qtd;
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                        <td><?= $qtd ?></td>
                        <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                        <td><a href="carrinho.php?remover=<?= $id ?>">Remover</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h3>Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>
        <a href="index.php">Continuar Comprando</a>
    <?php endif; ?>
</body>
</html>
