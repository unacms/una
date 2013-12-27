
    <div class="bx-install-initial-langs bx-def-margin-bottom">
    <?php foreach ($aLangs as $aLang): ?>
        <a title="<?php echo bx_html_attribute($aLang['title']); ?>" href="?lang=<?=$aLang['code']; ?>">
            <img src="<?php echo bx_html_attribute($aLang['icon']); ?>" alt="<?php echo bx_html_attribute($aLang['title']); ?>" />
        </a>
    <?php endforeach; ?>
    </div>
