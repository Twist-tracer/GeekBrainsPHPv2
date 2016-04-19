<?php if(!$error) {?>
    <?php foreach ($users as $user):?>

        <ul class="users-list">
            <li class="users-list__item">
                <a class="users-list__link" href="users/edit/<?=$user["id"]?>"><?= $user["login"] ?></a>
                <a class="icon icon_delete users-list__link" href="users/editor?del=<?=$user["id"]?>"></a>
            </li>
        </ul>

    <?php endforeach ?>
<?php } else {?>

    <h2 class="empty">Упс, ошибочка вышла :)</h2>

<?php } ?>