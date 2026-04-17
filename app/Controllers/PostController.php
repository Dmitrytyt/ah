<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Post;
use App\Services\HtmlSanitizer;

class PostController extends Controller
{
    public function show(Request $request, string $slug): void
    {
        $postModel = new Post();
        $post = $postModel->findBySlug($slug);

        if (!$post) {
            http_response_code(404);
            echo 'Статья не найдена';
            return;
        }

        $post['views'] = $postModel->incrementViews((int) $post['id']);
        $post['categories'] = $postModel->categoriesForPost((int) $post['id']);
        $post['content_safe'] = (new HtmlSanitizer())->sanitize((string) $post['content']);
        $relatedPosts = $postModel->related((int) $post['id'], 3);

        $this->view('post/show', [
            'pageTitle' => $post['title'],
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
