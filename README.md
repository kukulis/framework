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

    
