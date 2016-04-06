<aside class="module module_auth">
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
            <input class="button button_green button_x-small form-auth__send-button" type="submit" name="send-form-auth" value="Войти">
            <input id="rem-check" type="checkbox" name="remember-check" value="true" checked="checked">
            <label for="rem-check">Запомнить меня</label>
        </div>
        <div class="form__row">
            <a href="#">Зарегистрироваться</a>
        </div>
    </form>
</aside>