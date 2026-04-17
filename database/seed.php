<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Database;
use App\Services\Slugger;
use Dotenv\Dotenv;
use Faker\Factory;

require_once __DIR__ . '/../vendor/autoload.php';

$root = dirname(__DIR__);
if (file_exists($root . '/.env')) {
    Dotenv::createImmutable($root)->safeLoad();
}
Config::load($root . '/config/database.php', 'database');
$db = Database::connection();
$faker = Factory::create('ru_RU');

$db->exec('SET FOREIGN_KEY_CHECKS=0');
$db->exec('TRUNCATE TABLE post_category');
$db->exec('TRUNCATE TABLE posts');
$db->exec('TRUNCATE TABLE categories');
$db->exec('SET FOREIGN_KEY_CHECKS=1');

$categories = [
    ['Мода', 'Все о трендах, аксессуарах и стиле.'],
    ['Красота', 'Уход за кожей, макияж и wellness-подход.'],
    ['Кофе', 'Гайды, рецепты и обзоры кофейной культуры.'],
    ['Путешествия', 'Идеи для поездок и вдохновение.'],
];

$insertCategory = $db->prepare('INSERT INTO categories (name, slug, description) VALUES (:name, :slug, :description)');
$categoryIds = [];
foreach ($categories as [$name, $description]) {
    $slug = Slugger::make($name);
    $insertCategory->execute(compact('name', 'slug', 'description'));
    $categoryIds[] = (int) $db->lastInsertId();
}

$images = [
    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80'
];

$insertPost = $db->prepare(
    'INSERT INTO posts (title, slug, image, excerpt, content, views, published_at)
     VALUES (:title, :slug, :image, :excerpt, :content, :views, :published_at)'
);
$attachCategory = $db->prepare('INSERT INTO post_category (post_id, category_id) VALUES (:post_id, :category_id)');

for ($i = 1; $i <= 24; $i++) {
    $title = ucfirst($faker->words(mt_rand(3, 6), true));
    $slug = Slugger::make($title . '-' . $i);
    $image = $images[array_rand($images)];
    $excerpt = $faker->realText(140);
    $content = '<p>' . implode('</p><p>', $faker->paragraphs(5)) . '</p>';
    $views = mt_rand(10, 500);
    $published_at = $faker->dateTimeBetween('-120 days', 'now')->format('Y-m-d H:i:s');

    $insertPost->execute(compact('title', 'slug', 'image', 'excerpt', 'content', 'views', 'published_at'));
    $postId = (int) $db->lastInsertId();

    $assigned = $faker->randomElements($categoryIds, mt_rand(1, 2));
    foreach ($assigned as $categoryId) {
        $attachCategory->execute(['post_id' => $postId, 'category_id' => $categoryId]);
    }
}

echo "Database seeded successfully.\n";
