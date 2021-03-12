<?php

namespace Service;

use InvalidArgumentException;
use Repository\LocalRepository;
use Util\ConstantesGenericasUtil;

class LocalService
{

    public const tabela = 'Local';
    public const ordem = 'Nome';
    public const RECURSOS_GET = ['listar'];
    public const RECURSOS_DELETE = ['deletar'];
    public const RECURSOS_POST = ['cadastrar'];
    public const RECURSOS_PUT = ['atualizar'];

    private array $dados;
    private array $dadsoCorpoRequest = [];
    private object $LocalRepository;

    /**
     * LocalService constructor
     * @param array $dados
     */

    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->LocalRepository = new LocalRepository;
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

        $this->validarRetornoRequest($retorno);

        return $retorno;
    }

    public function validarDelete()
    {
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

        $this->validarRetornoRequest($retorno);

        return $retorno;
    }

    public function validarPost()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];

        if (in_array($recurso, self::RECURSOS_POST, true)) {
            $retorno = $this->$recurso();
        } else {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validarRetornoRequest($retorno);

        return $retorno;
    }

    public function setDadosCorpoRequest($dadosRequest)
    {
        $this->dadsoCorpoRequest = $dadosRequest;
    }

    public function validarPut()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];

        if (in_array($recurso, self::RECURSOS_PUT, true)) {
            if ($this->dados['id'] > 0) {
                $retorno = $this->$recurso();
            } else {
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
            }
        } else {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validarRetornoRequest($retorno);

        return $retorno;
    }

    private function getOneByKey()
    {
        return $this->LocalRepository->getMySQL()->getOneByKey(self::tabela, $this->dados['id']);
    }

    private function listar()
    {
        return $this->LocalRepository->getMySQL()->getAll(self::tabela, self::ordem);
    }

    private function deletar()
    {
        return $this->LocalRepository->getMySQL()->delete(self::tabela, $this->dados['id']);
    }

    private function cadastrar()
    {
        [$sigla, $nome] = [ $this->dadsoCorpoRequest['Sigla'], $this->dadsoCorpoRequest['Nome'] ];
        if ($sigla && $nome) {
            if ($this->LocalRepository->insertLocation($sigla, $nome) > 0) {
                $idInserido = $this->LocalRepository->getMySQL()->getDb()->lastInsertId();
                $this->LocalRepository->getMySQL()->getDb()->commit();
                return ['id_inserido' => $idInserido];
            }
            $this->LocalRepository->getMySQL()->getDb()->rollBack();

            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_CADASTRO_COMPLETO_OBRIGATORIO);
    }

    private function atualizar()
    {
        if ($this->LocalRepository->updateLocation($this->dados['id'], $this->dadsoCorpoRequest) > 0) {
            $this->LocalRepository->getMySQL()->getDb()->commit();
            return ConstantesGenericasUtil::MSG_ATUALIZADO_SUCESSO;
        }
        $this->LocalRepository->getMySQL()->getDb()->rollBack();
        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_NAO_AFETADO);
    }

    private function validarRetornoRequest($retorno): void
    {
        if ($retorno === null) {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
    }
}
