<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(Request $request): void
    {
        $categoryModel = new Category();
        $categories = $categoryModel->allWithLatestPosts(3);

        $this->view('home/index', [
            'pageTitle' => 'Главная',
            'categories' => $categories,
        ]);
    }
}
