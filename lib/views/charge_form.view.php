<h2>Enter Charge Information</h2>

<form method="POST" action="<?= BASE_URL ?>/charge/add">
  <?php require PARTIALS . '/_charge_form.php'; ?>

  

  <!-- Display the charges already added -->
  <?php if (isset($charges) && count($charges) > 0): ?>
      <h3>Current Charges:</h3>
      <ul>
          <?php foreach ($charges as $charge): ?>
              <li><?php echo htmlspecialchars($charge['description']); ?> - <?php echo htmlspecialchars($charge['status']); ?></li>
          <?php endforeach; ?>
      </ul>
  <?php endif; ?>
  <!-- Add More Charges button -->
  <button type="submit" name="add_more" class="btn btn-secondary mt-3">Add Another Charge</button>
  <button type="submit" class="btn btn-primary mt-3">Next: Lawyer</button>
</form>


