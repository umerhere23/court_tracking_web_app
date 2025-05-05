<div class="mb-3">
<input type="text" class="form-control mb-2" name="charge_description" placeholder="Charge Description"
        value="<?= htmlspecialchars($prefill['charge_description'] ?? '') ?>" required>
<input type="text" class="form-control mb-2" name="charge_status" placeholder="Charge Status"
        value="<?= htmlspecialchars($prefill['charge_status'] ?? '') ?>" required>
</div>