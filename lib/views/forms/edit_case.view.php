<h2>Edit Case ID: <?= htmlspecialchars($caseID) ?></h2>
<a href="<?= BASE_URL ?>/cases" class="btn btn-outline-secondary mb-3r">← Back to Case List</a>
<div class="my-3"></div>

<?php if (!empty($_GET['success'])): ?>
  <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php endif; ?>

<h3>Charges</h3>
<ul>
    <?php foreach ($charges as $charge): ?>
        <li>
            <?= htmlspecialchars($charge['Description']) ?> (Status: <?= htmlspecialchars($charge['Status']) ?>)
            <a href="<?= BASE_URL ?>/charge/edit/<?= $charge['charge_ID'] ?>?caseID=<?= $caseID ?>" class="btn btn-sm btn-secondary">Edit</a>
            <a href="<?= BASE_URL ?>/charge/delete/<?= $charge['charge_ID'] ?>?caseID=<?= $caseID ?>" 
                class="btn btn-sm btn-danger"
                onclick="return confirm('Are you sure you want to delete this charge?');">
                Delete
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<a href="<?= BASE_URL ?>/charge/add?caseID=<?= $caseID ?>" class="btn btn-primary">Add Charge</a>

<hr>

<h3>Events</h3>
<ul>
    <?php foreach ($events as $event): ?>
        <li>
            <?= htmlspecialchars($event['Description']) ?> on <?= htmlspecialchars($event['Date']) ?> at <?= htmlspecialchars($event['Location']) ?>
            <a href="<?= BASE_URL ?>/event/edit/<?= $event['Event_ID'] ?>?caseID=<?= $caseID ?>" class="btn btn-sm btn-secondary">Edit</a>
            <a href="<?= BASE_URL ?>/event/delete/<?= $event['Event_ID'] ?>?caseID=<?= $caseID ?>" 
                class="btn btn-sm btn-danger"
                onclick="return confirm('Are you sure you want to delete this event?');">
                Delete
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<a href="<?= BASE_URL ?>/event/add?caseID=<?= $caseID ?>" class="btn btn-primary mt-3">Add Event</a>
<hr>
