## Instalacja

```bash
docker-compose up -d

docker-compose exec -e COMPOSER_MEMORY_LIMIT=-1 php composer install
```

## Uruchomienie
```bash
docker-compose exec php bin/console app:account-numbers-ocr ./var/data/account_numbers.txt
```

## Testy
```bash
docker-compose exec php bin/phpunit -c phpunit.xml ./tests/
```