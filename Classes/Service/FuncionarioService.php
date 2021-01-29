<?php

namespace Service;

use InvalidArgumentException;
use Repository\FuncionarioRepository;
use Util\ConstantesGenericasUtil;

class FuncionarioService
{

    public const tabela = 'Funcionario';
    public const RECURSOS_GET = ['listar'];
    public const RECURSOS_DELETE = ['deletar'];
    public const RECURSOS_POST = ['cadastrar'];

    private array $dados;
    private array $dadsoCorpoRequest = [];
    private object $FuncionarioRepository;

    /**
     * FuncionarioService constructor
     * @param array $dados
     */

    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->FuncionarioRepository = new FuncionarioRepository();
    }

    public function validarGet()
    {

        $retorno = null;
        $recurso = $this->dados['recurso'];

        if (in_array($recurso, self::RECURSOS_GET, true)) {
            $retorno = $this->dados['id'] > 0 ? $this->getOneByKey() : $this->$recurso();
        } else {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($retorno === null) {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;
    }

    public function validarDelete(){
        $retorno = null;
        $recurso = $this->dados['recurso'];

        if (in_array($recurso, self::RECURSOS_DELETE, true)) {
            if ($this->dados['id'] > 0) {
                $retorno = $this->$recurso();
            } else {
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
            }
        } else {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($retorno === null) {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;
    }

    public function validarPost(){
        $retorno = null;
        $recurso = $this->dados['recurso'];

        if (in_array($recurso, self::RECURSOS_POST, true)) {
            $retorno = $this->$recurso();
        } else {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if ($retorno === null) {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;
    }

    public function setDadosCorpoRequest($dadosRequest) {
        $this->dadsoCorpoRequest = $dadosRequest;
        
    }



    private function getOneByKey()
    {
        return $this->FuncionarioRepository->getMySQL()->getOneByKey(self::tabela, $this->dados['id']);
    }

    private function listar()
    {
        return $this->FuncionarioRepository->getMySQL()->getAll(self::tabela);
    }

    private function deletar() {
        return $this->FuncionarioRepository->getMySQL()->delete(self::tabela, $this->dados['id']);
    }

    private function cadastrar() {
        [$area, $status, $matricula, $nome, $login, $senha] = [$this->dadsoCorpoRequest['Area'], $this->dadsoCorpoRequest['Status'], 
        $this->dadsoCorpoRequest['Matricula'], $this->dadsoCorpoRequest['Nome'], $this->dadsoCorpoRequest['Login'], $this->dadsoCorpoRequest['Senha']];
        if ($area && $status && $matricula && $nome && $login && $senha) {
            if ($this->FuncionarioRepository->insertUser($area, $status, $matricula, $nome, $login, $senha) > 0) {
                $idInserido = $this->FuncionarioRepository->getMySQL()->getDb()->lastInsertId();
                $this->FuncionarioRepository->getMySQL()->getDb()->commit();
                return ['id_inserido' => $idInserido];
            }
            $this->FuncionarioRepository->getMySQL()->getDb()->rollBack();
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_CADASTRO_COMPLETO_OBRIGATORIO);
    }
}
