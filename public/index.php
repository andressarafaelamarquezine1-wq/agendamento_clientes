<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/RotaController.php';

$rotaController = new RotaController($pdo);

// Dar baixa no atendimento se clicar em "Concluir"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['concluir_id'])) {
    $stmt = $pdo->prepare("UPDATE atendimentos SET status = 'Concluído' WHERE id = :id");
    $stmt->execute([':id' => (int) $_POST['concluir_id']]);
    header("Location: index.php");
    exit;
}

$dataFiltro = $_GET['data'] ?? date('Y-m-d');
$rotasHoje = $rotaController->buscarRotasDoDia($dataFiltro);

require_once __DIR__ . '/../includes/header.php';
?>

<h2>Rotas Programadas — <?= date('d/m/Y', strtotime($dataFiltro)) ?></h2>

<form method="GET" class="filtro-data">
    <label for="data">Filtrar data:</label>
    <input type="date" id="data" name="data" value="<?= htmlspecialchars($dataFiltro) ?>">
    <button type="submit">Buscar</button>
</form>

<?php if (empty($rotasHoje)): ?>
    <p class="vazio">Nenhum atendimento pendente para esta data.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Telefone</th>
                <th>Endereço / Cidade</th>
                <th>Status</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rotasHoje as $rota): ?>
                <tr>
                    <td><?= htmlspecialchars($rota['nome']) ?></td>
                    <td><?= htmlspecialchars($rota['telefone']) ?></td>
                    <td><?= htmlspecialchars($rota['endereco'] . ' - ' . $rota['cidade']) ?></td>
                    <td><?= htmlspecialchars($rota['status']) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="concluir_id" value="<?= $rota['atendimento_id'] ?>">
                            <button type="submit">Concluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

    </main>
</body>
</html>