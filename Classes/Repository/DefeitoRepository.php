<?php

namespace Repository;

use DB\MySQL;

class DefeitoRepository
{

    /**
     * @var object|MySQL
     */
    private object $MySQL;
    public const tabela = "Defeitos";

    /**
     * DefeitoRepository constructor
     */

    public function __construct()
    {
        $this->MySQL = new MySQL();
    }

    /**
     * @param $defeito
     * @param $descricao
     * @return int
     */

    public function insertDefect($defeito, $descricao)
    {
        $consultaInsert = 'INSERT INTO ' . self::tabela . ' (Defeito, Descricao) VALUES (:defeito, :descricao)';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($consultaInsert);
        $stmt->bindParam(':defeito', $defeito);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @param $id
     * @param $dados
     * @return int
     */

    public function updateDefect($id, $dados)
    {
        $consultaUpdate = 'UPDATE ' . self::tabela . ' SET Defeito = :defeito, Descricao = :descricao WHERE ID =:id';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($consultaUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':defeito', $dados['Defeito']);
        $stmt->bindParam(':descricao', $dados['Descricao']);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @return MySQL|object
     */

    public function getMySQL()
    {
        return $this->MySQL;
    }
}
