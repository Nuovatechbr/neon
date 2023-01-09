# PHP NEON

FRAMEWORK DE DESENVOLVIMENTO PHP

## UTILIZAÇÃO

Para realizar o carregamento do framework siga o exemplo abaixo:

```php

<?php

// CARREGAMENTO DO AUTOLOAD
require __DIR__."/vendor/autoload.php";

// DEPENDÊNCIAS
use Nuovatech\Neon\Neon;

// CHAMADA NO ARQUIVO INDEX.PHP
Neon::start(__DIR__);