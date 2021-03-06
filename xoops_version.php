<?php

/*
* $Id: xoops_version.php,v 1.1 2006/03/27 08:05:25 mikhail Exp $
* Licence: GNU
*/
$modversion['name'] = _MI_FAQM_NAME;
$modversion['version'] = '1.2';
$modversion['description'] = _MI_FAQMD_DESC;
$modversion['author'] = 'Catzwolf & ghosteEye';
$modversion['credits'] = "Thanks X-Mode, the Xoops Core team and 'Tom' for bugging me to do this module<p></p>Re-made in Vietnam >_< <br> This is Test version, use it by yourseft";
//$modversion['help'] = "wffaq.html";
$modversion['license'] = 'GPL see LICENSE';
$modversion['official'] = 0;
$modversion['image'] = 'images/wfecblogo.png';
$modversion['dirname'] = 'ecoTut';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = 'ecotu_faqsupcat';
$modversion['tables'][1] = 'ecotu_faqcategories';
$modversion['tables'][2] = 'ecotu_faqtopics';

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = 'wffaq_search';

// Menu
$modversion['hasMain'] = 1;
$modversion['sub'][1]['name'] = _MI_FAQSUB_SMNAME1;
$modversion['sub'][1]['url'] = 'submit.php?op=add';

// Templates
$modversion['templates'][1]['file'] = 'ecotut_category.html';
$modversion['templates'][1]['description'] = 'Display category';
$modversion['templates'][2]['file'] = 'ecotut_index.html';
$modversion['templates'][2]['description'] = 'Display index (not use)';
$modversion['templates'][3]['file'] = 'ecotut_answer.html';
$modversion['templates'][3]['description'] = 'Display answer';
$modversion['templates'][4]['file'] = 'ecotut_supcat.html';
$modversion['templates'][4]['description'] = 'Display supercategories';
$modversion['templates'][5]['file'] = 'ecotut_topic.html';
$modversion['templates'][5]['description'] = 'Display supercategories';
$modversion['templates'][6]['file'] = 'ecotut_editcp.html';
$modversion['templates'][6]['description'] = 'Display Editor Control';

// Blocks
$modversion['blocks'][1]['file'] = 'tut_menusupcat.php';
$modversion['blocks'][1]['name'] = _MI_SUPCAT_NAME;
$modversion['blocks'][1]['description'] = 'Shows menu supercategories';
$modversion['blocks'][1]['show_func'] = 'b_menu_supcat_show';
$modversion['blocks'][1]['template'] = 'tut_block_menu.html';
