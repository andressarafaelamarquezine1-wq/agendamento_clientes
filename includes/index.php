<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Se você enviou o formulário, vamos depurar o que veio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<div style='background: #333; color: #ff5555; padding: 15px; font-family: monospace;'>";
    echo "<h3>Dados recebidos do formulário ($_POST):</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    try {
        $stmt = $pdo->prepare("INSERT INTO clientes (nome, telefone, endereco, cidade, dia_fixo, regra_fds) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['nome'] ?? '',
            $_POST['telefone'] ?? '',
            $_POST['endereco'] ?? '',
            $_POST['cidade'] ?? '',
            $_POST['dia_fixo'] ?? '',
            $_POST['regra_fds'] ?? ''
        ]);
        echo "<p style='color: #55ff55;'>[SUCESSO] O INSERT rodou e salvou no banco!</p>";
    } catch (PDOException $e) {
        echo "<p>[ERRO NO MYSQL]: " . $e->getMessage() . "</p>";
    }
    echo "</div>";
}
// Configuração da conexão com o banco local (XAMPP)
$host = '127.0.0.1';
$db   = 'sistema_rotas'; // Altere se o nome do seu banco for diferente
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Processa o cadastro quando o formulário é enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $dia_fixo = $_POST['dia_fixo'] ?? '';
    $regra_fds = $_POST['regra_fds'] ?? '';

    if (!empty($nome)) {
        $stmt = $pdo->prepare("INSERT INTO clientes (nome, telefone, endereco, cidade, dia_fixo, regra_fds) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $telefone, $endereco, $cidade, $dia_fixo, $regra_fds]);
        header("Location: index.php");
        exit;
    }
}

// Busca os clientes cadastrados no banco em ordem
$stmt = $pdo->query("SELECT * FROM clientes ORDER BY dia_fixo ASC");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Clientes - Rota Fixa</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-top: 0; }
        form { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 30px; }
        .full-width { grid-column: span 3; }
        input, select { padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; width: 100%; box-sizing: border-box; }
        button { background: #0056b3; color: white; border: none; padding: 12px; border-radius: 4px; font-size: 16px; cursor: pointer; grid-column: span 3; }
        button:hover { background: #004085; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; color: #333; }
        tr:hover { background-color: #f1f1f1; }
        .empty-msg { text-align: center; color: #775; padding: 20px; font-style: italic; }
    </style>
</head>
<body>

<div class="container">
    <h2>Cadastrar Cliente na Rota Fixa</h2>
    
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome do Cliente" required>
        <input type="text" name="telefone" placeholder="Telefone / WhatsApp">
        <input type="text" name="endereco" placeholder="Endereço">
        <input type="text" name="cidade" placeholder="Cidade">
        <input type="number" name="dia_fixo" placeholder="Dia fixo (1 a 31)" min="1" max="31">
        <select name="regra_fds">
            <option value="PROXIMA_SEGUNDA">Se cair no FDS: Próxima Segunda</option>
            <option value="SEXTA_ANTERIOR">Se cair no FDS: Sexta Anterior</option>
        </select>
        <button type="submit">Salvar Cliente</button>
    </form>

    <h2>Clientes Cadastrados</h2>
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
            <?php if (count($clientes) > 0): ?>
                <?php foreach ($clientes as $c): ?>
                    <tr>
                        <td>Todo dia <?= htmlspecialchars($c['dia_fixo']) ?></td>
                        <td><?= htmlspecialchars($c['nome']) ?></td>
                        <td><?= htmlspecialchars($c['telefone']) ?></td>
                        <td><?= htmlspecialchars($c['cidade']) ?></td>
                        <td><?= htmlspecialchars($c['regra_fds']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="empty-msg">Nenhum cliente cadastrado ainda.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>