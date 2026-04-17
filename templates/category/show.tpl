{extends file='layouts/main.tpl'}

{block name='content'}
<section class="category-page">
    <h1>{$category.name|escape}</h1>
    <p class="category-description">{$category.description|escape}</p>

    <div class="toolbar">
        <span>Сортировка:</span>
        <a class="{if $sort === 'date'}active{/if}" href="?sort=date&page=1">По дате</a>
        <a class="{if $sort === 'views'}active{/if}" href="?sort=views&page=1">По просмотрам</a>
    </div>

    <div class="cards-grid">
        {foreach $posts as $post}
            <article class="post-card">
                <a href="{$baseUrl}/post/{$post.slug|escape}">
                    <img src="{$post.image|escape}" alt="{$post.title|escape}">
                </a>
                <h3><a href="{$baseUrl}/post/{$post.slug|escape}">{$post.title|escape}</a></h3>
                <div class="meta">{$post.published_at|date_format:'%d.%m.%Y'} · {$post.views} просмотров</div>
                <p>{$post.excerpt|truncate:150}</p>
            </article>
        {/foreach}
    </div>

    {if $pagination.last_page > 1}
        <nav class="pagination">
            {section name=p start=1 loop=$pagination.last_page+1 step=1}
                <a class="{if $smarty.section.p.index == $pagination.current_page}active{/if}" href="?sort={$sort|escape}&page={$smarty.section.p.index}">{$smarty.section.p.index}</a>
            {/section}
        </nav>
    {/if}
</section>
{/block}
