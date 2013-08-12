<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

class BxBaseConfig extends BxDol {
    /**/
    var    $PageCompThird_db_num                    = 0;

    /*Membership.php*/
    var    $PageCompStatus_db_num                    = 1;
    var    $PageCompSubscriptions_db_num            = 1;
    var    $PageCompMemberships_db_num                = 1;
        //Checkout
    var    $PageCompCheckoutInfo_db_num            = 1;
    var    $PageCompProviderList_db_num            = 1;
    var    $PageCompErrorMessage_db_num            = 1;

    var    $PageExplanation_db_num                    = 1;

        /*    greet.php    */
    var    $PageVkiss_db_num                        = 1;
        /*    compose.php    */
    var    $PageCompose_db_num                        = 0;
        /*    list-pop.php    */
    var    $PageListPop_db_num                        = 1;

        /* calculate page with in: px - pixels, % - percentages */
    var $PageComposeColumnCalculation            = 'px'; //

    //Width of Votes scale at profilr view page
    var    $iProfileViewProgressBar                    = 67;

        // show text link "view as photogallery" in the page navigation of search result page
    var    $show_gallery_link_in_page_navigation    = 1;

    var    $popUpWindowWidth                        = 660;
    var    $popUpWindowHeight                        = 200;

        // Groups
    var $iGroupMembersPreNum                    = 21; //number of random members shown in main page of group
    var $iGroupMembersResPerPage                = 14;

    var $iGroupsSearchResPerPage                = 10;
    var $iGroupsSearchResults_dbnum                = 1;

    var $iQSearchWindowWidth                    = 400;
    var $iQSearchWindowHeight                   = 400;

    var $iTagsMinFontSize                        = 10;  //Minimal font size of tag
    var $iTagsMaxFontSize                        = 30; //Maximal font size of tag

    //var $sCalendarCss;

    var $bAnonymousMode;

    var $bAllowUnicodeInPreg = false; // allow unicode in regular expressions



    function BxBaseConfig() {
        parent::BxDol();

        $this -> bAnonymousMode = getParam('anon_mode');;
    }

    public static function getInstance() {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxTemplConfig();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }
}

