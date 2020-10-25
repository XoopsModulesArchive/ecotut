<?php
/* NOT USE=====================================================
* $Id: upgrade.php,v 1.1 2006/03/27 08:05:25 mikhail Exp $
* Licence: GNU
*/ === === === === === === === === === === === === === === === === === === === === =

include '../../mainfile.php';
require XOOPS_ROOT_PATH.'/header.php';

#
# # Add new Table structure for table `xoops_faqtopics`
#

global $xoopsUser, $xoopsDB, $xoopsConfig;

$result = $GLOBALS['xoopsDB']->queryF('ALTER TABLE '.$xoopsDB->prefix('faqtopics')." ADD uid int(6) default '1', ADD submit int(1) NOT NULL default '0', ADD summary text NULL ADD datesub int(11) NOT NULL default '1033141070', ADD counter int(8) unsigned NOT NULL default '0' ");
$result = $GLOBALS['xoopsDB']->queryF('ALTER TABLE '.$xoopsDB->prefix('faqtopics').' DELETE keywords');

#
## End of update hopefully
#



$result = $GLOBALS['xoopsDB']->queryF('SELECT * FROM '.$xoopsDB->prefix('faqtopics')." WHERE topicID <>'0' ");

    $GLOBALS['xoopsDB']->queryF('UPDATE '.$xoopsDB->prefix('faqtopics')." SET submit = submit + 1 WHERE submit = '0'" );

OpenTable();

echo 'Updates to the database completed!';



CloseTable();
require_once XOOPS_ROOT_PATH.'/footer.php';


