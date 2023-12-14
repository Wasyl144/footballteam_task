up:
	docker compose up -d
stop:
	docker compose stop
be-bash:
	docker compose exec -it backend bash
fe-bash:
	docker compose exec -it frontend bash
pint:
	docker compose exec -it backend vendor/bin/pint
ide-helper:
	docker compose exec -it backend php artisan ide-helper:generate
	docker compose exec -it backend php artisan ide-helper:models
