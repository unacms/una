<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxPostsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isIndexExists('bx_posts_polls', 'content_id'))
                $this->oDb->query("ALTER TABLE `bx_posts_polls` ADD KEY `content_id` (`content_id`)");

            if(!$this->oDb->isIndexExists('bx_posts_polls_answers', 'poll_id'))
                $this->oDb->query("ALTER TABLE `bx_posts_polls_answers` ADD KEY `poll_id` (`poll_id`)");
        }

    	return parent::actionExecuteSql($sOperation);
    }
}
