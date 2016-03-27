<div class="form-wrap">
    <?php if($error):?>
        <div class="alert alert_error">Заполните все обязательные (*) поля!</div>
    <?php endif ?>
    <form name="editArticle" class="form form_edit-article" method="post">
        <table class="form__table">
            <tbody>
            <tr>
                <td>Заголовок *</td>
                <td><input type="text" name="title" value="<?=$title?>"></td>
            </tr>
            <tr>
                <td colspan="2"><textarea name="content"><?=$content?></textarea></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="send-editArticle" class="button button_green button_small addComment-form__send" value="Сохранить"></td>
            </tr>
            </tbody>
        </table>
    </form>
</div>