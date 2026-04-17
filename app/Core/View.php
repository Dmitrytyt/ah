<?php

namespace App\Core;

use Smarty\Smarty;

class View
{
    public static function make(): self
    {
        return new self();
    }

    public function __construct(private ?Smarty $smarty = null)
    {
        $this->smarty = $this->smarty ?: new Smarty();
        $this->smarty->setTemplateDir(Config::get('app.views_path'));
        $this->smarty->setCompileDir(Config::get('app.templates_c'));
        $this->smarty->setCacheDir(Config::get('app.cache_dir'));
        $this->smarty->assign('appName', Config::get('app.name'));
        $this->smarty->assign('baseUrl', Config::get('app.base_url'));
    }

    public function render(string $template, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        $this->smarty->display($template . '.tpl');
    }
}
