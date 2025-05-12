<h2>Optional Court Event</h2>

<form method="POST" action="<?= BASE_URL ?>/event/add">
  <?php require PARTIALS . '/_event_form.php'; ?>
  
  <button type="submit" class="btn btn-success">Confirm and Submit Case</button>
</form>

<!-- Displaying added events -->
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
