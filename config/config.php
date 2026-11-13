<?php

date_default_timezone_set('America/Sao_Paulo');

$host    = getenv('MYSQLHOST')     ?: ($_ENV['DB_HOST'] ?? '127.0.0.1');
$port    = getenv('MYSQLPORT')     ?: ($_ENV['DB_PORT'] ?? '3306');
$db      = getenv('MYSQLDATABASE') ?: ($_ENV['DB_NAME'] ?? 'sistema_rotas');
$user    = getenv('MYSQLUSER')     ?: ($_ENV['DB_USER'] ?? 'root');
$pass    = getenv('MYSQLPASSWORD') ?: ($_ENV['DB_PASS'] ?? '');
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Carrega variáveis simples do arquivo .env na raiz
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        [$name, $value] = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}