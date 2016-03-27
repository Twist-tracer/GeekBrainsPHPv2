<?php if(!$error) {?>
    <?php foreach ($articles as $article):?>

        <div class="pre-article clearfix">
            <div class="pre-article__content">
                <h2 class="pre-article__title"><?= $article["title"] ?></h2>
                <hr>
                <p class="pre-article__preview_text"><?= $article["content"] ?></p>
                <a href="index.php?id=<?=$article["id"]?>">Подробнее</a>
            </div>
        </div>

    <?php endforeach ?>
<?php } else {?>

        <h2 class="empty">На сайте пока нет статей</h2>

<?php } ?>