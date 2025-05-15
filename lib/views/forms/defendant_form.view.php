<h2><?= $isEdit ? 'Edit Defendant' : 'Add Defendant' ?></h2>

<form method="POST" action="">
    <?php include PARTIALS . '/_defendant_form.php'; ?>
    <button id="submit" type="submit" class="btn btn-primary"><?= $isEdit ? 'Update Defendant' : 'Add Defendant' ?></button>
</form>

<script>
document.getElementById('submit').addEventListener('click', function(e) {
  // Get form elements for validation
  const name = document.querySelector('[name="name"]').value.trim();
  const dob = document.querySelector('[name="dob"]').value.trim();

  // Check if any field is empty
  if (!name || !dob) {
    e.preventDefault();  // Prevent form submission
    alert("Please fill in name and date of birth before adding the defendant.");
  }
});
</script>
