docker-up:
	docker-compose up -d

docker-stop:
	docker-compose stop

docker-down:
	docker-compose down

docker-build:
	docker-compose up --build -d

docker-php:
	docker-compose exec php php

docker-artisan-migrate:
	docker-compose exec php php artisan migrate

docker-composer:
	docker-compose run composer

test:
	docker-compose exec php php vendor/bin/phpunit --colors=always

assets-install:
	docker-compose exec node yarn install

assets-dev:
	docker-compose exec node yarn run dev

assets-watch:
	docker-compose exec node yarn run watch

perm:
	sudo chown ${USER}:${USER} bootstrap/cache -R
	sudo chown ${USER}:${USER} .docker/conf/postgres -R
	sudo chmod -R 777 .docker/conf/postgres
	sudo chown ${USER}:${USER} storage -R
	sudo chmod -R 777 storage
	sudo chmod -R 777 app
	sudo chmod -R 777 resources
	sudo chmod -R 777 bootstrap/cache

uperm:
	sudo chown ${USER}:${USER} app/Http/Controllers/Ajax -R
	sudo chmod -R 777 app/Http/Controllers/Ajax