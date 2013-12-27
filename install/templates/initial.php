<div class="bx-install-initial">

    <img class="bx-def-margin-bottom bx-install-dolphin" src="img/dolphin.png" alt="Dolphin 8" />

    <?php include('lang_swither.php'); ?>

    <a href="javascript:void(0);" class="bx-btn bx-btn-primary"><? echo _t('_sys_inst_start_installation'); ?></a>
    <a href="?action=audit" class="bx-btn"><? echo _t('_sys_inst_server_audit'); ?></a>

    <?php if ($aWarnings): ?>
    <div class="bx-install-initial-waring bx-def-margin-top">
        <?php echo _t('_sys_inst_hosting_warnings'); ?>
    </div>
    <?php endif; ?>

</div>
