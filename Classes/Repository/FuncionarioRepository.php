<?php

namespace Repository;

use DB\MySQL;

class FuncionarioRepository
{

    /**
     * @var object|MySQL
     */
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

    public function insertUser($area, $status, $matricula, $nome, $login, $senha)
    {
        $consultaInsert = 'INSERT INTO ' . self::tabela . ' (Area, Status, Matricula, Nome, Login, Senha) VALUES (:area, :status, :matricula, :nome, :login, :senha)';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($consultaInsert);
        $stmt->bindParam(':area', $area);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':matricula', $matricula);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':login', $login);
        $password_hash = password_hash($senha, PASSWORD_BCRYPT);
        $stmt->bindParam(':senha', $password_hash);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @param $id
     * @param $dados
     * @return int
     */

    public function updateUser($id, $dados)
    {
        $consultaUpdate = 'UPDATE ' . self::tabela . ' SET Area = :area, Status = :status, Matricula = :matricula, Nome = :nome, Login = :login, Senha = :senha WHERE ID =:id';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($consultaUpdate);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':area', $dados['Area']);
        $stmt->bindParam(':status', $dados['Status']);
        $stmt->bindParam(':matricula', $dados['Matricula']);
        $stmt->bindParam(':nome', $dados['Nome']);
        $stmt->bindParam(':login', $dados['Login']);
        $password_hash = password_hash($dados['Senha'], PASSWORD_BCRYPT);
        $stmt->bindParam(':senha', $password_hash);
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
