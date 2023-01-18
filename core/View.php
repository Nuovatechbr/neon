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
    protected static function getGlobal(string $key, bool $print = false)
    {
        if ($print == true) {
            return self::$global[$key];
        } else {
            echo self::$global[$key];
        }
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
     * @param   string    $url Caminho do arquivo css
     * @param   bool      $place caso seja true, o framework buscará o recurso na própria biblioteca.
     * @param   string    $media Informa o tipo de mídia do css.
     * @author  Eduardo Marinho
     * @version 0.0.20210609
     */
    static public function css(string $url, string $place = '')
    {

        if ($place == true) {
            $path = $_SERVER['REQUEST_SCHEME'] . "://" .  $_SERVER['SERVER_NAME'] . DIRECTORY_SEPARATOR . "neon";
            $url = $path . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR . $url . ".css";
        } else {
            $url = self::getUri() . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR . $url . ".css";
        }

        $media = (!empty($media)) ? "media='$media'" : '';
        echo "<link href='$url' $media rel='stylesheet' type='text/css'>";
    }

    static public function image(string $url, $ext = "png")
    {
        $path = $_SERVER['REQUEST_SCHEME'] . "://" .  $_SERVER['SERVER_NAME'] . DIRECTORY_SEPARATOR . "neon";
        echo "<img src='$url'>";
    }

    /**
     * Método responsável por importar o favicon da página
     * @param   string  $url caminho da imagem
     * @param   string  $type formato da imagem
     */
    static public function favicon(string $url, $type = null)
    {
        $type = (!empty($type)) ? $type : ".ico";
        echo '<link href="' . self::getUri() . 'public/assets/imgs/' . $url . '.' . $type . '" rel="icon" type="img/' . $type . '">';
    }

    /**
     * Método responsável por incluir documento por require
     */
    static public function include(string $path, string $ext = "php")
    {
        require_once("public/pages/" . $path . '.' . $ext);
    }

    /**
     * Método responsável por realizar  a renderização da (view) em um template desejado.
     * @param string $path caminho do arquivo
     * @param string $type formato do arquivo (html, php)
     * @param array $vars variáveis com parâmetros
     */
    static public function template(string $path, array $vars = [], string $type = null)
    {

        // Verifica a extenssão do arquivo que será carregado, o padrão é .php        
        $extension = ($type == null) ? ".php" : '.' . $type;
        $view = "public/pages/" . $path . $extension;

        // Carrega o layout
        $layout = "public/template/layout.php";

        // Atribui os parâmetros para a variável global
        self::$global = $vars;

        // limpa a variável
        unset($vars);

        // Atribui a carga do conteúdo à variável global
        self::$body  = file_exists($view) ? file_get_contents($view) : '';

        // Verica se o conteúdo está vazio, caso esteja carrega a página de erro.
        if (empty(self::$body)) {
            self::$body = file_get_contents("public/pages/status/error-view.php");
            self::$global = [
                "msg" => "Página não encontrada",
                "site" => "Neon:: 404 - Página não encontrada.",
                "status" => 404,
            ];
        }

        ob_start();
        eval("?>" . self::$body  . "<?php");
        self::$body  = ob_get_clean();
        require $layout;
    }

    static public function renderJson(string $view, string $type = null, $vars)
    {

        $extension = ($type != null) ? '.' . $type : '.php';
        $file = "public/pages/" . $view . $extension;

        if (is_file($file)) {

            ob_start();
            include $file;

            // Neon::debug($vars, true);

            // arquivo obtido
            $content = ob_get_clean();

            if (!is_array($vars)) {
                $vars = get_object_vars($vars);
            }

            // Chaves do array de variáveis
            $keys = array_keys($vars);
            $keys = array_map(function ($key) {
                return "{{" . $key . "}}";
            }, $keys);

            $content = str_replace($keys, array_values($vars), $content);

            // Converte em entidades html
            $content = htmlentities($content);

            // Decodifica em entidades
            $content = html_entity_decode($content);

            return array(
                "body"  => $content,
                "msg" => "Visão encontrada!",
                "status" => 200
            );
            // return false;
        } else {
            return array(
                "msg" => "Visão não encontrada!",
                "status" => 404
            );
        }
    }

    /**
     * Método de inclusão da TAG SCRIPT em VIEWS.
     * @param string $url Caminho do arquivo js
     * @param bool  $defer Habilita o carregamento posterior do script
     * @see https://www.w3schools.com/tags/att_script_defer.asp
     * @author Eduardo Marinho
     * @version 0.0.20210609
     */
    static public function script($url, $framework = false, $defer = null)
    {
        if ($framework == true) {

            $path = $_SERVER['REQUEST_SCHEME'] . "://" .  $_SERVER['SERVER_NAME'] . DIRECTORY_SEPARATOR . "neon";
            $url = $path . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "script" . DIRECTORY_SEPARATOR . $url . ".js";
        } else {
            $url = self::getUri() . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "script" . DIRECTORY_SEPARATOR . $url . ".js";
        }

        if ($defer) {
            echo "<script defer src='$url'>" . "</script>";
        } else {
            echo "<script src='$url'>" . "</script>";
        }
    }

    static public function http(int $status)
    {
        if ($status == 404) {
            View::render("status/error");
        }

        if ($status == 405) {
            View::render("http/status", [
                "msg" => "Método Não Permitido",
                "site" => "Neon:: 405",
                "status" => 405
            ]);
        }

        if ($status == 424) {
            echo "Failed Dependency";
        }
    }

    /**
     * Método de retorno da url absoluta da aplicação
     * @param   string $caminho, complemento do endereço
     */
    public static function link($caminho = null, bool $retun = false)
    {
        // Obtem o endereço da raíz do index
        $urix = explode('/', $_SERVER['PHP_SELF']);

        // Remove o index.php do array
        unset($urix[count($urix) - 1]);

        // Remonta o caminho utilizando a URL editada
        if ($retun == true) {
            return $_SERVER['REQUEST_SCHEME'] . "://" .  $_SERVER['SERVER_NAME'] . implode('/', $urix) . '/' . $caminho;
        }
        echo  $_SERVER['REQUEST_SCHEME'] . "://" .  $_SERVER['SERVER_NAME'] . implode('/', $urix) . '/' . $caminho;
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
     * Método   responsável por realizar  a renderização da (view)  desejada.
     * @param   string  $view   caminho do arquivo
     * @param   string  $type   formato do arquivo (html, php) 
     * @param   array   $vars   variáveis que serão passadas para a view
     */
    static public function render(string $view, array $vars = [], string $type = null)
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

    static private function getUri()
    {
        // Obtem o endereço da raíz do index
        $urix = explode('/', $_SERVER['PHP_SELF']);

        // Remove o index.php do array
        unset($urix[count($urix) - 1]);

        // Remonta o caminho utilizando a URL editada
        return  $_SERVER['REQUEST_SCHEME'] . "://" .  $_SERVER['SERVER_NAME'] . implode('/', $urix) . '/';
    }
}
