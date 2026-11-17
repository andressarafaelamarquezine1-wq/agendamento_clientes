<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions_datas.php';

class RoutesController 
{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Gera as rotas previstas para todos os clientes ativos em um determinado mês/ano.
     */
    public function gerarRotasMensais(int $ano, int $mes): array 
    {
        // 1. Busca todos os clientes ativos
        $stmt = $this->pdo->query("SELECT id, nome, dia_fixo_atendimento, regra_fim_de_semana FROM clientes WHERE ativo = 1");
        $clientes = $stmt->fetchAll();

        $competencia = sprintf('%04d-%02d-01', $ano, $mes);
        $gerados = 0;
        $erros = 0;

        // Prepara a query de inserção (com INSERT IGNORE ou ON DUPLICATE KEY UPDATE para evitar duplicar)
        $sql = "INSERT INTO atendimentos (cliente_id, competencia, data_prevista, status) 
                VALUES (:cliente_id, :competencia, :data_prevista, 'Pendente')
                ON DUPLICATE KEY UPDATE data_prevista = VALUES(data_prevista)";
        
        $stmtInsert = $this->pdo->prepare($sql);

        foreach ($clientes as $cliente) {
            try {
                // Calcula a data ajustada (ex: se cai no domingo, vai pra segunda)
                $dataPrevistaObj = calcularDataRota(
                    $ano, 
                    $mes, 
                    (int) $cliente['dia_fixo_atendimento'], 
                    $cliente['regra_fim_de_semana']
                );

                $dataPrevistaStr = $dataPrevistaObj->format('Y-m-d');

                // Grava no banco
                $stmtInsert->execute([
                    ':cliente_id' => $cliente['id'],
                    ':competencia' => $competencia,
                    ':data_prevista' => $dataPrevistaStr
                ]);

                $gerados++;
            } catch (\Exception $e) {
                $erros++;
            }
        }

        return [
            'sucesso' => true,
            'gerados' => $gerados,
            'erros' => $erros,
            'competencia' => $competencia
        ];
    }

    /**
     * Retorna os clientes que possuem rota marcada para o dia atual.
     */
    public function buscarRotasDoDia(string $dataAtual): array 
    {
        $sql = "SELECT c.nome, c.telefone, c.endereco, c.cidade, a.status, a.id as atendimento_id
                FROM atendimentos a
                JOIN clientes c ON a.cliente_id = c.id
                WHERE a.data_prevista = :data_atual AND a.status = 'Pendente'";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':data_atual' => $dataAtual]);
        
        return $stmt->fetchAll();
    }
}