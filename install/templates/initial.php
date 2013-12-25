<div class="bx-install-initial">
    <img class="bx-def-margin-bottom" src="img/dolphin.png" />
    <a href="javascript:void(0);" class="bx-btn bx-btn-primary">Start installation</a>
    <a href="?action=audit" class="bx-btn">Server audit</a>
    <?php if ($aWarnings): ?>
    <div class="bx-install-initial-waring bx-def-margin-top">
        Your hosting setup has some warnings, for the complete report go to <a href="?action=audit">server audit page</a>.
    </div>
    <?php endif; ?>
</div>
