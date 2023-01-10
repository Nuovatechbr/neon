<?php

use \Nuovatech\Neon\Http\Response;
use \Nuovatech\Neon\Database\Connection;

// Rota de Error
$obRouter->get('/', [
    function () {
        return new Response(200);
    }
]);
