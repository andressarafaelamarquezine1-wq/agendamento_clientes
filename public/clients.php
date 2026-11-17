
<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/controllers/clienteController.php';

try {
    $clienteController = new ClienteController($pdo);

    // LISTAR CLIENTES
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        echo json_encode($clienteController->listarAtivos());
        exit;
    }

    // RECEBER DADOS
    $dados = json_decode(file_get_contents('php://input'), true);

    // CADASTRAR CLIENTE
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!is_array($dados)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Dados inválidos.']);
            exit;
        }

        $clienteController->cadastrar($dados);

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Cliente cadastrado com sucesso!'
        ]);
        exit;
    }

    // EXCLUIR CLIENTE
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $id = filter_var($dados['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido.']);
            exit;
        }

        $clienteController->desativar($id);

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Cliente excluído com sucesso!'
        ]);
        exit;
    }

    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido.']);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'erro' => 'Erro ao processar a solicitação.',
        'detalhe' => $e->getMessage()
    ]);
}
