<?php

use \Nuovatech\Neon\Http\Response;

// Rota de Error
$obRouter->get('/', [
    function () {
        return new Response(200);
    }
]);
