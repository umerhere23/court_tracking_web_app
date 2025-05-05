<h2>Add New Case</h2>

<?php if (!empty($success)): ?>
  <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<!-- Main Add Case Form -->
<form method="POST" action="<?= BASE_URL ?>/case/add">
  <!-- Defendant Select -->
  <div class="mb-3">
    <label class="form-label">Select Defendant</label>
    <select class="form-select" name="defendant_ID" required>
      <option value="">-- Choose existing --</option>
      <?php foreach ($defendants as $d): ?>
        <option value="<?= $d['defendant_ID'] ?>"
          <?= ((int)($_GET['defendant_id'] ?? '') === (int)$d['defendant_ID']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($d['Name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- Charge Partial -->
  <h5>Charge Details</h5>
  <?php require PARTIALS . '/_charge_form.php'; ?>

  <!-- Lawyer Select -->
  <h5>Assign Lawyer</h5>
  <div class="mb-3">
    <select class="form-select" name="lawyer_ID" required>
      <option value="">-- Choose lawyer --</option>
      <?php foreach ($lawyers as $l): ?>
        <option value="<?= $l['lawyer_ID'] ?>"
          <?= ((int)($_GET['lawyer_id'] ?? $prefill['lawyer_ID'] ?? '') === (int)$l['lawyer_ID']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($l['Name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- Optional Court Event -->
  <h5>Optional Court Event</h5>
  <?php require PARTIALS . '/_event_form.php'; ?>

  <button type="submit" class="btn btn-success mt-3">Create Case</button>
</form>

<!-- Subform: Add New Defendant -->
<hr class="my-4">
<form method="POST" action="<?= BASE_URL ?>/case/create_defendant">
  <h5>Add New Defendant</h5>
  <?php require PARTIALS . '/_defendant_form.php'; ?>
  <div class="mt-3">
    <button type="submit" class="btn btn-outline-primary">Create Defendant</button>
  </div>
</form>

<!-- Subform: Add New Lawyer -->
<hr class="my-4">
<form method="POST" action="<?= BASE_URL ?>/case/create_lawyer">
  <h5>Add New Lawyer</h5>
  <?php require PARTIALS . '/_lawyer_form.php'; ?>
  <div class="mt-3">
    <button type="submit" class="btn btn-outline-primary">Create Lawyer</button>
  </div>
</form>
