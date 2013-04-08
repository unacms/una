<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolDb');

function db_list_tables( $error_checking = true ) {
    BxDolDb::getInstance()->setErrorChecking ($error_checking);
    return BxDolDb::getInstance()->listTables();
}

function db_get_encoding ( $error_checking = true ) {
    BxDolDb::getInstance()->setErrorChecking ($error_checking);
    return BxDolDb::getInstance()->getEncoding();
}

function db_res( $query, $error_checking = true ) {
    BxDolDb::getInstance()->setErrorChecking ($error_checking);
    return BxDolDb::getInstance()->res($query);
}

function db_last_id() {
    return BxDolDb::getInstance()->lastId();
}

function db_affected_rows() {
    return BxDolDb::getInstance()->getAffectedRows();
}

function db_res_assoc_arr( $query, $error_checking = true ) {
    BxDolDb::getInstance()->setErrorChecking ($error_checking);
    return BxDolDb::getInstance()->getAll($query);
}

function db_arr( $query, $error_checking = true ) {
    BxDolDb::getInstance()->setErrorChecking ($error_checking);
    return BxDolDb::getInstance()->getRow($query, MYSQL_BOTH);
}

function db_assoc_arr( $query, $error_checking = true ) {
    BxDolDb::getInstance()->setErrorChecking ($error_checking);
    return BxDolDb::getInstance()->getRow($query);
}

function db_value( $query, $error_checking = true, $index = 0 ) {
    BxDolDb::getInstance()->setErrorChecking ($error_checking);
    return BxDolDb::getInstance()->getOne($query, $index);
}

function fill_array( $res ) {
    return BxDolDb::getInstance()->fillArray($res, MYSQL_BOTH);
}

function fill_assoc_array( $res ) {
    return BxDolDb::getInstance()->fillArray($res, MYSQL_ASSOC);
}

function isParam( $param_name, $use_cache = true ) {
    return BxDolDb::getInstance()->isParam($param_name, $use_cache);
}

function addParam($sName, $sValue, $iKateg, $sDesc, $sType) {
	return BxDolDb::getInstance()->addParam($sName, $sValue, $iKateg, $sDesc, $sType);
}

function getParam( $param_name, $use_cache = true ) {
    return BxDolDb::getInstance()->getParam($param_name, $use_cache);
}

function getParamDesc( $param_name ) {
    return BxDolDb::getInstance()->getOne ("SELECT `desc` FROM `sys_options` WHERE `Name` = '$param_name'");
}

function setParam( $param_name, $param_val ) {
    return BxDolDb::getInstance()->setParam($param_name, $param_val);
}

