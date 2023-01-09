<?php

namespace Nuovatech\Neon\Http;

/**
 * Classe de tratamento das respostas HTTP
 * @author  Eduardo Marinho
 * @since   19/09/2021
 */
class Response
{

    /**
     * Código do Status HTTP
     * @var integer
     */
    private $httpCode = 200;

    /**
     * Cabeçalho do Resonse
     * @var array
     */
    private $headers = [];

    /**
     * Tipo do conteúdo que está sendo retornado
     * @var string
     */
    private $contentType = "text/html";

    /**
     * Conteúdo do Response
     * @var mix
     */
    private $content;

    /**
     * Método responsável por iniciar a classe e definir os valores
     * @param   integer $httpCode Código de Status HTTP
     * @param   mixed   $content Conteúdo que será carregado
     * @param   string  $contentType Formato do conteúdo
     */
    public function __construct($httpCode, $content = '', $contentType = "text/html")
    {
        $this->httpCode = $httpCode;
        $this->content  = $content;
        $this->setContentType($contentType);
    }

    /**
     * Método responsável por alterar o content type do response
     * @param   string  $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Método responsável por adicionar um registro no cabeçalho do response
     * @param   string  $key
     * @param   string  $value
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * Método responsavel por enviar a resposta para o usuário
     */
    public function sendResponse()
    {
        // Envia os Headers 
        $this->sendHeaders();

        // Imprime o conteúdo
        switch ($this->contentType) {
            case "text/html": {
                    echo $this->content;
                    exit;
                }
            case "application/json": {
                    $content  = mb_convert_encoding($this->content, "UTF-8");
                    echo json_encode($content);
                    exit;
                }
        }
    }

    /**
     * Método responsável por enviar os headers pra o navegador
     */
    private function sendHeaders()
    {
        // Status
        http_response_code($this->httpCode);

        // Envia os headers
        foreach ($this->headers as $key => $value) {
            header($key . ':' . $value);
        }
    }
}
