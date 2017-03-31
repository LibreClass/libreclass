# LibreClass

![GitHub release](https://img.shields.io/badge/Vers%C3%A3o-1.2.0-green.svg)

Este projeto tem como objetivo atender às necessidades de instituições, organizar as tarefas de professores e aproximar os alunos de processos relativos à gestão escolar.

## Instalação

Instale o apache:

    $ sudo apt-get install apache2

Instale o php5, mysql, composer, etc:

    $ sudo apt-get install apache2 php5 php5-mcrypt php5-curl php5-imagick php5-mysql
    $ sudo apt-get install mysql-server

Modifique o arquivo `apache2/php.ini`, na linha onde há `post_max_size` coloque o tamanho máximo de arquivo em 10M.

Habilite os módulos necessários:

    $ a2enmod rewrite
    $ php5enmod mcrypt
    $ service apache2 restart

#### Configuração do arquivo `.env.php`

É necessário criar o arquivo `.env.php` de acordo com o arquivo `.env.php.example`, na raiz do projeto, com as informações para conexão ao banco de dados MySQL e configurações para envio de email. Este passo deve ser executado antes de instalar as dependências do projeto com o composer. Exemplo:

    <?php

        return array(

        // Database
        'DB_HOST'     => 'localhost',
        'DB_DATABASE' => 'libreclass-beta',
        'DB_USERNAME' => 'libreclass',
        'DB_PASSWORD' => 'libreClass1beta!',

        // Email
        'EMAIL_DRIVER'  => 'smtp',
        'EMAIL_HOST'    => 'mail.libreclass.com',
        'EMAIL_PORT'    => 25,
        'EMAIL_FROMADD' => 'contato@libreclass.com',
        'EMAIL_FROMNAM' => 'LibreClass',
        'EMAIL_ENC'     => 'tls',
        'EMAIL_UNAME'   => 'contato@libreclass.com',
        'EMAIL_PASS'    => 'SECRET'

    );

Instale o composer:

    $ php -r "readfile('https://getcomposer.org/installer');" | php
    $ cp composer.phar /bin/composer

Execute o composer na raiz do projeto para instalar as dependências necessárias:

    $ composer install

Após isto, prepare o banco de dados de acordo com a estrutura fornecida no diretório doc. Você poderá executar o projeto localmente através do comando:

    $ php artisan serve

Defina corretamente as permissões de arquivos, especialmente para diretório storage no projeto. Exemplo, no diretório do projeto, execute:

    $ sudo chown www-data:www-data . -R

## Pós-instalação

#### Criando uma conta institucional

Uma vez que o software está perfeitamente instalado, acesse o seu diretório e execute os passos a seguir para criar uma conta do perfil institucional:

###### 1) Pelo terminal, acessar a pasta do projeto e executar o comando:

    $ php artisan tinker

###### 2) Ao abrir o console do artisan, criar uma senha com o método: `Hash::make($senha)`. Exemplo:

    > Hash::make('1234')

###### 3) Copiar a string, a mesma será utilizada para criar o usuário no banco de dados;

    >  $2y$10$Azi/NDbx8WrjAsq0q9VMNeRKtUzoE4QRZOqXu/nQWsqocFXVKOQhu

###### 4) Abrir o banco de dados pelo terminal:

    $ mysql -u root -p

###### 5) Selecionar o banco de dados do libreclass:

    mysql> use libreclass-beta

###### 6) Criar o usuário instituição (type = I) utilizando a string copiada no passo 3 como password:

    mysql> INSERT INTO `Users` (`email`, `password`, `name`, `type`, `gender`, `birthdate`, `uee`, `course`, `formation`, `cadastre`, `idCity`, `street`, `photo`, `enrollment`, `created_at`, `updated_at`) VALUES ('meuemail@email.com', '$2y$10$Azi/NDbx8WrjAsq0q9VMNeRKtUzoE4QRZOqXu/nQWsqocFXVKOQhu', 'Nome da Instituição', 'I', NULL, NULL, NULL, NULL, '0', 'N', NULL, NULL, '/images/user-photo-default.jpg', NULL, NULL, NULL);

Neste ponto você terá um usuário do tipo instituição. Poderá realizar login com o email `meuemail@email.com` e a senha `1234`.

## Contribuindo

Agradecemos caso deseje contribuir para o projeto!
