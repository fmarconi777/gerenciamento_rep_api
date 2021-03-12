<?php

namespace Validator;

use InvalidArgumentException;
use Repository\TokensAutorizadosRepository;
use Service\AuthService;
use Service\DefeitoService;
use Service\FuncionarioService;
use Service\LocalService;
use Util\ConstantesGenericasUtil;
use Util\JsonUtil;

class RequestValidator {

    public $request;
    private array $dadosRequest = [];
    private object $TokensAutorizadosRepository;

    const GET = 'GET';
    const DELETE = 'DELETE';
    const ROTAS = [
        'FUNCIONARIO' => 'FUNCIONARIO',
        'LOCAL' => 'LOCAL',
        'DEFEITO' => 'DEFEITO'
        ];

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
        if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_LOGIN, true)) {
            $AuthService = new AuthService($this->dadosRequest);
            return $AuthService->validarLogin();
        }
        $this->TokensAutorizadosRepository->validarToken(getallheaders()['Authorization']);
        $metodo = $this->request['metodo'];
        return $this->$metodo(); // Função variável (retorna a string da variável como uma função). ex: $metodo = get => 'get'()        
    }

    private function get() {
        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;

        if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_GET, true)) {
            switch ($this->request['rota']) {
                case self::ROTAS['FUNCIONARIO']:
                    $FuncionarioService = new FuncionarioService($this->request);
                    $retorno = $FuncionarioService->validarGet();
                    break;
                case self::ROTAS['LOCAL']:
                    $LocalService = new LocalService($this->request);
                    $retorno = $LocalService->validarGet();
                    break;
                case self::ROTAS['DEFEITO']:
                    $DefeitoService = new DefeitoService($this->request);
                    $retorno = $DefeitoService->validarGet();
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
                case self::ROTAS['FUNCIONARIO']:
                    $FuncionarioService = new FuncionarioService($this->request);
                    $retorno = $FuncionarioService->validarDelete();
                    break;
                case self::ROTAS['LOCAL']:
                    $LocalService = new LocalService($this->request);
                    $retorno = $LocalService->validarDelete();
                    break;
                case self::ROTAS['DEFEITO']:
                    $DefeitoService = new DefeitoService($this->request);
                    $retorno = $DefeitoService->validarDelete();
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
                case self::ROTAS['FUNCIONARIO']:
                    $FuncionarioService = new FuncionarioService($this->request);
                    $FuncionarioService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $FuncionarioService->validarPost();
                    break;
                case self::ROTAS['LOCAL']:
                    $LocalService = new LocalService($this->request);
                    $LocalService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $LocalService->validarPost();
                    break;
                case self::ROTAS['DEFEITO']:
                    $DefeitoService = new DefeitoService($this->request);
                    $DefeitoService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $DefeitoService->validarPost();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $retorno;
    }

    private function put() {
        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;

        if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_PUT, true)) {
            switch($this->request['rota']) {
                case self::ROTAS['FUNCIONARIO']:
                    $FuncionarioService = new FuncionarioService($this->request);
                    $FuncionarioService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $FuncionarioService->validarPut();
                    break;
                case self::ROTAS['LOCAL']:
                    $LocalService = new LocalService($this->request);
                    $LocalService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $LocalService->validarPut();
                    break;
                case self::ROTAS['DEFEITO']:
                    $DefeitoService = new DefeitoService($this->request);
                    $DefeitoService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $DefeitoService->validarPut();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $retorno;
    }
}
