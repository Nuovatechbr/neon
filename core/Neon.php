<?php

namespace Nuovatech\Neon;

use Nuovatech\Neon\Config\Application;
use \Nuovatech\Neon\Http\Router;

/**
 * Classe de gestão do Framework. Deve ser a primeira classe importada para o projeto
 * @author Eduardo Marinho
 * @since 29/09/2022
 * @version 0.2.0.20220929
 */
abstract class Neon
{

    public static $app;

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
        $obRouter = new Router(self::$app->url);

        // Diretório dos arquivos de rotas
        $directory = dir($dir .  "/app/Routes/");

        // Quantidade de arquivos de rotas encontrados
        $qtdRoutes = 0;

        // Percorre o diretório em busca dos mapas de rotas
        while ($file = $directory->read()) {

            if (stripos($file, 'php')) {

                // Incrementa a quantidade de rotas encontradas
                $qtdRoutes += 1;

                // Importa o arquivo de rotas.
                require_once($dir . "/app/Routes/$file");
            }
        }

        if ($qtdRoutes > 0) {

            // Executa a chamada da rotas
            $obRouter->run()->sendResponse();

            // Limpa o conteúdo do BUFFER para evitar erros
            ob_end_flush();
        } else {
            echo Tools::dump([
                "status" => 500,
                "description" => "Não existem arquivos de rota definidos!"
            ], true);
        }
    }

    /**
     * Método de inicialização do framework.
     * @param   string  $dir diretório da aplicação.
     * @param   string  $template diretório do template externo.
     */
    public static function start(string $dir = __DIR__)
    {
        // Cria os diretórios do sistema, caso não existam
        self::diretories($dir);

        // Cria a configuração da aplicação
        self::settings($dir);

        // Define a timezone de acordo com o valor da configuracao
        self::timezone(self::$app->timezone);

        // Inicia a variável de sessão para utilização futura
        if (session_start() != PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Realiza o carregamento das rotas
        self::route($dir);
    }

    /**
     * Método de criação dos diretórios para gerar a aplicação caso não existam
     * @param string $dir diretório raíz da aplicação
     */
    private static function diretories(string $dir)
    {

        // Cria os diretórios de aplicação
        if (!file_exists($dir . "/app")) {
            mkdir("app");
        }

        if (!file_exists($dir . "/app/Controller")) {
            mkdir("app/Controller");
        }
        if (!file_exists($dir . "/app/Interface")) {
            mkdir("app/Interface");
        }
        if (!file_exists($dir . "/app/Model")) {
            mkdir("app/Model");
        }
        if (!file_exists($dir . "/app/Routes")) {
            mkdir("app/Routes");
        }

        // Cria os diretórios de visão
        if (!file_exists($dir . "/public")) {
            mkdir("public");
        }
        if (!file_exists($dir . "/public/pages")) {
            mkdir("public/pages");
        }
        if (!file_exists($dir . "/public/assets/")) {
            mkdir("public/assets");
        }
        if (!file_exists($dir . "/public/assets/css")) {
            mkdir("public/assets/css");
        }
        if (!file_exists($dir . "/public/assets/script")) {
            mkdir("public/assets/script");
        }
    }

    /**
     * Cria o arquivo de configuração da aplicação
     */
    private static function settings($dir)
    {
        // Carrega o caminho do arquivo
        $jsonFile = $dir . "/app/application.json";

        // Verifica se o arquivo de configuração já existe.
        if (!file_exists($jsonFile)) {

            // Cria um arquivo json
            $file = fopen($jsonFile, 'w');

            // Verifica a possibilidade de criar o arquivo
            if ($file == false) die('Não foi possível criar o arquivo.');

            // Inicializa a variável de aplicação
            self::$app = new Application();
            fwrite($file, json_encode(self::$app));
        }

        $app = file_get_contents($jsonFile);
        self::$app = new Application(json_decode($app, true));
    }

    /**
     * Método responsável por carregar o timezone para aplicação
     * @param   string  $place local da zona
     */
    public static function timezone(string $place)
    {
        $place = (empty($place)) ? "America/Bahia" : $place;
        date_default_timezone_set($place);
    }
}
