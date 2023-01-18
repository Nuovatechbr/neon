<?php

namespace Nuovatech\Neon;

use stdClass;

/**
 * Classe de gerenciamento de sessão
 * @author  Eduardo Marinho
 * @since   2022-02-02 21:36
 */
abstract class Session
{

    /**
     * Método de encerramento da sessão
     * @param   string $key Chave da sessão
     */
    public static function close(string $key = null)
    {
        // Limpa a sessão
        unset($_SESSION[$key]);

        session_unset();

        // // Finaliza a sessão
        session_destroy();
    }

    /**
     * Método de verificação da inicialização de uma sessão.
     * @param string $key chave da sessão.
     * @return bool
     */
    static public function check(string $key)
    {
        return (isset($_SESSION[$key])) ? true : false;
    }

    /**
     * Método responsável por retornar o corpo da sessão
     */
    static public function getBody(string $key)
    {
        if (self::check($key)) {
            return $_SESSION[$key]->body;
        }
    }

    /**
     * Método utilizado para verificar a origem da sessão.
     * Por meio desse método garante-se que o usuário que está acessando o conteúdo é o mesmo que encontra-se logado.
     * @param   string  $key nome da sessão;
     * @return  bool true em caso de verdadeira e falso caso contrário.
     */
    static public function origin(string $key)
    {
        $session = $_SESSION[$key];
        $token = md5('seg' . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        return ($session->id == $token) ? true : false;
    }

    static public function remove(string $key, string $value)
    {
        // Verifica
        if (key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]->$value);
        }
    }

    /**
     * Método de inicialização da sessão.
     * @param   string  $key nome da chave da sessão;
     * @param   int     $expires tempo de vida da sessão;
     * @param   misc    $obj recebe qualquer objeto, classe ou array que deseja guardar na sessão
     */
    static public function start(string $key, $obj = null, int $expires = 1)
    {

        // Verificando estado da sessão
        if (!isset($_SESSION[$key])) {

            // Finaliza as sessões anteriores
            Session::close();

            // Define o tempo da sessãos
            session_cache_expire($expires);

            // Inicia a sessão
            session_start();

            // Criação de classe padrão para gerenciar sessão
            $session = new stdClass();
            $session->id = session_id();
            $session->begin = date('Y/m/d H:i:s');
            $session->body = $obj;

            $_SESSION[$key] = $session;
        }
    }
}
