<div class="tabs">
    <a href="articles/editor" id="tab1" class="tabs__tab <?php if ($current == "articles") print "tabs__tab_active"?>" title="Статьи">Статьи</a>

    <a href="users/editor" id="tab2" class="tabs__tab <?php if ($current == "users") print "tabs__tab_active"?>" title="Пользователи">Пользователи</a>

    <section class="tabs__content" >
        <?=$tabs_content?>
    </section>
</div>