<?php

namespace Validator;

use InvalidArgumentException;
use Repository\TokensAutorizadosRepository;
use Service\FuncionarioService;
use Util\ConstantesGenericasUtil;
use Util\JsonUtil;

class RequestValidator {

    public $request;
    private array $dadosRequest = [];
    private object $TokensAutorizadosRepository;

    const GET = 'GET';
    const DELETE = 'DELETE';
    const FUNCIONARIO = 'FUNCIONARIO';

    public function __construct($request)
    {
        $this->request = $request;
        $this->TokensAutorizadosRepository = new TokensAutorizadosRepository();
    }

    /**
     * @return string
     */

    public function processarRequest() {
        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;

        if (in_array($this->request['metodo'], ConstantesGenericasUtil::TIPO_REQUEST, true)){
            $retorno = $this->direcionarRequest();
        }   
        return $retorno;
    }

    private function direcionarRequest(){
        if ($this->request['metodo'] !== self::GET && $this->request['metodo'] !== self::DELETE){
            $this->dadosRequest = JsonUtil::tratarCorpoRequisicaoJson();
        }
        $this->TokensAutorizadosRepository->validarToken(getallheaders()['Authorization']);
        $metodo = $this->request['metodo'];
        return $this->$metodo(); // Função variável (retorna a string da variável como uma função). ex: $metodo = get => 'get'()        
    }

    private function get() {
        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;

        if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_GET, true)) {
            switch ($this->request['rota']) {
                case self::FUNCIONARIO:
                    $FuncionarioService = new FuncionarioService($this->request);
                    $retorno = $FuncionarioService->validarGet();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $retorno;
    }

    private function delete() {
        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;

        if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_DELETE, true)) {
            switch($this->request['rota']) {
                case self::FUNCIONARIO:
                    $FuncionarioService = new FuncionarioService($this->request);
                    $retorno = $FuncionarioService->validarDelete();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $retorno;
    }

    private function post() {
        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;

        if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_POST, true)) {
            switch($this->request['rota']) {
                case self::FUNCIONARIO:
                    $FuncionarioService = new FuncionarioService($this->request);
                    $FuncionarioService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $FuncionarioService->validarPost();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $retorno;
    }
}
