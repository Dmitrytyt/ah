# Blog test project

Простой блог на чистом PHP 8.1+, MySQL и Smarty с MVC-структурой.

## Возможности
- главная страница с категориями и 3 последними статьями
- страница категории с сортировкой и пагинацией
- страница статьи с увеличением счетчика просмотров
- блок из 3 похожих статей
- сидинг категорий и статей
- Docker-окружение

## Быстрый старт

### Через Docker
```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php database/migrate.php
docker compose exec app php database/seed.php
```

Сайт: http://localhost:8080

### Локально
1. Создать базу MySQL.
2. Скопировать `.env.example` в `.env` и заполнить доступы.
3. Выполнить:
```bash
composer install
php database/migrate.php
php database/seed.php
php -S localhost:8000 -t public
```

## Маршруты
- `/` — главная
- `/category/{slug}` — категория
- `/post/{slug}` — статья
