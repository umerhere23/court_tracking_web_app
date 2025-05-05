<h2>Select or Add Lawyer</h2>

<form method="POST" action="<?= BASE_URL ?>/lawyer/add">
  <div class="mb-3">
    <label>Select Existing Lawyer</label>
    <select class="form-select" name="lawyer_ID">
      <option value="">-- Choose --</option>
      <?php foreach ($lawyers as $l): ?>
        <option value="<?= $l['lawyer_ID'] ?>"><?= htmlspecialchars($l['Name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <button type="submit" name="action" value="select_existing" class="btn btn-primary mt-3">Next: Charge</button>

  <hr>
  <h5>Or Add New Lawyer</h5>
  <?php require PARTIALS . '/_lawyer_form.php'; ?>

  <button type="submit" name="action" value="add_new" class="btn btn-secondary mt-3">Add New</button>
</form>
