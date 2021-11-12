-- SETTINGS
UPDATE `sys_options` SET `value`="{'moreText': {'buttons': ['bold', 'italic', 'underline','alignLeft', 'alignCenter', 'alignRight', 'formatOL', 'formatUL','insertLink', 'insertImage'],'buttonsVisible': 40},'moreMisc': {'buttons': [ 'fullscreen', 'html'],'align': 'right','buttonsVisible': 2}}" WHERE `name`='bx_froala_option_toolbar_standard';


-- PRELOADER
UPDATE `sys_preloader` SET `content`='modules/boonex/froala/plugins/froala/css/|froala_style.min.css' WHERE `module`='bx_froala' AND `content`='{dir_plugins_modules}boonex/froala/plugins/froala/css/|froala_style.min.css';