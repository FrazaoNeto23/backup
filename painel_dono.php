<?php
session_start();
include "config.php";

// Só permite acesso se for dono
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != "dono") {
    header("Location: acesso.php");
    exit;
}

$msg = "";

// Adicionar produto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao']) && $_POST['acao'] == "add") {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $stmt = $conn->prepare("INSERT INTO produtos (nome, preco) VALUES (?, ?)");
    $stmt->bind_param("sd", $nome, $preco);
    $stmt->execute();
    $msg = "Produto adicionado!";
}

// Excluir produto
if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $msg = "Produto excluído!";
}

// Listar produtos
$res = $conn->query("SELECT * FROM produtos");
$produtos = $res->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Painel do Dono</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body>
    <div class="container">
        <h1>👑 Painel do Dono</h1>
        <p>Bem-vindo, <?php echo $_SESSION['usuario']; ?>!</p>

        <?php if ($msg)
            echo "<p class='msg'>$msg</p>"; ?>

        <h2>Adicionar Produto</h2>
        <form method="POST">
            <input type="hidden" name="acao" value="add">
            <input type="text" name="nome" placeholder="Nome do produto" required>
            <input type="number" step="0.01" name="preco" placeholder="Preço" required>
            <button type="submit">Adicionar</button>
        </form>

        <h2>Produtos Cadastrados</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($produtos as $p): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo $p['nome']; ?></td>
                    <td>R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></td>
                    <td><a href="?del=<?php echo $p['id']; ?>" onclick="return confirm('Excluir produto?')">🗑 Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p><a class="btn" href="logout.php">Sair</a></p>
    </div>
</body>

</html>