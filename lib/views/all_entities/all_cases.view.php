<h2>Manage Cases</h2>
<a href="<?= BASE_URL ?>/cases" class="btn btn-outline-secondary mb-3">← Back</a>

<?php if (empty($cases)): ?>
    <p>No cases found.</p>
<?php else: ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Case ID</th>
                <th>Defendant</th>
                <th>Lawyer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cases as $case): ?>
                <tr>
                    <td><?= htmlspecialchars($case['case_ID']) ?></td>
                    <td><?= htmlspecialchars($case['defendant_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($case['lawyer_name'] ?? 'N/A') ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/case/edit/<?= $case['case_ID'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="<?= BASE_URL ?>/case/delete/<?= $case['case_ID'] ?>" class="btn btn-sm btn-secondary">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
