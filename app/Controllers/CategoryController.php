<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Category;
use App\Models\Post;

class CategoryController extends Controller
{
    public function show(Request $request, string $slug): void
    {
        $categoryModel = new Category();
        $postModel = new Post();

        $category = $categoryModel->findBySlug($slug);

        if (!$category) {
            http_response_code(404);
            echo 'Категория не найдена';
            return;
        }

        $sort = $request->query('sort', 'date');
        $rawPage = (int) $request->query('page', 1);
        $page = max(1, min(1000, $rawPage));
        $perPage = 6;

        $result = $postModel->paginateByCategory((int) $category['id'], $sort, $page, $perPage);

        if ($rawPage > $result['pagination']['last_page']) {
            http_response_code(404);
            echo 'Страница не найдена';
            return;
        }

        $this->view('category/show', [
            'pageTitle' => $category['name'],
            'category' => $category,
            'posts' => $result['items'],
            'pagination' => $result['pagination'],
            'sort' => $sort,
        ]);
    }
}
