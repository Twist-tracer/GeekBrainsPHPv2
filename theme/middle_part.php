<main class="content <?=$width?>">
    <?php if($have_access) { ?>
        <?=$content?>
    <?php } else { ?>
        <div class="empty">У Вас нет доступа к этой странице</div>
    <?php } ?>
</main>

<?=$sidebar?>