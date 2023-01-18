<?php

namespace Nuovatech\Neon\Config;

use stdClass;

/**
 * Model class to settings the application. It's autoloaded when the Neon is started.
 * It create a jsonfile contain the basical settings.
 * @author Eduardo Marinho
 */
class Application
{

    /**
     * Auto construct the class.
     */
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
            $this->dataBase();
            $this->url();
            $this->sessionKey();
        }
    }

    /**
     * Contém as informações de banco de dados da aplicação
     * @var stdClass
     */
    public $database;

    /**
     * Stored a sessionkey to application
     * @var string
     */
    public $sessionKey;

    /**
     * Armazena o valor de timezone para a aplicação
     * @var string
     */
    public $timezone = '';

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
     * Create an object with properties to define a database specifies.
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
     * Get the true directory of application.
     */
    private function url()
    {
        $path = explode('\\', getcwd());
        $pathSize = count($path);
        $this->url = $path[$pathSize - 1];
    }

    /**
     * Define the session key. If it don't defined 'll used the name of directory application.
     */
    private function sessionKey()
    {
        $this->sessionKey = $this->url;
    }
}
