<form method="POST" id="form">
    <?php foreach($form->getAttributes() as $name => $val ) : ?>
        <label><?= $name ?>
        <input name="<?= $name ?>" id="<?= $name ?>" value="<?= $val ?>">
        </label>
    <?php endforeach; ?>
    <button type="submit">Сохранить</button>
</form>
<?php if(count($form->getErrors()) > 0): ?>
<?= implode(', ', $form->getErrors()) ?>
<?php endif; ?>
