{extends file='layouts/main.tpl'}

{block name='content'}
    {foreach $categories as $category}
        <section class="category-section">
            <div class="section-head">
                <h2>{$category.name|escape}</h2>
                <a href="{$baseUrl}/category/{$category.slug|escape}">Все статьи</a>
            </div>

            <div class="cards-grid">
                {foreach $category.posts as $post}
                    <article class="post-card">
                        <a href="{$baseUrl}/post/{$post.slug|escape}">
                            <img src="{$post.image|escape}" alt="{$post.title|escape}">
                        </a>
                        <h3><a href="{$baseUrl}/post/{$post.slug|escape}">{$post.title|escape}</a></h3>
                        <div class="meta">{$post.published_at|date_format:'%d.%m.%Y'} · {$post.views} просмотров</div>
                        <p>{$post.excerpt|truncate:120}</p>
                        <a class="read-more" href="{$baseUrl}/post/{$post.slug|escape}">Continue Reading</a>
                    </article>
                {/foreach}
            </div>
        </section>
    {/foreach}
{/block}
