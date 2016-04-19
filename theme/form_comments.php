<?php if($add_comments_access) {?>
<div class="form-wrap">
    <?php if($error):?>
        <div class="alert alert_error">Заполните все обязательные (*) поля!</div>
    <?php endif ?>
    <form name="addComment" class="form form_add-comment" method="post">
        <h3 class="form__title">Добавить комментарий</h3>
        <table class="form__table">
            <tbody>
                <tr>
                    <td colspan="2">
                        <textarea name="comment" placeholder="Текст комментария *"><?=$comment?></textarea>
                        <input type="hidden" name="name" value="<?=$name?>" placeholder="Стив Джобс">
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="send-addComment" class="button button_green button_small addComment-form__send" value="Отправить"></td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
<?php } else { ?>
    <div>Для того чтобы оставлять комментарии авторизуйтесь используйя форму справа</div>
<?php } ?>
