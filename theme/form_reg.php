<h2>Регистрация</h2>
<div class=form-wrap">
    <?php if($error) :?>
        <div class="alert alert_error alert_reg">Ашипка! Проверьте корректность введенных данных</div>
    <?php endif ?>
    <form name="regUser" class="form form_regUser" method="post">
        <table class="form__table form_regUser">
            <tbody>
            <tr>
                <td>Логин *</td>
                <td><input type="text" name="login" value="<?=$login?>" placeholder="Самый лучший заголовок"></td>
            </tr>
            <tr>
                <td>Пароль *</td>
                <td><input type="password" name="password" placeholder="Самый лучший заголовок"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="send-regUser" class="button button_green button_small addComment-form__send" value="Зарегистрироваться"></td>
            </tr>
            </tbody>
        </table>
    </form>
</div>