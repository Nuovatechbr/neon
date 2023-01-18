## [0.1.2.20230118]

### Add

- [core/Session.php] : Implementada classe de gerenciamento de sessão.

### Changed

- [core/Config/Application.php] : Adicionada propriedade de timezone para armazenar a timezone da aplicação.
- [core/Neon.php] : Adicionada chamada do método 'timezone()'.


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
