<?php

namespace Util;

use JsonException as JsonExceptionAlias;

class JsonUtil {

    public function processarArrayParaRetornar($retorno) {
        $dados = [];
        $dados[ConstantesGenericasUtil::TIPO] = ConstantesGenericasUtil::TIPO_ERRO;

        if ((is_array($retorno) && count($retorno) > 0) || strlen($retorno) > 0) {
            $dados[ConstantesGenericasUtil::TIPO] = ConstantesGenericasUtil::TIPO_SUCESSO;
            $dados[ConstantesGenericasUtil::RESPOSTA] = $retorno;
        }
        $this->retornarJson($dados);
    }

    private function retornarJson($json) {
        if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] == APPS) {
            header('Content-Type: application/json, charset=UTF-8');
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
            header('Access-Control-Allow-Origin: '.APPS);
            header('Access-Control-Allow-Headers: origin, content-type, accept, authorization');
            echo json_encode($json);
        }
        exit;
    }

    /**
     * @return array|mixed
     */

    public static function tratarCorpoRequisicaoJson() {
        try {
            $postJson = json_decode(file_get_contents('php://input'), true);
        }catch (JsonExceptionAlias $exception){
            throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERR0_JSON_VAZIO);
        }

        if (is_array($postJson) && count($postJson) > 0) {
            return $postJson;
        }
    }

}