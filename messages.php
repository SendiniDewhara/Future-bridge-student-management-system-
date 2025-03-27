<?php if (isset($_GET['success'])): ?>
    <div class="alert" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
        <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>
