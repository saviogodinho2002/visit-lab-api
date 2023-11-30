# Gerenciamento dos Laboratórios - UFOPA

Este projeto tem como objetivo fornecer um sistema de gerenciamento de laboratórios para a UFOPA (Universidade Federal do Oeste do Pará). Para executar este projeto, você precisará das seguintes dependências:

## Detalhe
Não foi feito as funcionalidades para Agendamento do laboratório.

## Dependências PHP

Você precisará configurar um ambiente PHP no seu sistema. Caso esteja utilizando o Windows, uma opção popular é o [XAMPP](https://www.apachefriends.org/index.html). Certifique-se de que o PHP esteja instalado e funcionando corretamente.

### Composer

O [Composer](https://getcomposer.org/) é uma ferramenta de gerenciamento de dependências para o PHP. Você pode instalá-lo seguindo as instruções no site oficial.


### Mysql

O [Mysql](https://www.mysql.com/) é o SGDB usado para salvar os dados. Instale ele através do site oficial.

### Node
A versão LTS no  [Node](https://nodejs.org/en) pode ser baixado diretamente pelo site. O node é necessário porque tem a interface web usando o framework [Vue](https://vuejs.org/)

### Ngrok
O [ngrok](https://dashboard.ngrok.com/get-started/setup) tem instruções de instalações no site. 
Para funcionar bem no laravel deve seguir os passos dessa [url](https://wallacemaxters.com.br/blog/60/utilizando-ngrok-com-laravel), MAS DEIXE http://localhost:8000 EM APP_URL. 



## Instalação e Configuração

1. Clone este repositório para o seu sistema;
2. Navegue até o diretório do projeto;
3. Execute o Composer para instalar as dependências do Laravel;
```
composer install
```
4. Configure seu ambiente de desenvolvimento de acordo com suas necessidades específicas, como o banco de dados, servidor web, etc;
5. Execute o npm para instalar as dependência do node;
 ```
npm install
npm run build
```
6. Inicie o servidor web (por exemplo, Apache) e certifique-se de que o PHP esteja configurado corretamente.

## URLS

Devem ser declaradas no .env as urls para a API da UFOPA e para a autenticação do SIGAA. Esses dados devem ser fornecidos pelo CTIC, menos o de autenticação do SIGAA.

API_SIGAA_BASE_URL=[api da ufopa]

API_KEY_SIGAA=[chave de api]

API_AUTHENTICATION_SIGAA=https://autenticacao.ufopa.edu.br/

## Usuario Administrador
Em [seeders](database%2Fseeders), na seed [CreateRolesAndAdminSeeder.php](database%2Fseeders%2FCreateRolesAndAdminSeeder.php) coloque o seu usuário do SIGAAAna criação da PreRegistration pra criar o primeiro adminstrador do sistema.

Após isso execute:
```shell
php artisan migrate

php artisan DB:seed
```
Depois, rode a aplicação com:
```shell
php artisan serve
```
Abra no navegador e entre com as credenciais do SIGAA. O primeiro usuário administrador deve aceitar o seu pré registro para criar outras aplicações e permitir o uso via API.

## Problema
INFELIZMENTE, se abrir a interface web do LARAVEL através do ngrok não vai funcionar, abra no localhost mesmo (colocando APP_URL como http://localhost:8000). Tentei resolver, não consegui.
## Documentos
Os documentos do projeto estão em [docs](docs). Na pasta [swagger](public%2Fswagger) você encontra [swagger.json](public%2Fswagger%2Fswagger.json) onde está documentada a API.
## Licença

Este projeto está licenciado sob a Licença MIT - consulte o arquivo [LICENSE](LICENSE) para obter detalhes.
