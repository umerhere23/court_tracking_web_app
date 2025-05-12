<h2>Optional Court Event</h2>

<form method="POST" action="<?= BASE_URL ?>/event/add" id="event-form">
  <?php require PARTIALS . '/_event_form.php'; ?>
  <button type="submit" name="add_more" class="btn btn-secondary mt-3" id="add-more-btn">Add Event</button>
  <button type="submit" class="btn btn-secondary mt-3">Confirm and Submit Case</button>
</form>

<?php if (!empty($_SESSION['case']['events'])): ?>
  <h3>Current Court Events:</h3>
  <ul>
    <?php foreach ($_SESSION['case']['events'] as $event): ?>
      <li>
        <?= htmlspecialchars($event['description']) ?> – 
        <?= htmlspecialchars($event['date']) ?> at 
        <?= htmlspecialchars($event['location']) ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<script>

document.getElementById('add-more-btn').addEventListener('click', function(e) {
  // Get form elements for validation
  const description = document.querySelector('[name="description"]').value.trim();
  const date = document.querySelector('[name="date"]').value.trim();
  const location = document.querySelector('[name="location"]').value.trim();

  // Check if any field is empty
  if (!description || !date || !location) {
    e.preventDefault();  // Prevent form submission
    alert("Please fill in all fields before adding the event.");
  }
});
</script>
