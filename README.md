# Rateme backend

## Установка

```
cp .env.example .env
cp src/.env.example src/.env
docker compose up -d
docker compose exec app composer install
```

## Полезные команды

### Сбросить базу данных
Сбрасывает БД и засеивает необходимыми данными
```
docker compose exec app composer run-script refresh
```

### Засеить БД тестовыми пользователями
```
docker compose exec app php artisan db:seed --class=UsersSeeder
```