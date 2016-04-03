<?php if(!$error) {?>
    <?php foreach ($comments as $comment):?>

        <div class="comment">
            <div class="comment__header">
                <span class="icon icon_author comment-content__header-elem"><?=$comment["name"]?></span>
                <time class="icon icon_date comment-content__header-elem"><?php print date("d.m.Y", $comment["date"])." в ".date("H:m:s", $comment["date"]); ?></time>
            </div>
            <p class="comment__text"><?=$comment["comment"]?></p>
        </div>

    <?php endforeach ?>
<?php } else {?>

    <h2 class="empty">Здесь пока нет комментариев</h2>

<?php } ?>