<?php

namespace App\Models;

use App\Core\Model;

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

        if (!$categories) {
            return [];
        }

        $categoryIds = array_map(static fn (array $category): int => (int) $category['id'], $categories);
        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));

        $stmt = $this->db->prepare(
            "SELECT ranked.*
             FROM (
                SELECT
                    p.*,
                    pc.category_id,
                    ROW_NUMBER() OVER (
                        PARTITION BY pc.category_id
                        ORDER BY p.published_at DESC
                    ) AS row_num
                FROM posts p
                INNER JOIN post_category pc ON pc.post_id = p.id
                WHERE pc.category_id IN ({$placeholders})
             ) ranked
             WHERE ranked.row_num <= ?
             ORDER BY ranked.category_id, ranked.published_at DESC"
        );

        $paramIndex = 1;
        foreach ($categoryIds as $categoryId) {
            $stmt->bindValue($paramIndex++, $categoryId, \PDO::PARAM_INT);
        }
        $stmt->bindValue($paramIndex, $limitPerCategory, \PDO::PARAM_INT);
        $stmt->execute();

        $postsByCategory = [];
        foreach ($stmt->fetchAll() as $post) {
            $postsByCategory[(int) $post['category_id']][] = $post;
        }

        foreach ($categories as &$category) {
            $category['posts'] = $postsByCategory[(int) $category['id']] ?? [];
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
