.PHONY: help docker-up docker-down docker-build docker-logs docker-shell-app docker-shell-db docker-artisan docker-migrate docker-seed docker-fresh migration model controller

help:
	@echo "GitHub League - Docker Commands"
	@echo ""
	@echo "Setup & Management:"
	@echo "  make docker-build       - Build Docker images"
	@echo "  make docker-up          - Start all containers"
	@echo "  make docker-down        - Stop all containers"
	@echo "  make docker-fresh       - Remove all and restart fresh"
	@echo ""
	@echo "Logs & Debug:"
	@echo "  make docker-logs        - View container logs (tail -f)"
	@echo "  make docker-shell-app   - Enter app shell (bash)"
	@echo "  make docker-shell-db    - Enter MySQL shell"
	@echo ""
	@echo "Laravel Generators:"
	@echo "  make migration NAME     - Create migration (e.g: make migration user)"
	@echo "  make model NAME         - Create model (e.g: make model User)"
	@echo "  make controller NAME    - Create controller (e.g: make controller User)"
	@echo ""
	@echo "Laravel Artisan:"
	@echo "  make docker-migrate     - Run database migrations"
	@echo "  make docker-seed        - Run seeders"
	@echo "  make docker-tinker      - Open Laravel Tinker"
	@echo ""
	@echo "Node/Assets:"
	@echo "  make docker-npm-build   - Build assets (npm run build)"
	@echo "  make docker-npm-install - Install npm packages"

docker-up:
	docker-compose up -d
	@echo "✅ Containers started"

docker-down:
	docker-compose down
	@echo "✅ Containers stopped"

docker-build:
	docker-compose build
	@echo "✅ Images built"

docker-fresh:
	docker-compose down -v
	docker-compose build --no-cache
	docker-compose up -d
	@echo "✅ Fresh start complete"

docker-logs:
	docker-compose logs -f

docker-shell-app:
	docker-compose exec app bash

docker-shell-db:
	docker-compose exec mysql mysql -u laravel -p github_league

docker-migrate:
	docker-compose exec app php artisan migrate

docker-seed:
	docker-compose exec app php artisan db:seed

docker-fresh-db:
	docker-compose exec app php artisan migrate:fresh --seed

docker-tinker:
	docker-compose exec app php artisan tinker

docker-npm-install:
	docker-compose exec node npm install

docker-npm-build:
	docker-compose exec node npm run build

docker-clear-cache:
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:cache
	@echo "✅ Cache cleared"

# Laravel Generators
migration:
	@if [ -z "$(NAME)" ]; then \
		echo "❌ Error: Please provide a name. Usage: make migration NAME=user"; \
	else \
		docker-compose exec app php artisan make:migration create_$(shell echo $(NAME) | tr A-Z a-z)s_table --create=$(shell echo $(NAME) | tr A-Z a-z)s; \
		echo "✅ Migration created: create_$(shell echo $(NAME) | tr A-Z a-z)s_table"; \
	fi

model:
	@if [ -z "$(NAME)" ]; then \
		echo "❌ Error: Please provide a name. Usage: make model NAME=User"; \
	else \
		docker-compose exec app php artisan make:model $(shell echo $(NAME) | sed 's/\b\(.\)/\u\1/g'); \
		echo "✅ Model created: $(shell echo $(NAME) | sed 's/\b\(.\)/\u\1/g')"; \
	fi

controller:
	@if [ -z "$(NAME)" ]; then \
		echo "❌ Error: Please provide a name. Usage: make controller NAME=User"; \
	else \
		docker-compose exec app php artisan make:controller $(shell echo $(NAME) | sed 's/\b\(.\)/\u\1/g')Controller; \
		echo "✅ Controller created: $(shell echo $(NAME) | sed 's/\b\(.\)/\u\1/g')Controller"; \
	fi
