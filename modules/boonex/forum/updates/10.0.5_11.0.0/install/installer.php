<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxForumUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }
    
    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_forum_discussions', 'multicat'))
                $this->oDb->query("ALTER TABLE `bx_forum_discussions` ADD `multicat` text NOT NULL AFTER `cat`");
            if(!$this->oDb->isFieldExists('bx_forum_discussions', 'labels'))
                $this->oDb->query("ALTER TABLE `bx_forum_discussions` ADD `labels` text NOT NULL AFTER `lr_comment_id`");

            $aCmts = $this->oDb->getAll("SELECT * FROM `bx_forum_cmts` WHERE `cmt_level`>'0' AND `cmt_vparent_id`='0'");
            foreach($aCmts as $aCmt) {
                if((int)$aCmt['cmt_level'] == 1) {
                    $this->oDb->query("UPDATE `bx_forum_cmts` SET `cmt_vparent_id`=`cmt_parent_id` WHERE `cmt_id`=:cmt_id LIMIT 1", array('cmt_id' => (int)$aCmt['cmt_id']));
                    continue;
                }

                $iParent = (int)$aCmt['cmt_parent_id'];
                while(true) {
                    $aParent = $this->oDb->getRow("SELECT * FROM `bx_forum_cmts` WHERE `cmt_id`=:cmt_id LIMIT 1", array('cmt_id' => $iParent));
                    if((int)$aParent['cmt_level'] == 0)
                        break;

                    $iParent = $aParent['cmt_parent_id'];
                }

                $this->oDb->query("UPDATE `bx_forum_cmts` SET `cmt_vparent_id`=:cmt_parent_id WHERE `cmt_id`=:cmt_id LIMIT 1", array(
                    'cmt_parent_id' => $iParent, 
                    'cmt_id' => (int)$aCmt['cmt_id']
                ));
            }
        }

        return parent::actionExecuteSql($sOperation);
    }
}
