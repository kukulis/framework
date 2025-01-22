# project

Experimental project to learn symfony + jwt


# docker

## docker network

    docker network create symf-network

## rebuild image

    docker build -t symf/symf-app .docker/app


## install php application libs

    cd application
    cp .env.example .env

From inside docker container:

    composer install



# debugging

## debug from command line 

    export XDEBUG_SESSION=PHPSTORM
    export PHP_IDE_CONFIG="serverName=symf.dv"

Value "symf.dv" writen to .docker/web/nginx/conf.d/default.conf 
and inside docker-compose.yml at  PHP_IDE_CONFIG: 'serverName=symf.dv'

    

# security

## sertificates

From the inside of the symf-app container:

    bin/console lexik:jwt:generate-keypair


## api login

url: http://localhost:8000/api/login_check
body: {"username":"johndoe","password":"test"}

## using the token

In your post headers:

Authorization: Bearer {token}

## creating user in database

    INSERT INTO symf.users (id, username, password, name) VALUES (1, 'jonas', '$2y$13$v5olE3gyeaKGkBhYlDtuw.hO4WwazOiN5l.u/N1UbM13HuBDEiZ6i', 'Jonas');

This will create user with username 'jonas' and password 'test'

To generate other password, call 

    bin/console security:hash-password


