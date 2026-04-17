<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Post extends Model
{
    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM posts WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function paginateByCategory(int $categoryId, string $sort, int $page, int $perPage): array
    {
        $sortSql = $sort === 'views' ? 'p.views DESC, p.published_at DESC' : 'p.published_at DESC';
        $offset = ($page - 1) * $perPage;

        $countStmt = $this->db->prepare(
            'SELECT COUNT(*)
             FROM posts p
             INNER JOIN post_category pc ON pc.post_id = p.id
             WHERE pc.category_id = :category_id'
        );
        $countStmt->execute(['category_id' => $categoryId]);
        $total = (int) $countStmt->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT p.*
             FROM posts p
             INNER JOIN post_category pc ON pc.post_id = p.id
             WHERE pc.category_id = :category_id
             ORDER BY {$sortSql}
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'items' => $stmt->fetchAll(),
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => max(1, (int) ceil($total / $perPage)),
            ],
        ];
    }

    public function incrementViews(int $postId): void
    {
        $stmt = $this->db->prepare('UPDATE posts SET views = views + 1 WHERE id = :id');
        $stmt->execute(['id' => $postId]);
    }

    public function categoriesForPost(int $postId): array
    {
        $stmt = $this->db->prepare(
            'SELECT c.*
             FROM categories c
             INNER JOIN post_category pc ON pc.category_id = c.id
             WHERE pc.post_id = :post_id'
        );
        $stmt->execute(['post_id' => $postId]);

        return $stmt->fetchAll();
    }

    public function related(int $postId, int $limit = 3): array
    {
        $stmt = $this->db->prepare(
            'SELECT DISTINCT p.*
             FROM posts p
             INNER JOIN post_category pc ON pc.post_id = p.id
             WHERE pc.category_id IN (
                 SELECT category_id FROM post_category WHERE post_id = :post_id
             )
             AND p.id != :post_id
             ORDER BY p.published_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
