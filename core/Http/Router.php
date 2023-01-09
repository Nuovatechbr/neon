<?php

namespace Nuovatech\Neon\Http;

use \Exception;
use \Closure;
use \ReflectionFunction;
use \Nuovatech\Neon\Neon;
use \Nuovatech\Neon\Http\Response;
use \Nuovatech\Neon\Http\Request;
use \Neon\Core\Http\Middleware\Queue;
use Nuovatech\Neon\Tools;
use \Nuovatech\Neon\View;
use Reflection;

class Router
{

    /**
     * URL completa do projeto (raiz)
     * @var string
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix = '';

    /**
     * Índice de rotas
     * @var array
     */
    private $routes = [];

    /**
     * Instância de Request
     * @var Request
     */
    private $request;

    /**
     * Método responsável por iniciar a classe
     * @param string $url
     */
    public function __construct($url)
    {
        $this->request = new Request();
        $this->url     = $url;
        $this->setPrefix();
    }

    /**
     * Método responsável por definir o prefixo das rotas
     */
    private function setPrefix()
    {
        // Informações da URl atual
        $parseUrl = parse_url($this->url);

        // Define o prefixo
        $this->prefix = $parseUrl["path"] ?? '';
    }

    /**
     * Método responsável por adicionar uma rota na classe
     * @param   string  $method
     * @param   string  $route
     * @param   array   $params
     */
    private function addRoute($method, $route, $params = [])
    {
        // Validação dos parâmetros
        foreach ($params as $key => $value) {

            if ($value instanceof Closure) {
                $params["controller"] = $value;
                unset($params[$key]);
                continue;
            }
        }

        // Middlewares das rotas
        $params["middlewares"] = $params["middlewares"] ?? [];

        // Variáveis da rota
        $params["variables"] = [];

        // Padrão de validação das variáveis das rotas
        $patternVariable = '/{(.*?)}/';

        if (preg_match_all($patternVariable, $route, $matches)) {
            $route               = preg_replace($patternVariable, '(.*?)', $route);
            $params["variables"] = $matches[1];
        }

        // Padrão de validação da URL
        $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

        // Adiciona a rota dentro da classe
        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * Método responsável por definir uma rota de GET
     * @param   string  $route
     * @param   array   $params
     */
    public function get($route, $params = [])
    {
        return  $this->addRoute("GET", $route, $params);
    }

    /**
     * Método responsável por definir uma rota de POST
     * @param   string  $route
     * @param   array   $params
     */
    public function post($route, $params = [])
    {
        return  $this->addRoute("POST", $route, $params);
    }

    /**
     * Método responsável por definir uma rota de PUT
     * @param   string  $route
     * @param   array   $params
     */
    public function put($route, $params = [])
    {
        return  $this->addRoute("PUT", $route, $params);
    }

    /**
     * Método responsável por definir uma rota de DELETEs
     * @param   string  $route
     * @param   array   $params
     */
    public function delete($route, $params = [])
    {
        return  $this->addRoute("DELETE", $route, $params);
    }

    /**
     * Método responsável por retornar a URI desconsiderando o prefixo
     * @return  string
     */
    private function getUri()
    {
        // URI DA REQUEST
        $uri = $this->request->getUri();

        // Fatiar a URI com o prefixo
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];
        return end($xUri);
    }

    /**
     * Método responsável por retornar os dados da rota atual
     * @return array
     */
    private function getRoute()
    {

        //URI
        $uri = $this->getUri();

        // METHOD
        $httpMethod = $this->request->getHttpMethod();

        //Validar as rotas
        foreach ($this->routes as $patternRoute => $methods) {

            // Verifica se a URI bate com o padrão
            if (preg_match($patternRoute, $uri, $matches)) {

                //Verifica o método
                if (isset($methods[$httpMethod])) {

                    // Remove a primeira posição, não necessária
                    unset($matches[0]);

                    // Variáveis processadas
                    $keys = $methods[$httpMethod]["variables"];
                    $methods[$httpMethod]["variables"] = array_combine($keys, $matches);
                    $methods[$httpMethod]["variables"]["request"] = $this->request;

                    // Retorno dos parâmetros da rota
                    return $methods[$httpMethod];
                }
                View::http(405);
                throw new Exception("Método não permitido", 405);
            }
        }
        // URL não encontrada
        throw new Exception("File or Route don't be founded!", 404);
    }

    /**
     * Método responsável por executar a rota atual
     * @return Response 
     */
    public function run()
    {
        try {

            // Obtem a rota atual
            $route = $this->getRoute();

            // Verifica o controlador
            if (!isset($route["controller"])) {
                throw new Exception("Controlador não encontrado", 500);
            }

            // Argumentos da função
            $args = [];

            // Reflexion 
            $reflection =  new ReflectionFunction($route["controller"]);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route["variables"][$name] ?? '';
            }

            //Retorna a execução das rotas
            // return (new Queue(
            //     $route['middlewares'],
            //     $route['controller'],
            //     $args
            // ))->next($this->request);

            // Retorna a execução da função
            return call_user_func_array($route["controller"], $args);
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }
}
