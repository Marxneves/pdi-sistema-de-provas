
# Makefile
build-and-serve:
	@eval $(ssh-agent); docker run --rm --interactive --tty \
		--volume ${PWD}:/app \
  		--volume ${SSH_AUTH_SOCK}:/ssh-auth.sock \
  		--env SSH_AUTH_SOCK=/ssh-auth.sock \
  		composer:2.3.10 composer install --ignore-platform-reqs --no-scripts && \
	cp .env.example .env && \
  	docker-compose -f ./docker-compose.yaml up --build --remove-orphans

serve:
	@docker-compose -f ./docker-compose.yaml up

shell:
	@docker-compose -f ./docker-compose.yaml exec api bash

run:
	@docker-compose -f ./docker-compose.yaml exec -T api sh -c "/var/www/artisan $(filter-out $@, $(MAKECMDGOALS))"

help:
	@docker-compose -f ./docker-compose.yaml exec -T api sh -c "/var/www/artisan doctrine:generate:proxies && composer dump-autoload && chmod -R 777 storage/proxies"

db_update:
	@docker-compose -f ./docker-compose.yaml exec -T api sh -c "php artisan doctrine:migrations:migrate && php artisan db:seed"

all-tests:
	@docker-compose -f ./docker-compose.yaml exec -T api sh -c "./vendor/bin/phpunit -d memory_limit=-1"

key-generate:
	@docker-compose -f ./docker-compose.yaml exec -T api sh -c "php artisan key:generate"

fix-migrations-permissions:
	@sudo chmod -R 777 database/migrations/*