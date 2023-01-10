<?php

namespace Nuovatech\Neon\Config;

use stdClass;

/**
 * Classe de configuração da aplicação
 */
class Application
{

    public function __construct()
    {
        $this->url();
        $this->dataBase();
    }

    /**
     * Contém as informações de banco de dados da aplicação
     * @var stdClass
     */
    public $dataBase;

    /**
     * Armazena a URL Base do sistema
     * @var string
     */
    public $url = '';

    /**
     * Codificação do sistema
     */
    public $charset = "utf-8";

    /**
     * Cria as configurações para conexão com banco de dados
     */
    private function database()
    {
        $this->dataBase = new stdClass();
        $this->dataBase->driver = '';
        $this->dataBase->name = '';
        $this->dataBase->password = '';
        $this->dataBase->user = '';
    }

    /**
     * Cria a URL base da aplicação
     */
    private function url()
    {
        $path = explode('\\', getcwd());
        $pathSize = count($path);
        $this->url = $path[$pathSize - 1];
    }
}
