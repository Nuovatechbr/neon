<?php

namespace Nuovatech\Neon\Util;

/**
 * @description: Possui os métodos e propriedades genéricas de todas as classes filhas
 * @author Eduardo Marinho
 * @since   05/06/2021
 *  
 */
class Model
{
    /**
     * @desc: método de construção da classe
     * @param   $properties: espera um array espelho das propriedades da classe
     */
    public function __construct($properties = null)
    {
        if (is_array($properties)) {

            foreach ($properties as $key => $value) {

                $method = "set" . ucwords($key);

                if (method_exists($this, $method)) {

                    $this->$method($value);
                    continue;
                } else if (property_exists($this, $key)) {

                    $this->$key = $value;
                    continue;
                } else {

                    $keyCamelCase = $this->_snakeToCamel($key);
                    $method = "set" . ucwords($this->_snakeToCamel($keyCamelCase));
                    if (method_exists($this, $method)) {
                        $this->$method($value);
                        continue;
                    } else if (property_exists($this, $keyCamelCase)) {
                        $this->$keyCamelCase = $value;
                        continue;
                    }
                }
            }
        }
    }

    /**
     * @desc: realiza a conversão do valor passado para camelCase.
     * @param   $val, string
     */
    private function _snakeToCamel($val)
    {
        $val = str_replace(' ', '', ucwords(str_replace('_', ' ', $val)));
        $val = strtolower(substr($val, 0, 1)) . substr($val, 1);
        return $val;
    }
}
