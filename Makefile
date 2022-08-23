.PHONY: test
test: up
	docker-compose exec app phpunit

load-data: up
	echo "Load fixtures..."
	docker-compose exec app php bin/console doctrine:fixtures:load --no-interaction

up:
	docker-compose up -d

down:
	docker-compose down -v

up-first: up
	docker-compose exec app composer install \
		--no-interaction \
		--no-plugins \
		--no-scripts \
		--no-autoloader \
		--prefer-dist
	docker-compose exec app php bin/console doctrine:migrations:migrate --no-interaction
