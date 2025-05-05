<h2>Add New Case</h2>

<?php if (!empty($success)): ?>
  <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<!-- Main Add Case Form -->
<form method="POST" action="<?= BASE_URL ?>/case/add">
  <!-- Defendant Selection -->
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

  <!-- Charge Info -->
  <h5>Charge Details</h5>
  <div class="mb-3">
    <input type="text" class="form-control mb-2" name="charge_description" placeholder="Charge Description"
           value="<?= htmlspecialchars($prefill['charge_description'] ?? '') ?>" required>
    <input type="text" class="form-control mb-2" name="charge_status" placeholder="Charge Status"
           value="<?= htmlspecialchars($prefill['charge_status'] ?? '') ?>" required>
  </div>

  <!-- Lawyer Selection -->
  <h5>Assign Lawyer</h5>
  <div class="mb-3">
    <select class="form-select" name="lawyer_ID" required>
      <option value="">-- Choose lawyer --</option>
      <?php foreach ($lawyers as $l): ?>
        <option value="<?= $l['lawyer_ID'] ?>"
          <?= ((int)($selected_lawyer_id ?? $prefill['lawyer_ID'] ?? '') === (int)$l['lawyer_ID']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($l['Name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- Optional Court Event -->
  <h5>Optional Court Event</h5>
  <div class="mb-3">
    <input type="text" class="form-control mb-2" name="event_description" placeholder="Event Description"
           value="<?= htmlspecialchars($prefill['event_description'] ?? '') ?>">
    <input type="date" class="form-control mb-2" name="event_date"
           value="<?= htmlspecialchars($prefill['event_date'] ?? '') ?>">
    <input type="text" class="form-control mb-3" name="event_location" placeholder="Event Location"
           value="<?= htmlspecialchars($prefill['event_location'] ?? '') ?>">
  </div>

  <button type="submit" class="btn btn-success">Create Case</button>
</form>

<!-- Subform: Add New Defendant -->
<hr class="my-4">
<form method="POST" action="<?= BASE_URL ?>/case/create_defendant">
  <h5>Add New Defendant</h5>
  <div class="row g-3">
    <div class="col-md-6">
      <input type="text" class="form-control" name="new_defendant_name" placeholder="Name" required>
    </div>
    <div class="col-md-6">
      <input type="date" class="form-control" name="new_defendant_dob" placeholder="Date of Birth" required>
    </div>
    <div class="col-md-12">
      <input type="text" class="form-control" name="new_defendant_address" placeholder="Address">
    </div>
    <div class="col-md-6">
      <input type="text" class="form-control" name="new_defendant_ethnicity" placeholder="Ethnicity">
    </div>
    <div class="col-md-6">
      <input type="text" class="form-control" name="new_defendant_phone" placeholder="Phone">
    </div>
    <div class="col-md-12">
      <input type="email" class="form-control" name="new_defendant_email" placeholder="Email">
    </div>
    <div class="col-12 mt-3">
      <button type="submit" class="btn btn-outline-primary">Create Defendant</button>
    </div>
  </div>
</form>

<!-- Subform: Add New Lawyer -->
<hr class="my-4">
<form method="POST" action="<?= BASE_URL ?>/case/create_lawyer">
  <h5>Add New Lawyer</h5>
  <div class="row g-3">
    <div class="col-md-6">
      <input type="text" class="form-control" name="new_lawyer_name" placeholder="Name" required>
    </div>
    <div class="col-md-6">
      <input type="email" class="form-control" name="new_lawyer_email" placeholder="Email">
    </div>
    <div class="col-md-6">
      <input type="text" class="form-control" name="new_lawyer_phone" placeholder="Phone">
    </div>
    <div class="col-md-6">
      <input type="text" class="form-control" name="new_lawyer_firm" placeholder="Firm">
    </div>
    <div class="col-12 mt-3">
      <button type="submit" class="btn btn-outline-primary">Create Lawyer</button>
    </div>
  </div>
</form>