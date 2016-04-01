<ul class="main-menu">
    <li class="main-menu__item <?php if ($current == "Главная") print "main-menu__item_current"?>">
        <a href="index.php?c=article&?a=index">Главная</a>
    </li>
    <li class="main-menu__item <?php if ($current == "Консоль редактора") print "main-menu__item_current"?>">
        <a href="editor.php?c=article&?a=editor">Консоль редактора</a>
    </li>
</ul>