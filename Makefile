init: start composer-install key-generate migrate seed

composer-install:
	docker compose exec app composer install

composer-update:
	docker compose exec app composer update

key-generate:
	docker compose exec app php artisan key:generate

start:
	docker compose up -d

rebuild:
	docker compose up -d --build

stop:
	docker compose stop

migrate:
	docker compose exec app php artisan migrate

seed:
	docker compose exec app php artisan db:seed

test: 
	docker compose exec app php artisan test

docker-test:
	docker build --file docker/Dockerfile --progress plain --no-cache --target test .