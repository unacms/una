
DELETE FROM `bx_profile_custom_units` WHERE `name` = 'box' OR `name` = 'boxheader';

INSERT INTO `bx_profile_custom_units` (`name`, `caption`, `css_name`, `type`) VALUES
    ('boxcontent', 'Default box content', '#divUnderCustomization .disignBoxFirst .boxContent', 'background'),
    ('boxheader', 'Default box header', '#divUnderCustomization .disignBoxFirst .boxFirstHeader', 'background');

