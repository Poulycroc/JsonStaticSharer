dev:
	make -j 2 dev-back dev-front
dev-front:
	npm run dev
dev-back:
	php artisan serve

clear:
	php artisan cache:clear
	composer update

autoload:
	composer clear-cache
	composer dump-autoload -o

migrate:
	php artisan migrate:fresh
	make seed

seed:
	php artisan db:seed
seed-only:
	php artisan db:seed --class=$(name)

migration:
	php artisan make:migration create_$(name)_table

model:
	php artisan make:model $(name) --migration

seeder:
	php artisan make:seeder $(name)
controller:
	php artisan make:controller $(name)

bash-migrate:
	sudo docker exec partner-platform_backend-fpm_1 php artisan migrate
bash-clean-docker:
	sudo docker system prune

test-release:
	sh deploy_to_prod_server.sh

list:
	php artisan route:list
