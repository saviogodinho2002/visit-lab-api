# Gerenciamento dos Laboratórios - UFOPA

Este projeto tem como objetivo fornecer um sistema de gerenciamento de laboratórios para a UFOPA (Universidade Federal do Oeste do Pará). Para executar este projeto, você precisará das seguintes dependências:

## Dependências PHP

Você precisará configurar um ambiente PHP no seu sistema. Caso esteja utilizando o Windows, uma opção popular é o [XAMPP](https://www.apachefriends.org/index.html). Certifique-se de que o PHP esteja instalado e funcionando corretamente.

### Composer

O [Composer](https://getcomposer.org/) é uma ferramenta de gerenciamento de dependências para o PHP. Você pode instalá-lo seguindo as instruções no site oficial.


### Mysql

O [Mysql](https://www.mysql.com/) é o SGDB usado para salvar os dados. Instale ele através do site oficial.


## Instalação e Configuração

1. Clone este repositório para o seu sistema;
2. Navegue até o diretório do projeto;
3. Execute o Composer para instalar as dependências do Laravel;
4. Configure seu ambiente de desenvolvimento de acordo com suas necessidades específicas, como o banco de dados, servidor web, etc;
5. Inicie o servidor web (por exemplo, Apache) e certifique-se de que o PHP esteja configurado corretamente.

## URLS

Devem ser declaradas no .env as urls para a API da UFOPA e para a autenticação do SIGAA. Esses dados devem ser fornecidos pelo CTIC, menos o de autenticação do SIGAA.

API_SIGAA_BASE_URL=[api da ufopa]
API_KEY_SIGAA=[chave de api]
API_AUTHENTICATION_SIGAA=https://autenticacao.ufopa.edu.br/

## Usuario Administrador
Em [seeders](database%2Fseeders), na seed [CreateRolesAndAdminSeeder.php](database%2Fseeders%2FCreateRolesAndAdminSeeder.php) coloque o seu usuário do SIGAAAna criação da PreRegistration pra criar o primeiro adminstrador do sistema.

Após isso execute

php artisan migrate

php artisan DB:seed

## Documentos
Os documentos do projeto estão em [docs](docs).
## Licença

Este projeto está licenciado sob a Licença MIT - consulte o arquivo [LICENSE](LICENSE) para obter detalhes.
