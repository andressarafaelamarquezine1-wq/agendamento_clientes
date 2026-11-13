<?php

class ClienteController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listarAtivos(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM clientes WHERE ativo = 1 ORDER BY dia_fixo_atendimento ASC, nome ASC");
        return $stmt->fetchAll();
    }

    public function cadastrar(array $dados): bool
    {
        $sql = "INSERT INTO clientes (nome, telefone, endereco, cidade, dia_fixo_atendimento, regra_fim_de_semana)
                VALUES (:nome, :telefone, :endereco, :cidade, :dia_fixo, :regra)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nome'     => trim($dados['nome']),
            ':telefone' => trim($dados['telefone'] ?? ''),
            ':endereco' => trim($dados['endereco'] ?? ''),
            ':cidade'   => trim($dados['cidade'] ?? ''),
            ':dia_fixo' => (int) $dados['dia_fixo_atendimento'],
            ':regra'    => $dados['regra_fim_de_semana'] ?? 'PROXIMA_SEGUNDA',
        ]);
    }

    public function desativar(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE clientes SET ativo = 0 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}