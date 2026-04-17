<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Category extends Model
{
    public function allWithLatestPosts(int $limitPerCategory = 3): array
    {
        $categories = $this->db->query(
            'SELECT c.*
             FROM categories c
             WHERE EXISTS (
                SELECT 1 FROM post_category pc WHERE pc.category_id = c.id
             )
             ORDER BY c.name'
        )->fetchAll();

        $stmt = $this->db->prepare(
            'SELECT p.*
             FROM posts p
             INNER JOIN post_category pc ON pc.post_id = p.id
             WHERE pc.category_id = :category_id
             ORDER BY p.published_at DESC
             LIMIT :limit'
        );

        foreach ($categories as &$category) {
            $stmt->bindValue(':category_id', (int) $category['id'], PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limitPerCategory, PDO::PARAM_INT);
            $stmt->execute();
            $category['posts'] = $stmt->fetchAll();
        }

        return $categories;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch();

        return $result ?: null;
    }
}
