<?php if(!$error) {?>
    <?php foreach ($articles as $article):?>

        <ul class="articles-list">
            <li class="articles-list__item">
                <a class="articles-list__link" href="index.php?c=article&a=edit&id=<?=$article["id"]?>"><?= $article["title"] ?></a>
                <a class="icon icon_delete articles-list__link" href="index.php?c=article&a=editor&del=<?=$article["id"]?>"></a>
            </li>
        </ul>

    <?php endforeach ?>
<?php } else {?>

    <h2 class="empty">На сайте пока нет статей</h2>

<?php } ?>