<h2><?= $isEdit ? 'Edit Event' : 'Add Event' ?></h2>

<form method="POST" action="">
    <?php include PARTIALS . '/_event_form.php'; ?>
    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update Event' : 'Add Event' ?></button>
</form>
