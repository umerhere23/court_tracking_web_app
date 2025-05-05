<h2>Add or Select Defendant</h2>

<?php if (!empty($success)): ?>
  <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/defendant/add">
  <div class="mb-3">
    <label class="form-label">Select Existing Defendant</label>
    <select class="form-select" name="defendant_ID">
      <option value="">-- Choose --</option>
      <?php foreach ($defendants as $d): ?>
        <option value="<?= $d['defendant_ID'] ?>"><?= htmlspecialchars($d['Name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <button type="submit" name="action" value="select_existing" class="btn btn-primary mt-3">Next: Court Event</button>

  <hr>
  <h5>Or Add New Defendant</h5>
  <?php require PARTIALS . '/_defendant_form.php'; ?>
  <button type="submit" name="action" value="add_new" class="btn btn-secondary mt-3">Add New</button>

</form>