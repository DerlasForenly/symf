ssh:
	@docker exec -it symfony_php sh

nginx-reload:
	@docker exec -it gdp_nginx service nginx reload

build:
	@docker-compose up -d --build

start:
	@docker-compose up -d

stop:
	@docker-compose down

env:
	cp ./.env.example ./.env

restart: stop start

cache:
	@docker exec -it symfony_php php bin/console cache:clear

migration:
	@docker exec -it symfony_php php bin/console make:migration

migrate:
	@docker exec -it symfony_php php bin/console doctrine:migrations:migrate

fresh:
	@docker exec -it symfony_php php bin/console doctrine:database:drop --force
	@docker exec -it symfony_php php bin/console doctrine:database:create
	@docker exec -it symfony_php php bin/console doctrine:migrations:migrate
	@docker exec -it symfony_php php bin/console doctrine:fixtures:load

routes:
	@docker exec -it symfony_php php bin/console debug:router

seed:
	@docker exec -it symfony_php php bin/console doctrine:fixtures:load

