<?php

    $aStoragesTranscodersWithWebmFiles = array(
        'bx_videos_media_resized' => 'bx_videos_video_webm',
        'bx_albums_photos_resized' => 'bx_albums_video_webm',
        'bx_timeline_videos_processed' => 'bx_timeline_videos_webm',
    );

    if (!$this->oDb->isFieldExists('sys_objects_auths', 'Style'))
        $this->oDb->query("ALTER TABLE `sys_objects_auths` ADD `Style` varchar(255) NOT NULL AFTER `Icon`");

    if (!$this->oDb->isFieldExists('sys_options_mixes', 'published'))
        $this->oDb->query("ALTER TABLE `sys_options_mixes` ADD `published` tinyint(1) NOT NULL default '0' AFTER `active`");

    if (!$this->oDb->isFieldExists('sys_form_pre_values', 'Data'))
        $this->oDb->query("ALTER TABLE `sys_form_pre_values` ADD `Data` text NOT NULL default '' AFTER `LKey2`");

    if ($this->oDb->isFieldExists('sys_form_pre_values', 'Data')) {
        $this->oDb->query("DELETE FROM `sys_form_pre_values` WHERE `Key` = 'sys_vote_reactions'");
        $sQuery = <<<EOF
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`, `Data`) VALUES
('sys_vote_reactions', 'like', 1, '_sys_pre_lists_vote_reactions_like', '', 'a:2:{s:4:"icon";s:9:"thumbs-up";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'love', 2, '_sys_pre_lists_vote_reactions_love', '', 'a:2:{s:4:"icon";s:26:"heart sys-colored col-red1";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'joy', 3, '_sys_pre_lists_vote_reactions_joy', '', 'a:2:{s:4:"icon";s:32:"grin-squint sys-colored col-red3";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'surprise', 4, '_sys_pre_lists_vote_reactions_surprise', '', 'a:2:{s:4:"icon";s:29:"surprise sys-colored col-red3";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'sadness', 5, '_sys_pre_lists_vote_reactions_sadness', '', 'a:2:{s:4:"icon";s:29:"sad-tear sys-colored col-red3";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'anger', 6, '_sys_pre_lists_vote_reactions_anger', '', 'a:2:{s:4:"icon";s:26:"angry sys-colored col-red3";s:6:"weight";s:1:"1";}');
EOF;
        $this->oDb->query($sQuery);

        $this->oDb->query("DELETE FROM `sys_form_pre_values` WHERE `Key` = 'sys_relations'");
        $sQuery = <<<EOF
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`, `Data`) VALUES
('sys_relations', '1', 1, '_sys_pre_lists_relations_husband', '', 'a:1:{i:0;s:1:"2";}'),
('sys_relations', '2', 2, '_sys_pre_lists_relations_wife', '', 'a:1:{i:0;s:1:"1";}'),
('sys_relations', '3', 3, '_sys_pre_lists_relations_father', '', 'a:2:{i:0;s:1:"5";i:1;s:1:"6";}'),
('sys_relations', '4', 4, '_sys_pre_lists_relations_mother', '', 'a:2:{i:0;s:1:"5";i:1;s:1:"6";}'),
('sys_relations', '5', 5, '_sys_pre_lists_relations_son', '', 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}'),
('sys_relations', '6', 6, '_sys_pre_lists_relations_daughter', '', 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}'),
('sys_relations', '7', 7, '_sys_pre_lists_relations_brother', '', 'a:2:{i:0;s:1:"7";i:1;s:1:"8";}'),
('sys_relations', '8', 8, '_sys_pre_lists_relations_sister', '', 'a:2:{i:0;s:1:"7";i:1;s:1:"8";}');
EOF;
        $this->oDb->query($sQuery);
    }

    // delete webm files
    foreach ($aStoragesTranscodersWithWebmFiles as $sStorage => $sTranscoder) {
        $o = BxDolStorage::getObjectInstance($sStorage);
        $sTableFiles = $this->oDb->getOne("SELECT `table_files` FROM `sys_objects_storage` WHERE `object` = :storage", array('storage' => $sStorage));
        if (!$o || !$sTableFiles)
            continue;

        $aFilesIds = $this->oDb->getColumn("SELECT `id` FROM `{$sTableFiles}` WHERE `ext` = 'webm'");
        if ($aFilesIds)
            $o->queueFilesForDeletion($aFilesIds);

        $this->oDb->query("DELETE FROM `sys_transcoder_videos_files` WHERE `transcoder_object` = :transcoder", array('transcoder' => $sTranscoder));
    }

    return true;
