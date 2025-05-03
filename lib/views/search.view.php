<h1>Multi-Table Search</h1>

<form method="get" action="<?= dirname($_SERVER['SCRIPT_NAME']) ?>/search">
    <label for="field">Search by:</label>
    <select name="field" id="field">
        <option value="name" <?= ($query['field'] ?? '') === 'name' ? 'selected' : '' ?>>Defendant Name</option>
        <option value="email" <?= ($query['field'] ?? '') === 'email' ? 'selected' : '' ?>>Defendant Email</option>
        <option value="charge" <?= ($query['field'] ?? '') === 'charge' ? 'selected' : '' ?>>Charge Description</option>
        <option value="status" <?= ($query['field'] ?? '') === 'status' ? 'selected' : '' ?>>Charge Status</option>
        <option value="lawyer" <?= ($query['field'] ?? '') === 'lawyer' ? 'selected' : '' ?>>Lawyer Name</option>
        <option value="event" <?= ($query['field'] ?? '') === 'event' ? 'selected' : '' ?>>Event Description</option>
    </select>

    <input type="text" name="q" value="<?= htmlspecialchars($query['q'] ?? '') ?>" placeholder="Search term..." />
    <input type="submit" value="Search" />
</form>

<?php if (!empty($results)): ?>
    <ul>
        <?php foreach ($results as $row): ?>
            <li>
                <strong><?= htmlspecialchars($row['Defendant_Name'] ?? '') ?></strong>
                (<?= htmlspecialchars($row['Defendant_Email'] ?? '') ?>)
                <ul>
                    <li>Case ID: <?= htmlspecialchars(strval($row['case_ID'] ?? '')) ?></li>
                    <li>Charge: <?= htmlspecialchars($row['Charge_Description'] ?? '') ?>
                        (<?= htmlspecialchars($row['Charge_Status'] ?? '') ?>)</li>
                    <li>Lawyer: <?= htmlspecialchars($row['Lawyer_Name'] ?? '') ?></li>
                    <li>Event: <?= htmlspecialchars($row['Event_Description'] ?? '') ?> at
                        <?= htmlspecialchars($row['Event_Location'] ?? '') ?> on
                        <?= htmlspecialchars($row['Event_Date'] ?? '') ?></li>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
<?php elseif (!empty($query['q'])): ?>
    <p>No results found for "<strong><?= htmlspecialchars($query['q']) ?></strong>"
        in <?= ucfirst($query['field'] ?? 'that field') ?>.</p>
<?php endif; ?>
<?php if (empty($query['q'])): ?>
    <p>Enter a search term to begin.</p>
<?php endif; ?>