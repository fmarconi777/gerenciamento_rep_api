<?php

namespace Repository;

use DB\MySQL;

class LocalRepository
{

    /**
     * @var object|MySQL
     */
    private object $MySQL;
    public const tabela = "Local";

    /**
     * LocalRepository constructor
     */

    public function __construct()
    {
        $this->MySQL = new MySQL();
    }

    /**
     * @param $sigla
     * @param $nome
     * @return int
     */

    public function insertLocation($sigla, $nome)
    {
        $consultaInsert = 'INSERT INTO ' . self::tabela . ' (Sigla, Nome) VALUES (:sigla, :nome)';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($consultaInsert);
        $stmt->bindParam(':sigla', $sigla);
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @param $id
     * @param $dados
     * @return int
     */

    public function updateLocation($id, $dados)
    {
        $consultaUpdate = 'UPDATE ' . self::tabela . ' SET Sigla = :sigla, Nome = :nome WHERE ID =:id';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($consultaUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':sigla', $dados['Sigla']);
        $stmt->bindParam(':nome', $dados['Nome']);
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
