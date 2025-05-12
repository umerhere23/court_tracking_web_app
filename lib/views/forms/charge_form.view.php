<h2><?= $isEdit ? 'Edit Charge' : 'Add Charge' ?></h2>

<form method="POST" action="">
    <?php include PARTIALS . '/_charge_form.php'; ?>
    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update Charge' : 'Add Charge' ?></button>
</form>
