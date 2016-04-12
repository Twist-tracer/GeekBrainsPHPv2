<?php if(!$auth_success) { ?>

    <!-- Форма авторизации-->
    <aside class="module module_auth">
        <h2 class="module__title">Авторизация</h2><hr>
        <?php if($error):?>
            <div class="alert alert_error">Неверные имя пользователя или пароль</div>
        <?php endif ?>
        <form class="form form_auth" name="form-auth" action="#" method="post">
            <div class="form__row">
                <label class="form__label form-auth__label-login">
                    Имя пользователя<br>
                    <input class="form__field form__field-login" type="text" name="login" maxlength="15" placeholder="Введите ваш логин...">
                </label>
            </div>
            <div class="form__row">
                <label class="form__label form__label-password">
                    Пароль<br>
                    <input class="form__field form-auth__field-password" type="password" name="password" maxlength="12" placeholder="Введите ваш пароль...">
                </label>
            </div>
            <div class="form__row">
                <input class="button button_green button_x-small form-auth__send-button" type="submit" name="send-form_auth" value="Войти">
                <input id="rem-check" type="checkbox" name="remember" value="true" checked="checked">
                <label for="rem-check">Запомнить меня</label>
            </div>
            <div class="form__row">
                <a href="index.php?c=page&a=register">Зарегистрироваться</a>
            </div>
        </form>
    </aside>

<?php } else { ?>

    <!-- Панель приветствия -->
    <aside class="module module_auth">
        <h2>Вы вошли как <?=$user?></h2>
        <a href="index.php?c=article&a=index&logout=true">Выйти</a>
    </aside>

<?php } ?>
