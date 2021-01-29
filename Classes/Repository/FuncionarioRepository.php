<?php

namespace Repository;

use DB\MySQL;

class FuncionarioRepository {

    private object $MySQL;
    public const tabela = "Funcionario";

    /**
     * FuncionarioRepository constructor
     */

    public function __construct()
    {
        $this->MySQL = new MySQL();
    }

    /**
     * @param $area
     * @param $status
     * @param $matricula
     * @param $nome
     * @param $login
     * @param $senha
     * @return int
     */

    public function insertUser($area, $status, $matricula, $nome, $login, $senha) {
        $consultaInsert = 'INSERT INTO '.self::tabela.' (Area, Status, Matricula, Nome, Login, Senha) VALUES (:area, :status, :matricula, :nome, :login, :senha)';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($consultaInsert);
        $stmt->bindParam(':area', $area);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':matricula', $matricula);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @return MySQL|object
     */

    public function getMySQL() {
        return $this->MySQL;
    }
}