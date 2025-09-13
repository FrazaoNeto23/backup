<?php
session_start();
include "config.php";

// Só permite acesso se for cliente
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != "cliente") {
    header("Location: acesso.php");
    exit;
}

// Carregar produtos
$res = $conn->query("SELECT * FROM produtos");
$produtos = $res->fetch_all(MYSQLI_ASSOC);

// Salvar pedido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto_id = intval($_POST['produto_id']);
    $adicionais = isset($_POST['adicionais']) ? implode(",", $_POST['adicionais']) : "";
    $stmt = $conn->prepare("INSERT INTO pedidos (id_usuario, id_produto, adicionais) VALUES (?,?,?)");
    $stmt->bind_param("iis", $_SESSION['id_usuario'], $produto_id, $adicionais);
    $stmt->execute();
    $msg = "Pedido realizado com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cardápio</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body>
    <div class="container">
        <h1>🍔 Cardápio</h1>
        <p>Olá, <?php echo $_SESSION['usuario']; ?>!</p>

        <?php if (isset($msg))
            echo "<p class='msg'>$msg</p>"; ?>

        <?php foreach ($produtos as $p): ?>
            <form method="POST" class="produto-card">
                <h3><?php echo $p['nome']; ?> - R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></h3>
                <input type="hidden" name="produto_id" value="<?php echo $p['id']; ?>">

                <!-- Se for o Litrão, mostrar até 6 adicionais -->
                <?php if (stripos($p['nome'], "Litrão") !== false): ?>
                    <label><input type="checkbox" name="adicionais[]" value="Granola"> Granola</label>
                    <label><input type="checkbox" name="adicionais[]" value="Leite em pó"> Leite em pó</label>
                    <label><input type="checkbox" name="adicionais[]" value="Paçoca"> Paçoca</label>
                    <label><input type="checkbox" name="adicionais[]" value="Leite condensado"> Leite condensado</label>
                    <label><input type="checkbox" name="adicionais[]" value="Ovomaltine"> Ovomaltine</label>
                    <label><input type="checkbox" name="adicionais[]" value="Morango"> Morango</label>
                <?php endif; ?>

                <button type="submit">Pedir</button>
            </form>
        <?php endforeach; ?>

        <p><a class="btn" href="meus_pedidos.php">Meus Pedidos</a></p>
        <p><a class="btn" href="logout.php">Sair</a></p>
    </div>
</body>

</html>