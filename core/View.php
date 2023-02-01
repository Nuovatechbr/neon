<?php

namespace Nuovatech\Neon;

use Directory;
use Exception;
use \Nuovatech\Neon\Http\Exception as HttpException;
use stdClass;

/**
 * Classe de gestão dos recursos das views da aplicação.
 * @author Eduardo Marinho
 * @since  18/06/2021
 * @version 0.0.2.20220203
 */
abstract class View
{

    /**
     * Armazena o conteúdo da visão
     * @var string
     */
    static private $body;

    /**
     * @var
     * Armazena um array com as variáveis utilizadas no controle da visão.
     */
    static private $global;

    /**
     * Método responsável por devolver o corpo da página para a estrutura do layout.
     */
    protected static function getBody()
    {
        echo self::$body;
    }

    /**
     * Método responsável por devolver como string o valor guardado na variável global da visão
     * @param   string $key chave do array.
     */
    protected static function getGlobal(string $key)
    {
        if (isset(self::$global[$key])) {
            return self::$global[$key];
        }
        return '';
    }

    protected static function setBody(mixed $body)
    {
        self::$body = $body;
    }

    /**
     * Método responsável por incluir valor na variável global
     * @param   mix $global armazena multiplos dados. 
     */
    protected static function setGlobal($global)
    {
        self::$global = $global;
    }

    /**
     * Método de inclusão da TAG LINK em VIEWS.
     * @param   string    $path Caminho do arquivo css
     * @author  Eduardo Marinho
     * @version 0.0.20210609
     */
    public static function css(string $path)
    {
        $path = self::directory() . "public/assets/css/$path.css";
        print_r("<link href='$path' rel='stylesheet' type='text/css'> \r");
    }

    /**
     * Renderiza o conteúdo para a página
     * @param string $content
     * @return string
     */
    public static function content(string $content = '')
    {
        return $content;
    }

    /**
     * Método responsável por importar o favicon da página
     * @param   string  $url caminho da imagem
     * @param   string  $type formato da imagem
     */
    public static function favicon(string $url, $type = null)
    {
        $type = (!empty($type)) ? $type : ".ico";
    }

    /**
     * Método responsável por incluir documento por require
     */
    public static function include(string $path, string $ext = "php")
    {
        require_once("public/pages/" . $path . '.' . $ext);
    }

    /**
     * Load the module js
     * @param string $path Path or URL the module script file.
     * @see Load .js files
     */
    public static function module(string $path)
    {
        $path = $_SERVER['REQUEST_SCHEME'] . "://" .  $_SERVER['SERVER_NAME'] .  $_SERVER["REQUEST_URI"] . "public/assets/script/$path.mjs";
        print_r("<script src='$path' type='module'></script> \r");
    }

    /**
     * Método   responsável por realizar  a renderização da (view)  desejada.
     * @param   string  $view   caminho do arquivo
     * @param   string  $type   formato do arquivo (html, php) 
     * @param   array   $vars   variáveis que serão passadas para a view
     */
    public static function render(string $view, array $vars = [], string $type = null)
    {
        // Verifica a extensão do arquivo que será carregado, o padrão é .php
        $extension = ($type == null) ? ".php" : '.' . $type;

        // Obtem o caminho do arquivo
        $file = "public/pages/" . $view . $extension;

        // Verifica a existência do arquivo
        if (!file_exists($file)) {
            HttpException::response(404);
        }

        // Acesso a variáveis globais
        self::$global = new stdClass();
        foreach ($vars as $param => $value) {
            self::$global->$param = $value;
        }

        unset($vars);

        // Carrega o arquivo 
        $content = file_get_contents($file);

        // Realiza a renderização da visão
        eval("?> $content <?php ");
    }

    /**
     * Load the internal template script.
     * @param string $name Name of module'll be load.
     */
    public static function script($name)
    {
        $path = $_SERVER['REQUEST_SCHEME'] . "://" .  $_SERVER['SERVER_NAME'] .  $_SERVER["REQUEST_URI"] . "public/assets/script/$name.js";
        print_r("<script src='$path'></script> \r");
    }

    /**
     * Load the title site name
     */
    public static function title()
    {
        if (!empty(self::getGlobal("site"))) {
            print_r(self::getGlobal("site"));
        }
        print_r("Teste");
    }

    /**
     * Get the return of application directory
     * @return string directory
     */
    private static function directory()
    {
        // Obtem o endereço da raíz do index
        $urix = explode('/', $_SERVER['PHP_SELF']);

        // Remove o index.php do array
        unset($urix[count($urix) - 1]);

        // Remonta o caminho utilizando a URL editada
        return  $_SERVER['REQUEST_SCHEME'] . "://" .  $_SERVER['SERVER_NAME'] . implode('/', $urix) . '/';
    }

    // private static function directory()
    // {
    //     $serverPath = $_SERVER['REQUEST_SCHEME'] . "://" .  $_SERVER['SERVER_NAME'];
    //     $appPath = explode('/', $_SERVER['PHP_SELF']);
    //     $subPathSize = count($appPath);
    //     if ($subPathSize > 1) {
    //         unset($appPath[$subPathSize - 1]);
    //     }
    //     return $serverPath . implode('/', $appPath) . '/';
    // }
}
