<?php

namespace Nuovatech\Neon\Http;

/**
 * Classe de resposta para os códigos HTTP informados
 * @author Eduardo Marinho
 */
abstract class Exception
{

    /**
     * Retorna a mensagem e código correspondente ao código informado.
     * @param int $code HTTP code status or Exception code
     */
    public static function response(int $code, string $msg = null)
    {
        header('Content-Type: application/json');

        http_response_code($code);

        switch ($code) {
            case 200: {
                    exit(json_encode([
                        "code" => $code,
                        "message" => (empty($msg)) ? "Success" : $msg
                    ]));
                }
            case 400: {
                    exit(json_encode([
                        "code" => $code,
                        "message" => (empty($msg)) ? "Bad Request" : $msg
                    ]));
                }
            case 404: {
                    exit(json_encode([
                        "code" => $code,
                        "message" => (empty($msg)) ? "Not Found" : $msg
                    ]));
                }
            case 405: {
                    exit(json_encode([
                        "code" => $code,
                        "message" => (empty($msg)) ? "Method Not Allowed" : $msg
                    ]));
                }
            default: {
                    exit(json_encode([
                        "code" => 500,
                        "message" => (empty($msg)) ? "Internal Server Error" : $msg
                    ]));
                }
        }
    }
}
