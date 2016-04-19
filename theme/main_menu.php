<ul class="main-menu">
    <li class="main-menu__item <?php if ($current == "Главная") print "main-menu__item_current"?>">
        <a href="articles/index">Главная</a>
    </li>
    <?php if ($consol_access): ?>
        <li class="main-menu__item <?php if ($current == "Консоль редактора") print "main-menu__item_current"?>">
            <a href="articles/editor">Консоль редактора</a>
        </li>
    <?php endif ?>
</ul>