<?php
// cron/disparar_alertas.php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/RoutesController.php';

$rotaController = new RoutesController($pdo);

$anoAtual = (int) date('Y');
$mesAtual = (int) date('n');
$hoje     = date('Y-m-d');

// Garante que as rotas do mês corrente existam
$rotaController->gerarRotasMensais($anoAtual, $mesAtual);

// Busca os atendimentos previstos para hoje
$rotasHoje = $rotaController->buscarRotasDoDia($hoje);

if (empty($rotasHoje)) {
    echo "[{$hoje}] Nenhuma rota agendada para hoje.\n";
    exit;
}

$mensagem = "📅 *ROTAS DE HOJE (" . date('d/m/Y') . ")*\n\n";
foreach ($rotasHoje as $r) {
    $mensagem .= "• *{$r['nome']}* ({$r['cidade']})\n";
    $mensagem .= "  End: {$r['endereco']} | Tel: {$r['telefone']}\n\n";
}

// Exibe no terminal/log (aqui você pode plugar envio via API do WhatsApp/Telegram/E-mail)
echo $mensagem;