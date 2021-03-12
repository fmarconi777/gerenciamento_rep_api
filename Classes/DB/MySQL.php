<?php

namespace DB;

use InvalidArgumentException;
use PDO;
use PDOException;
use Util\ConstantesGenericasUtil;

class MySQL {

    private object $db;

    /**
     * MySQL constructor
     */

    public function __construct()
    {
        $this->db = $this->setDB();
    }

    /**
     * @return PDO
     */

    public function setDB(){
        try {
            return new PDO('mysql:host='.HOST.'; dbname='.BANCO.';', USUARIO, SENHA);
        }catch (PDOException $exception) {
            throw new PDOException($exception->getMessage());
        }
    }

    /**
     * @param $tabela
     * @param $id
     * @return string
     */

    public function delete($tabela, $id){

        $consultaDelete = 'DELETE FROM '.$tabela.' WHERE ID = :id';
        if ($tabela && $id) {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($consultaDelete);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $this->db->commit();
                return ConstantesGenericasUtil::MSG_DELETADO_SUCESSO;
            }
            $this->db->rollBack();
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_SEM_RETORNO);
        }
        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
    }

    /**
     * @param $tabela
     * @param $status
     * @param $id
     * @return string
     */

    public function setStatus($tabela, $status, $id){
        
        $status = strtoupper($status);
        $consultaSetStatus = 'UPDATE '.$tabela.' SET Status = '.$status.' WHERE ID = :id';
        if($tabela && $status && $id) {
            $stmt = $this->db->prepare($consultaSetStatus);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return ConstantesGenericasUtil::MSG_ATUALIZADO_SUCESSO;
            }
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_SEM_RETORNO);
        }
        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
    }

    /**
     * @param $tabela
     * @return array
     */

    public function getAll($tabela, $ordem){

        if($tabela) {
            $consulta = 'SELECT * FROM '.$tabela.' ORDER BY '.$ordem;
            $stmt = $this->db->query($consulta);
            $registros = $stmt->fetchAll($this->db::FETCH_ASSOC);
            if (is_array($registros) && count($registros) > 0) {
                return $registros;
            }
        }
        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_SEM_RETORNO);
    }

    /**
     * @param $tabela
     * @param $id
     * @return array|mixed
     */

    public function getOneByKey($tabela, $id) {
        if($tabela && $id) {
            $consulta = 'SELECT * FROM '.$tabela.' WHERE ID = :id';
            $stmt = $this->db->prepare($consulta);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            if($stmt->rowCount() === 1) {
                return $stmt->fetch($this->db::FETCH_ASSOC);
            }
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_SEM_RETORNO);
        }
        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
    }

    /**
     * @return object|PDO
     */

    public function getDb() {
        return $this->db;
    }

}