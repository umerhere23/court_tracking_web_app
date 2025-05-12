<h2>Add or Select Defendant</h2>

<?php if (!empty($success)): ?>
  <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form id="defendant-form" method="POST" action="<?= BASE_URL ?>/defendant/add">
  <div class="mb-3">
    <label class="form-label">Select Existing Defendant</label>
    <select class="form-select" name="defendant_ID" id="defendant_ID">
      <option value="">-- Choose --</option>
      <?php foreach ($defendants as $d): ?>
        <option value="<?= $d['defendant_ID'] ?>"><?= htmlspecialchars($d['Name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <button type="submit" class="btn btn-primary mt-3" id="next-btn">Next: Court Event</button>

  <hr>
  <h5>Or Add New Defendant</h5>
  <?php require PARTIALS . '/_defendant_form.php'; ?>
  <button type="submit" class="btn btn-secondary mt-3" id="add-btn">Add New</button>

  <input type="hidden" name="action" id="action-input" value="">
</form>

<script>
  const form = document.getElementById('defendant-form');
  const actionInput = document.getElementById('action-input');

  const nextBtn = document.getElementById('next-btn');
  const addBtn = document.getElementById('add-btn');

  nextBtn.addEventListener('click', function(e) {
    const defendantID = document.getElementById('defendant_ID').value.trim();
    if (!defendantID) {
      e.preventDefault();
      alert("Please select a defendant before continuing.");
    } else {
      actionInput.value = 'select_existing';
    }
  });

  addBtn.addEventListener('click', function(e) {
    const name = form.querySelector('[name="name"]')?.value.trim();
    const dob = form.querySelector('[name="dob"]')?.value.trim();

    if (!name || !dob) {
      e.preventDefault();
      alert("Please enter both name and date of birth to add a new defendant.");
    } else {
      actionInput.value = 'add_new';
    }
  });
</script>