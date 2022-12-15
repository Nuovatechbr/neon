<?php

namespace Nuovatech\Neon;

use \Nuovatech\Neon\Http\Router;


/**
 * Classe de gestão do Framework. Deve ser a primeira classe importada para o projeto
 * @author Eduardo Marinho
 * @since 29/09/2022
 * @version 0.2.0.20220929
 */
abstract class Neon
{

    /**
     * Método responsável por carregar as variáveis de ambiente do projeto
     * @param  string $dir Caminho absoluto da pasta onde encontra-se o arquivo .env
     * @author WilliamCosta
     * @see https://www.youtube.com/c/WDEVoficial/featured
     */
    public static function environment(string $dir)
    {
        //VERIFICA SE O ARQUIVO .ENV EXISTE
        if (!file_exists($dir . '/.env')) {
            return false;
        }

        //DEFINE AS VARIÁVEIS DE AMBIENTE
        $lines = file($dir . '/.env');
        foreach ($lines as $line) {
            $constant = explode('=', $line);
            define(trim($constant[0]), trim($constant[1]));
        }
    }

    /**
     * Método responsável por iniciar as rotas para o projeto.
     * A definição das URL precisam já terrem sido carregadas antes da chamada deste método.
     * @param   string $dir diretório padrão do projeto
     */
    public static function route(string $dir)
    {

        // Carrega conteúdo para o BUFFER
        ob_start();

        // Variável que chamará as rotas do projeto
        $obRouter = new Router(URL);

        // Diretório dos arquivos de rotas
        $directory = dir($dir .  "/app/Routes/");

        // Percorre o diretório em busca dos mapas de rotas
        while ($file = $directory->read()) {
            if (stripos($file, 'php')) {
                // Importa o arquivo de rotas.
                require_once($dir . "/app/Routes/$file");
            }
        }

        // Executa a chamada da rotas
        $obRouter->run()->sendResponse();

        // Limpa o conteúdo do BUFFER para evitar erros
        ob_end_flush();
    }

    /**
     * Método de inicialização do framework.
     * @param   string  $dir diretório da aplicação.
     * @param   string  $template diretório do template externo.
     */
    public static function start(string $dir = __DIR__)
    {
        // Carrega as variáveis de ambiente
        self::environment($dir);

        // Inicia a variável de sessão para utilização futura
        if (session_start() != PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Carregamento dos controladores
        // self::autoController($dir);

        // Realiza o carregamento das rotas
        self::route($dir);

        print("Hello");
    }
}
