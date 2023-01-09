<?php

namespace Nuovatech\Neon;

use Nuovatech\Neon\Http\Response;

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
    public static function dump($elem, $json = false)
    {
        if ($json == true) {
            header('Content-Type: application/json');
            echo die(json_encode($elem));
        } else {
            echo die("<pre>" . print_r($elem, true) . "</pre>");
        }
    }
}
