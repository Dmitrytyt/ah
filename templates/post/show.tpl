{extends file='layouts/main.tpl'}

{block name='content'}
<article class="post-page">
    <img class="post-hero" src="{$post.image|escape}" alt="{$post.title|escape}">
    <h1>{$post.title|escape}</h1>
    <div class="meta">{$post.published_at|date_format:'%d.%m.%Y'} · {$post.views} просмотров</div>

    <div class="chips">
        {foreach $post.categories as $category}
            <a href="{$baseUrl}/category/{$category.slug|escape}" class="chip">{$category.name|escape}</a>
        {/foreach}
    </div>

    <p class="excerpt">{$post.excerpt|escape}</p>
    <div class="post-content">{$post.content_safe nofilter}</div>
</article>

<section class="related-section">
    <h2>Похожие статьи</h2>
    <div class="cards-grid">
        {foreach $relatedPosts as $item}
            <article class="post-card">
                <a href="{$baseUrl}/post/{$item.slug|escape}">
                    <img src="{$item.image|escape}" alt="{$item.title|escape}">
                </a>
                <h3><a href="{$baseUrl}/post/{$item.slug|escape}">{$item.title|escape}</a></h3>
                <div class="meta">{$item.published_at|date_format:'%d.%m.%Y'} · {$item.views} просмотров</div>
                <p>{$item.excerpt|truncate:120}</p>
            </article>
        {/foreach}
    </div>
</section>
{/block}
