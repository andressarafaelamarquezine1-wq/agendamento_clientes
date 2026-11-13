<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Rotas</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <strong>Controle de Rotas</strong>
        <div>
            <a href="index.php">Rotas do Dia</a>
            <a href="clientes.php">Clientes</a>
            <a href="gerar_rotas.php?ano=<?= date('Y') ?>&mes=<?= date('n') ?>" target="_blank">Gerar Mês Atual</a>
        </div>
    </nav>
    <main class="container">