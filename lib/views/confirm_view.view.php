<h2>Review Case Details</h2>

<p><strong>Defendant:</strong> <?= htmlspecialchars($defendant['Name']) ?></p>
<p><strong>Charge:</strong> <?= htmlspecialchars($case['charge_description']) ?></p>
<p><strong>Lawyer:</strong> <?= htmlspecialchars($lawyer['Name']) ?></p>
<?php if (!empty($event['description'])): ?>
    <p><strong>Event:</strong> <?= htmlspecialchars($event['description']) ?> on <?= $event['date'] ?> at <?= htmlspecialchars($event['location']) ?></p>
<?php else: ?>
    <p><strong>Event:</strong> None</p>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/case/confirm">
    <button type="submit" class="btn btn-success">Submit Case</button>
</form>