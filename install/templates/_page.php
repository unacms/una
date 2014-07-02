<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?=$sTitle; ?></title>

    <style>

<?=$sInlineCSS; ?>

    </style>
    <link rel="stylesheet" href="css/styles.css" />

<?=$sFilesCSS; ?>

<?=$sFilesJS; ?>

    <script>
        $(document).ready(function () {
            $('#bx-toolbar').css({opacity: 0.9});
        });
    </script>
</head>
<body class="bx-def-font bx-def-color-bg-page">

<div class="bx-install-page">

    <div id="bx-toolbar">
        <div class="bx-page-wrapper bx-def-padding-sec-left bx-def-padding-sec-right bx-def-page-width">
            <div id="bx-toolbar-content" class="bx-clearfix">
                <div id="bx-menu-toolbar-1-container"></div>
                <div id="bx-logo-container"><a class="bx-def-font-contrasted"><?=$sTitle; ?></a></div>
                <div id="bx-menu-toolbar-2-container">
                    <?php if (!empty($aToolbarItem)): ?>
                    <ul class="bx-menu-toolbar bx-menu-object-sys_toolbar_member">
                        <li class="bx-def-color-bg-hl-hover ">
                            <a title="<?=$aToolbarItem['title']; ?>" href="<?=$aToolbarItem['link']; ?>" target="<?=$aToolbarItem['target']; ?>" onclick="" class="bx-def-margin-sec-left bx-def-font-contrasted">
                                <i class="sys-icon <?=$aToolbarItem['icon']; ?> bx-def-margin-sec-leftright"></i>
                            </a>
                        </li>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div id="bx-menu-main-bar-wrapper"></div>

    <div class="bx-page-wrapper bx-def-padding-sec-left bx-def-padding-sec-right bx-def-page-width">
        <div id="bx-content-container" class="bx-def-padding-sec-left bx-def-padding-sec-right">
            <div id="bx-content-main" class="bx-def-margin-top">
                <?=$sCode; ?>
            </div>
        </div>
    </div>
    <div class="bx-install-footer-push"></div>
</div>

<div class="bx-install-footer">
    <div class="bx-page-wrapper bx-def-padding-sec-left bx-def-padding-sec-right bx-def-page-width">
        <div id="bx-content-container" class="bx-def-padding-sec-left bx-def-padding-sec-right bx-clearfix">
            <div class="bx-install-footer-text">BoonEx - Community Software Experts</div>
            <div class="bx-install-footer-copyright">&copy; BoonEx Pty Ltd</div>
        </div>
    </div>
</div>

</body>
</html>
