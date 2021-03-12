<?php

namespace Repository;

use DB\MySQL;
use InvalidArgumentException;
use Util\ConstantesGenericasUtil;

class AuthRepository
{
    /**
     * @var object|MySQL
     */
    private object $MySQL;
    public const tabela = "Funcionario";

    /**
     * AuthRepository constructor
     */

    public function __construct()
    {
        $this->MySQL = new MySQL();
    }

    /**
     * @param $login
     * @param $senha
     * @return int
     */

    public function auth($login) {
        $consulta = 'SELECT ID, Status, Matricula, Nome, Login, Senha  FROM '.self::tabela.' WHERE Login = :login';
        $stmt = $this->MySQL->getDb()->prepare($consulta);
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        if ($stmt->rowCount() === 1){
            $row = $stmt->fetch($this->MySQL->getDb()::FETCH_ASSOC);
            return $row;
        } else {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_LOGIN);
        }
    }

    /**
     * @return MySQL|object
     */

    public function getMySQL() {
        return $this->MySQL;
    }
}