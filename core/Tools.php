<?php

namespace Nuovatech\Neon;

/**
 * Classe de apoio à desenvolvimento, possuí métodos para depuração ou testes
 */
abstract class Tools
{

    /**
     * Método de despejo de um objeto ou array, imprime na tela em formato TAG <PRE>
     * @param mix $elem
     * @param bool $json  Caso passe true, o retorno será dado em um objeto json
     * @return json
     */
    public static function dump($elem, $stop = false)
    {
        if(!$stop) {
            die(json_encode($elem));
        }
        header('Content-Type: application/json');
        echo die(json_encode($elem));
    }

    /**
     * Método de retorno de objeto json.
     * @param espera um
     */
    public static function json($elem, $return = true)
    {
    }
}
