<?php

namespace Nuovatech\Neon\Http;

/**
 * Classe de tratamento das requisições HTTP
 * @author  Eduardo Marinho
 * @since   19/09/2021
 * @version 0.0.1.20210919
 */
class Request
{
    /**
     * Método HTTP da requisição
     * @var string
     */
    private $httpMethod;

    /**
     * URI da página
     * @var string
     */
    private $uri;

    /**
     * Parâmetros da URL ($_GET)
     * @var string
     */
    private $queryParams = [];

    /**
     * Variáveis recebidas no POST da página ($_POST)
     * @var array
     */
    private $postVars = [];


    /**
     * Váriáveis recebidas no PUT da página ($_PUT)
     * @var array
     */
    private $putVars = [];

    /**
     * Cabeçalho da requisição
     * @var array
     */
    private $headers = [];

    /**
     * Construtor da classe
     */
    public function __construct()
    {
        $this->httpMethod   = $_SERVER["REQUEST_METHOD"] ?? '';
        $this->uri          = $_SERVER["REQUEST_URI"] ?? '';
        $this->queryParams  = $_GET ?? [];
        $this->postVars     = $_POST ?? [];
        $this->putVars      = $_PUT ?? [];
        $this->headers      = getallheaders();
    }

    /**
     * Método responsável por retornar o método HTTP da requisição
     * @return  string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * Método responsável por retornar a URI da requisição
     * @return  string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Método responsável por retornar os parâmetros da requisição
     * @return  string
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Método responsável por retornar as variáveis POST da requisição
     * @return  string
     */
    public function getPostVars()
    {
        return $this->postVars;
    }

    /**
     * Método responsável por retornar as variáveis PUT da requisição
     */
    public function getPutVars()
    {
        return $this->putVars;
    }

    /**
     * Método responsável por obter os headers da requisição
     * @return  string
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
