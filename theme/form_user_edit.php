<div class="form-wrap">
    <?php if($error):?>
        <div class="alert alert_error">Заполните все обязательные (*) поля!</div>
    <?php endif ?>
    <form name="editUser" class="form form_edit-user" method="post">
        <table class="form__table form__table_edit-user">
            <thead>
                <tr>
                    <td>Логин</td><td>Привелегия</td>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td><input type="text" name="login" value="<?=$login?>"></td>
                <td>
                    <select name="role">
                        <?=$options?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="send-editUser" class="button button_green button_small addComment-form__send" value="Сохранить"></td>
            </tr>
            </tbody>
        </table>
    </form>
</div>