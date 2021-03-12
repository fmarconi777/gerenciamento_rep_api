<?php

namespace Repository;

use DB\MySQL;
use InvalidArgumentException;
use Util\ConstantesGenericasUtil;
use Firebase\JWT\JWT;

class TokensAutorizadosRepository
{

    /**
     * @var object|MySQL
     */
    private object $MySQL;
    public const tabela = "Funcionario";
    private const secret_key = SECRET_KEY;

    /**
     * TokensAutorizadosRepository constructor
     */

    public function __construct()
    {
        $this->MySQL = new MySQL();
    }

    /**
     * @param $token
     */

    public function validarToken($token)
    {
        $token = str_replace([' ', 'Bearer'], '', $token);
        if ($token) {
            try {
                $decode = JWT::decode($token, self::secret_key, array('HS256'));
                $data = (array) $decode->data;
                [$matricula, $nome, $login] = [$data['Matricula'], $data['Nome'], $data['Login']];
                $consulta = 'SELECT ID FROM ' . self::tabela . ' WHERE Matricula = :matricula AND Nome = :nome AND Login = :login';
                $stmt = $this->getMySQL()->getDb()->prepare($consulta);
                $stmt->bindValue(':matricula', $matricula);
                $stmt->bindValue(':nome', $nome);
                $stmt->bindValue(':login', $login);
                $stmt->execute();
                if ($stmt->rowCount() !== 1) {
                    header('HTTP/1.1 401 Unauthorized');
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TOKEN_NAO_AUTORIZADO);
                }
            } catch (\Firebase\JWT\ExpiredException $exception) {
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TOKEN_EXPIRADO);
            } catch (\Firebase\JWT\SignatureInvalidException $exception) {
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_LOGIN);
            } catch (\Firebase\JWT\BeforeValidException $exception) {
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
            } catch (\DomainException $exception) {
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TOKEN_NAO_AUTORIZADO);
            } catch (\InvalidArgumentException $exception) {
                throw new \InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TIPO_DOMINIO);
            } catch (\UnexpectedValueException $exception) {
                throw new \InvalidArgumentException($exception);
            }
        } else {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TOKEN_VAZIO);
        }
    }

    /**
     * @return MySQL|object
     */

    public function getMySQL()
    {
        return $this->MySQL;
    }
}
