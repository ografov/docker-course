<?php
/**
 * @var array $services
 */
?>
<?php foreach ($services as $title => $service): ?>
    <div class="card mb-3 <?= $service['status'] ? 'border-success' : 'border-danger' ?>">
        <div class="card-header">
            <b><?= $title ?></b>
            on <i><?= $service['dsn'] ?></i>
            <?php if ($service['status']): ?>
                <span class="badge badge-success">OK</span>
            <?php else: ?>
                <span class="badge badge-danger">FAIL</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <p class="card-text">
                <?= $service['info'] ?>
            </p>
        </div>
    </div>
<?php endforeach; ?>