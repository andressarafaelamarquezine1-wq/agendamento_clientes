<?php
// public/gerar_rotas.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/RotaController.php';

// Pega o ano e mês atuais ou os passados via GET (ex: ?ano=2026&mes=12)
$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : (int)date('Y');
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('n');

$rotaController = new RoutesController($pdo);
$resultado = $rotaController->gerarRotasMensais($ano, $mes);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($resultado, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);