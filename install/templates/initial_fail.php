<div class="bx-install-initial-fail">
    Your hosting doesn't meet minimal requirements:
    <ul>
    <?php foreach ($aErrors as $sMsg): ?>
        <li><?=$sMsg; ?></li>
    <?php endforeach; ?>
    </ul>
    <a href="?action=audit" class="bx-btn">Server audit</a>
</div>
