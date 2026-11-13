<?php
// public/clientes.php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/ClienteController.php';

$clienteController = new ClienteController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clienteController->cadastrar($_POST);
    header("Location: clientes.php");
    exit;
}

$clientes = $clienteController->listarAtivos();

require_once __DIR__ . '/../includes/header.php';
?>

<h2>Cadastrar Cliente na Rota Fixa</h2>

<form method="POST" class="form-grid">
    <input type="text" name="nome" placeholder="Nome do Cliente" required>
    <input type="text" name="telefone" placeholder="Telefone / WhatsApp">
    <input type="text" name="endereco" placeholder="Endereço">
    <input type="text" name="cidade" placeholder="Cidade">
    <input type="number" name="dia_fixo_atendimento" min="1" max="31" placeholder="Dia fixo (1 a 31)" required>
    <select name="regra_fim_de_semana">
        <option value="PROXIMA_SEGUNDA">Se cair no FDS: Próxima Segunda</option>
        <option value="SEXTA_ANTERIOR">Se cair no FDS: Sexta Anterior</option>
        <option value="MANTER">Manter no FDS</option>
    </select>
    <button type="submit">Salvar Cliente</button>
</form>

<h3>Clientes Ativos</h3>
<table>
    <thead>
        <tr>
            <th>Dia Fixo</th>
            <th>Cliente</th>
            <th>Telefone</th>
            <th>Cidade</th>
            <th>Regra FDS</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clientes as $c): ?>
            <tr>
                <td><strong>Todo dia <?= $c['dia_fixo_atendimento'] ?></strong></td>
                <td><?= htmlspecialchars($c['nome']) ?></td>
                <td><?= htmlspecialchars($c['telefone']) ?></td>
                <td><?= htmlspecialchars($c['cidade']) ?></td>
                <td><?= htmlspecialchars($c['regra_fim_de_semana']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    </main>
</body>
</html>