<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="theme/style.css" />

    <title><?=$top_title?></title>
</head>
<body>
<div class="globContainer">
    <header class="header">
        <h1 class="header__title"><?=$title?></h1>
    </header>
    
    <nav class="main-nav">
        <?=$main_menu?>
    </nav>

    <div class="container clearfix">
        <?=$content?>
    </div>

    <?=$comments?>

    <footer class="footer">
        <p class="footer__paragraph">Copyright &copy; 2016 Сема Богданов.</p>
    </footer>
</div>
</body>
</html>