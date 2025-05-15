<h2><?= $isEdit ? 'Edit Lawyer' : 'Add Lawyer' ?></h2>

<form method="POST" action="">
    <?php include PARTIALS . '/_lawyer_form.php'; ?>
    <button id="submit" type="submit" class="btn btn-primary"><?= $isEdit ? 'Update Lawyer' : 'Add Lawyer' ?></button>
</form>

<script>
document.getElementById('submit').addEventListener('click', function(e) {
  // Get form elements for validation
  const name = document.querySelector('[name="name"]').value.trim();

  // Check if any field is empty
  if (!name) {
    e.preventDefault();  // Prevent form submission
    alert("Please fill in name before adding the lawyer.");
  }
});
</script>
