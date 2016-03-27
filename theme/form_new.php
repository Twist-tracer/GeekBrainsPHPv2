<div class="form-wrap">
    <?php if($error) :?>
        <div class="alert alert_error">Заполните все обязательные (*) поля!</div>
    <?php endif ?>
    <form name="addArticle" class="form form_add-article" method="post">
        <table class="form__table">
            <tbody>
            <tr>
                <td>Заголовок *</td>
                <td><input type="text" name="title" value="<?=$title?>" placeholder="Самый лучший заголовок"></td>
            </tr>
            <tr>
                <td colspan="2"><textarea name="content" placeholder="Текст вашей статьи..."><?=$content?></textarea></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="send-addArticle" class="button button_green button_small addComment-form__send" value="Добавить статью"></td>
            </tr>
            </tbody>
        </table>
    </form>
</div>