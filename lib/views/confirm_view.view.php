<h2>Review Case Details</h2>

<p><strong>Defendant:</strong> <?= htmlspecialchars($defendant['Name']) ?></p>

<?php if (!empty($_SESSION['case']['charges'])): ?>
    <p><strong>Charges:</strong></p>
    <ul>
        <?php foreach ($_SESSION['case']['charges'] as $charge): ?>
            <li><?= htmlspecialchars($charge['description']) ?> (<?= htmlspecialchars($charge['status']) ?>)</li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p><strong>Lawyer:</strong> <?= htmlspecialchars($lawyer['Name']) ?></p>

<?php if (!empty($_SESSION['case']['events'])): ?>
    <p><strong>Events:</strong></p>
    <ul>
        <?php foreach ($_SESSION['case']['events'] as $event): ?>
            <li>
                <?= htmlspecialchars($event['description']) ?> on <?= htmlspecialchars($event['date']) ?> at <?= htmlspecialchars($event['location']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p><strong>Events:</strong> None</p>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/case/confirm">
    <button type="submit" class="btn btn-success">Submit Case</button>
</form>
