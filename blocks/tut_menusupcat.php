<?php

/*
* $Id: tut_menusupcat.php,v 1.1 2006/03/27 08:05:22 mikhail Exp $
* Licence: GNU
*/

function b_menu_supcat_show()
{
    global $xoopsDB;

    $block = [];

    $result = $xoopsDB->query('SELECT supID, name FROM ' . $xoopsDB->prefix('ecotu_faqsupcat') . ' WHERE 1');

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $block['stories'][] = $myrow;
    }

    return $block;
}
