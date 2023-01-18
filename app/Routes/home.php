<?php

use \Nuovatech\Neon\Http\Response;
use \Nuovatech\Neon\View;

$obRouter->get('/', [
    function () {
        return new Response(200, View::render("teste", [
                "nome" => "Carlos Eduardo Marinho"
            ])
        );
    }
]);
