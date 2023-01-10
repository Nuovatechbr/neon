<?php

namespace Nuovatech\Neon\Config;

use Nuovatech\Neon\Tools;
use stdClass;

/**
 * Classe de configuração da aplicação
 */
class Application
{

    public function __construct($params = null)
    {
        if ($params) {

            foreach ($params as $key => $value) {

                if (!is_array($value)) {
                    $this->$key = $value;
                } else {
                    $this->$key = new stdClass();
                    foreach ($value as $subKey => $subValue) {
                        $this->$key->$subKey = $subValue;
                    }
                }
            }
        } else {
            $this->url();
            $this->dataBase();
        }
    }

    /**
     * Contém as informações de banco de dados da aplicação
     * @var stdClass
     */
    public $database;

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
        $this->database = new stdClass();
        $this->database->charset = '';
        $this->database->driver = '';
        $this->database->host = '';
        $this->database->name = '';
        $this->database->password = '';
        $this->database->port = '';
        $this->database->user = '';
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
