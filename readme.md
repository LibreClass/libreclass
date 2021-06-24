# LibreClass

Este projeto tem como objetivo atender às necessidades de instituições, organizar as tarefas de professores e aproximar os alunos de processos relativos à gestão escolar.

## Instalação

Instale o apache:

    $ sudo apt-get install apache2

Instale o php7.4, mysql, composer, etc:

    $ sudo apt-get install php7.4 mysql-server composer
    $ sudo apt-get install php7.4-xml php7.4-mbstring php7.4-curl php7.4-mysql php7.4-zip php-mongodb php-imagick php-pear php-dev
    $ sudo pecl install mongodb
    $ sudo apt-get install libxrender1 libxtst6
    $ sudo apt-get install npm

Modifique o arquivo `apache2/php.ini`, na linha onde há `post_max_size` coloque o tamanho máximo de arquivo em 10M.

Habilite os módulos necessários:

    $ sudo a2enmod rewrite
    $ sudo service apache2 restart

#### Instalação do pacote libssl1.0-dev

Esse pacote é necessário para a criação de relatórios em PDF.

Edite o arquivo em `/etc/apt/sources.list` e adicione ao fim do mesmo:

    deb http://security.ubuntu.com/ubuntu bionic-security main

Então execute:

    sudo apt update && sudo apt-cache policy libssl1.0-dev
    sudo apt-get install libssl1.0-dev

#### Configuração do arquivo `.env`

É necessário criar o arquivo `.env` de acordo com o arquivo `.env.example`, na raiz do projeto, com as informações para conexão ao banco de dados MySQL e configurações para envio de email. Este passo deve ser executado antes de instalar as dependências do projeto com o composer. Exemplo:

    APP_NAME=Laravel
    APP_ENV=local
    APP_KEY=base64:YBnDcTNEtXlTrc6IQBdYT/nL8sZ6HfplsDzJv0lTEZY=
    APP_DEBUG=true
    APP_URL=http://localhost
    ASSET_URL=http://localhost

    LOG_CHANNEL=single

    DB_CONNECTION=mysql
    DB_HOST=localhost
    DB_PORT=3306
    DB_DATABASE=libreclassbeta
    DB_USERNAME=libreclass
    DB_PASSWORD=libreclass

    BROADCAST_DRIVER=log
    CACHE_DRIVER=file
    QUEUE_CONNECTION=sync
    SESSION_DRIVER=file
    SESSION_LIFETIME=120

    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379

    MAIL_DRIVER=smtp
    MAIL_HOST=
    MAIL_PORT=25
    MAIL_USERNAME=
    MAIL_PASSWORD=
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS=
    MAIL_FROM_NAME=

    MAIL_SUPORTE=contato@libreclass.com

    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    PUSHER_APP_CLUSTER=mt1

    MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
    MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

    LOG_SLACK_WEBHOOK_URL=

    MIX_GA_TRACKING_ID=

Execute o composer na raiz do projeto para instalar as dependências necessárias:

    $ composer require mongodb/mongodb --ignore-platform-reqs
    $ composer install

Crie a chave e o secret do jwt

    $ php artisan key:generate
    $ php artisan jwt:secret

Após isto, prepare o banco de dados de acordo com a estrutura fornecida no diretório doc. Você poderá executar o projeto localmente através do comando:

    $ php artisan serve

Defina corretamente as permissões de arquivos, especialmente para diretório storage no projeto. Exemplo, no diretório do projeto, execute:

    $ sudo chown www-data:www-data . -R

## Pós-instalação

#### Criando uma conta institucional

Uma vez que o software está perfeitamente instalado e o banco de dados criado a partir da estrutura indicada em `create-db.sql`, acesse o seu diretório e execute os passos a seguir para criar uma conta do perfil institucional:

###### 1) Pelo terminal, acessar a pasta do projeto e executar o comando:

    $ php artisan tinker

###### 2) Ao abrir o console do artisan, criar uma senha com o método: `Hash::make($senha)`. Exemplo:

    > Hash::make('1234')

###### 3) Copiar a string, a mesma será utilizada para criar o usuário no banco de dados;

    >  $2y$10$tJBmU3RkGHn2TVEYzlu08.rrVJXjScffWHODelcffjHdLWwtFlyS.

###### 4) Abrir o banco de dados pelo terminal:

    $ mysql -u root -p

###### 5) Selecionar o banco de dados do libreclass:

    mysql> use libreclassbeta

###### 6) Criar o usuário instituição (type = I) utilizando a string copiada no passo 3 como password:

    mysql> INSERT INTO `users` (`email`, `password`, `name`, `type`, `gender`, `birthdate`, `uee`, `course`, `formation`, `cadastre`, `city_id`, `street`, `photo`, `enrollment`, `created_at`, `updated_at`) VALUES ('meuemail@email.com', '$2y$10$tJBmU3RkGHn2TVEYzlu08.rrVJXjScffWHODelcffjHdLWwtFlyS.', 'Nome da Instituição', 'I', NULL, NULL, NULL, NULL, '0', 'N', NULL, NULL, '/images/user-photo-default.jpg', NULL, NULL, NULL);

Neste ponto você terá um usuário do tipo instituição. Poderá realizar login com o email `meuemail@email.com` e a senha `1234`.

## Contribuindo

Agradecemos caso deseje contribuir para o projeto!
