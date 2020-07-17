start:
	php artisan serve

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test

lint:
	composer exec phpcs

lint-fix:
	composer exec phpcbf

