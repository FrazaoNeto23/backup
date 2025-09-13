<?php
session_start();
include "config.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: acesso.php");
    exit;
}

// Buscar pedidos do cliente
$stmt = $conn->prepare("SELECT p.id, pr.nome, p.adicionais, p.data 
                        FROM pedidos p 
                        JOIN produtos pr ON p.id_produto = pr.id 
                        WHERE p.id_usuario=? ORDER BY p.data DESC");
$stmt->bind_param("i", $_SESSION['id_usuario']);
$stmt->execute();
$res = $stmt->get_result();
$pedidos = $res->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Meus Pedidos</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body>
    <div class="container">
        <h1>📋 Meus Pedidos</h1>
        <?php if (count($pedidos) == 0): ?>
            <p>Você ainda não fez nenhum pedido.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Adicionais</th>
                    <th>Data</th>
                </tr>
                <?php foreach ($pedidos as $p): ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td><?php echo $p['nome']; ?></td>
                        <td><?php echo $p['adicionais'] ?: "-"; ?></td>
                        <td><?php echo $p['data']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <p><a class="btn" href="painel_cliente.php">Voltar</a></p>
    </div>
</body>

</html>