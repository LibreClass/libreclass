# LibreClass

[![GitHub release](https://img.shields.io/badge/Vers%C3%A3o-0.2.0-green.svg)](https://github.com/Sysvale/libreclass)

Este projeto tem como objetivo atender às necessidades de instituições, organizar as tarefas de professores e aproximar os alunos de processos relativos à gestão escolar.

## Instalação

Instale o apache:

    $ sudo apt-get install apache2

Instale o php5, mysql, composer, etc:

    $ sudo apt-get install apache2 php5 php5-mcrypt php5-curl php5-imagick php5-mysql
    $ sudo apt-get install mysql-server

Habilite os módulos necessários:

    $ a2enmod rewrite
    $ php5enmod mcrypt
    $ service apache2 restart

Instale o composer:

    $ php -r "readfile('https://getcomposer.org/installer');" | php
    $ cp composer.phar /bin/composer

Execute o composer na raiz do projeto:

    $ composer install

Após isto, prepare o banco de dados de acordo com a estrutura fornecida no diretório doc. Você poderá executar o projeto localmente através do comando:

    $ php artisan serve

Defina corretamente as permissões de arquivos, especialmente para diretório storage no projeto.

## Contribuindo

Agradecemos caso deseje contribuir para o projeto!
