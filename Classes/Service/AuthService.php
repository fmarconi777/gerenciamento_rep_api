<?php

namespace Service;

use InvalidArgumentException;
use Repository\AuthRepository;
use Firebase\JWT\JWT;
use Util\ConstantesGenericasUtil;

class AuthService
{

    public $row;

    private array $dadsoCorpoRequest = [];
    private object $AuthRepository;

    public function __construct($dadosRequest = [])
    {
        $this->dadsoCorpoRequest = $dadosRequest;
        $this->AuthRepository = new AuthRepository();
    }

        public function validarLogin()
    {
        $retorno = null;
        $login = $this->dadsoCorpoRequest['Login'];
        $senha = $this->dadsoCorpoRequest['Senha'];

        if ($this->dadosRequest !== [] && trim($login) !== '' && trim($senha) !== '') {
            $this->row = $this->AuthRepository->auth($login);
            [$id, $status, $matricula, $nome, $hash_senha] = [$this->row['ID'], $this->row['Status'], $this->row['Matricula'], $this->row['Nome'], $this->row['Senha']];
            if ($status === 'A' && password_verify($senha, $hash_senha)) {
                $encode = $this->jwtEncodeData($id, $nome, $matricula, $login);
                $retorno = array(
                    "Auth" => true,
                    "Token" => $encode
                );
            } else {
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_LOGIN);
            }
        } else {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
        }

        $this->validarRetornoRequest($retorno);

        return $retorno;
    }

    private function jwtEncodeData($id, $nome, $matricula, $login)
    {
        $secret_key = SECRET_KEY;
        $servidor = SERVIDOR;
        $apps = APPS;
        $criacao_token = time();
        $valido_a_partir = $criacao_token;
        $expira = $criacao_token + 60 * 60 * 4;
        $token = array(
            "iss" => $servidor,
            "aud" => $apps,
            "iat" => $criacao_token,
            "nbf" => $valido_a_partir,
            "exp" => $expira,
            "data" => array(
                "ID" => $id,
                "Matricula" => $matricula,
                "Nome" => $nome,
                "Login" => $login
            )
        );
        $encode = JWT::encode($token, $secret_key);
        return $encode;
    }

    private function validarRetornoRequest($retorno): void
    {
        if ($retorno === null) {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
    }
}
