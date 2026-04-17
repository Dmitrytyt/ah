# Blog test project

Простой блог на чистом PHP 8.1+, MySQL и Smarty с MVC-структурой.

## Возможности
- главная страница с категориями и 3 последними статьями
- страница категории с сортировкой и пагинацией
- страница статьи с увеличением счетчика просмотров
- блок из 3 похожих статей
- сидинг категорий и статей
- Docker-окружение
- Сборщик стилей

## Быстрый старт

### Через Docker
```bash
cp .env.example .env
docker compose up -d --build
docker compose exec blog_php_8.2 composer install
docker compose exec blog_php_8.2 php database/migrate.php
docker compose exec blog_php_8.2 php database/seed.php
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


## Стили (SCSS)
В проект добавлен сборщик стилей на базе Dart Sass.
Требуемая версия Node.js для сборки: **20+**.

### Через Docker (рекомендуется)
```bash
docker compose up -d blog_node_20
docker compose exec blog_node_20 npm install
docker compose exec blog_node_20 npm run styles:build
```

Для разработки с автоматической пересборкой:
```bash
docker compose exec blog_node_20 npm run styles:watch
```

### Локально
```bash
npm install
npm run styles:build
```

Для разработки с автоматической пересборкой:

```bash
npm run styles:watch
```

- исходники SCSS: `src/scss/style.scss`
- итоговый CSS: `public/assets/css/style.css`
