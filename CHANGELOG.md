## [0.1.5.20230126]

### Changed
- [core/Http/Exception.php] : Amplicada lista de status [201, 202, 401, 403, 500];
- [core/Neon.php] : Tratamento aplicado no método Route() para lançar exceção do próprio framework;
- [core/View.php] : Removido método Template(), ficando à cargo do template realizar a implmentação do método.

## [0.1.4.20230120]

### Changed

- [core/View.php] : Removido método Template.


## [0.1.4.20230119]

### Changed

- [core/Http/Exception.php] : Adicinados novos códigos de status.
- [core/Http/Router.php] : Alterado retornos.

## [0.1.3.20230118]

### Changed

- [core/Config/Application.php] : Adicionada propriedade "sessionKey" para ser utilizada na sessão.

## [0.1.2.20230118]

### Add

- [core/Http/Exception.php] : Implementada classe de gerenciamento de exceções http.
- [core/Http/Session.php] : Implementada classe de gerenciamento de sessão.

### Changed

- [core/Config/Application.php] : Adicionada propriedade de timezone para armazenar a timezone da aplicação.
- [core/Neon.php] : 
    - Adicionada chamada do método 'timezone()'.
    - Removido método de carregamento de variável de ambiente por .env
- [core/View] : 
    - Adicionado método de renderização de conteúdo.
    - Melhoria na validação de arquivo no método render;
    - Alterada estrutura da variável global da visão para um StdClass.


### Fixed

- [core/Http/Response.php] : Removida função "mb_convert_encoding" para renderização de página.

## [0.1.1.20230110]

### Changed

- [core/Config/Application.php] : Criado novos métodos para criação de parâmetros de configuração.
- [core/Database/Connection.php] : Alterados parâmetros de configuração do servidor.
- [core/Neon.php] : Alterado carregamento das configurações no gerenciador.

## [0.1.0.20230110]

### Changed

- [core/Neon] : Adicionados métodos de construção do arquivo de configuração da aplicação. 

## [0.0.2.20230108]

### Fixed

- [core/Http/Router.php] : Mensagem de retorno quando a rota ou arquivo não são encontrados.
- [core/Tools.php] : Retorno do método dump.
