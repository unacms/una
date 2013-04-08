<?php

$aLangContent = array(
    '_adm_mmi_antispam' => 'Antispam Tools',
    '_sys_adm_title_dnsbl_log' => 'DNSBL Block Log',
    '_sys_adm_title_dnsbluri_log' => 'DNSBL URI Block Log',
    '_sys_adm_title_akismet_log' => 'Akismet Block Log',
    '_sys_adm_title_dnsbl_recheck' => 'Recheck IP',
    '_sys_adm_title_dnsbluri_recheck' => 'Recheck URL',
    '_sys_adm_page_cpt_dnsbl' => 'DNS Blocklists',
    '_sys_adm_page_cpt_uridnsbl' => 'URI DNS Blocklists',
    '_sys_adm_page_cpt_akismet' => 'Akismet',
    '_sys_adm_btn_dnsbl_recheck' => 'Recheck',
    '_sys_adm_btn_dnsbl_log' => 'Log',
    '_sys_adm_btn_dnsbl_delete' => 'Delete',
    '_sys_adm_btn_dnsbl_activate' => 'Activate',
    '_sys_adm_btn_dnsbl_deactivate' => 'Deactivate',
    '_sys_adm_btn_dnsbl_add' => 'Add',
    '_sys_adm_btn_dnsbl_settings' => 'Settings',
    '_sys_adm_btn_dnsbl_help' => 'Help',
    '_sys_adm_btn_dnsbl_help_text' => 'DNSBL - <a href="http://en.wikipedia.org/wiki/DNSBL">Domain Name Service Block Lists</a>.
This functionality allows to block by IP address if it exists in one of available blocklists.
Originally it is designed to identify email spammers, but it is suitable for websites too.<br /><br />
Use many services which provides lists of spammer IPs for blocking.
You can refer to the following sites for more such services: <br />
<a href="http://en.wikipedia.org/wiki/Comparison_of_DNS_blacklists">http://en.wikipedia.org/wiki/Comparison_of_DNS_blacklists</a><br />
<a href="http://www.dnsbl.info/">http://www.dnsbl.info/</a><br />
<a href="http://stats.dnsbl.com/">http://stats.dnsbl.com/</a><br />
<a href="http://www.moensted.dk/spam/">http://www.moensted.dk/spam/</a>',
    '_sys_adm_btn_dnsbluri_help_text' => 'A URI DNSBL is a DNSBL that lists the domain names and IP addresses which are found in the "clickable" links contained in the body of spams, but generally not found inside legitimate messages.
This antispam method scan submitted content for the urls and check them if any of them is a link to spam site. If such url detected in the text then content is not submitted.
<br /><br />
More info about URI DNSBL: <br />
<a href="http://www.surbl.org/">http://www.surbl.org/</a> <br />
<a href="http://dnsbl.invaluement.com/ivmuri/">http://dnsbl.invaluement.com/ivmuri/</a>',
    '_sys_adm_form_err_required_field' => 'This is required field',
    '_sys_adm_fld_dnsbl_chain' => 'Chain',
    '_sys_adm_fld_dnsbl_zonedomain' => 'Domain zone',
    '_sys_adm_fld_dnsbl_postvresp' => 'Return value ("any" - any result)',
    '_sys_adm_fld_dnsbl_url' => 'URL',
    '_sys_adm_fld_dnsbl_recheck_url' => 'Recheck URL',
    '_sys_adm_fld_dnsbl_comment' => 'Comment',
    '_sys_adm_fld_dnsbl_active' => 'Active',
    '_sys_adm_fld_dnsbl_recheck' => 'Recheck IP',
    '_sys_adm_fld_dnsbluri_recheck' => 'Recheck Domain',
    '_sys_sucess_result' => 'Data has been succesfully submited.',
    '_sys_spam_detected' => 'Sorry, it looks like you are trying to submit spam, if you believe that it is not spam please submit false positive report here: %s',
    '_sys_adm_enabled' => 'Enabled',
    '_sys_adm_disabled' => 'Disabled',
    '_sys_adm_akismet_key_valid' => 'Your Akismet key is valid.',
    '_sys_adm_akismet_key_invalid' => 'Your Akismet key is invalid.',
    '_sys_adm_akismet_key_empty' => 'Your Akismet key is not specified - get <a target="_blank" href="http://wordpress.com/api-keys/">WordPress API key</a>',
    '_sys_adm_dnsbl_listed' => 'LISTED',
    '_sys_adm_dnsbl_not_listed' => 'Not Listed',
    '_sys_adm_dnsbl_failed' => 'Failed',
    '_User was removed from block list' => 'User was removed from block list',
    '_adm_admtools_cache_engines' => 'Cache Engines',
);

?>
