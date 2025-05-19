<h2>Edit Case ID: <?= htmlspecialchars($caseID) ?></h2>

<?php if (!empty($_GET['success'])): ?>
  <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php endif; ?>

<h3>Charges</h3>
<ul>
    <?php foreach ($charges as $charge): ?>
        <li>
            <?= htmlspecialchars($charge['Description']) ?> (Status: <?= htmlspecialchars($charge['Status']) ?>)
            <a href="<?= BASE_URL ?>/charge/edit/<?= $charge['charge_ID'] ?>?caseID=<?= $caseID ?>" class="btn btn-sm btn-secondary">Edit</a>
            <a href="<?= BASE_URL ?>/charge/delete/<?= $charge['charge_ID'] ?>?caseID=<?= $caseID ?>" class="btn btn-sm btn-danger">Delete</a>
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
            <a href="<?= BASE_URL ?>/event/delete/<?= $event['Event_ID'] ?>?caseID=<?= $caseID ?>" class="btn btn-sm btn-danger">Delete</a>
        </li>
    <?php endforeach; ?>
</ul>

<a href="<?= BASE_URL ?>/event/add?caseID=<?= $caseID ?>" class="btn btn-primary mt-3">Add Event</a>
<hr>

<a href="<?= BASE_URL ?>/case/manage" class="btn btn-secondary mt-3">Back to Case List</a>
