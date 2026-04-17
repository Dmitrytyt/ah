<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$pageTitle|default:$appName} - {$appName}</title>
    <link rel="stylesheet" href="{$baseUrl}/assets/css/style.css?v={$styleVersion}">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a class="logo" href="{$baseUrl}/">{$appName}</a>
    </div>
</header>

<main class="container main-content">
    {block name="content"}{/block}
</main>

<footer class="site-footer">
    <div class="container">Copyright © 2026. All Rights Reserved.</div>
</footer>
</body>
</html>
