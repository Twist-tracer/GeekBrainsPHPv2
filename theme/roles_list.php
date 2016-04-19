<?php if(!$error) {?>
    <?php foreach ($roles as $role):?>

        <option <?php if($user_role_id == $role["id"]) print "selected"?> value="<?=$role["id"]?>"><?=$role["name"]?></option>

    <?php endforeach ?>
<?php } else {?>

    <option selected disabled>error</option>

<?php } ?>