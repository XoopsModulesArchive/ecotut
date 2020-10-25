<?php

/*
* $Id: index.php,v 1.1 2006/03/27 08:03:52 mikhail Exp $
* Licence: GNU
*/

include 'admin_header.php';

$myts = MyTextSanitizer::getInstance();

$op = '';

foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

foreach ($_GET as $k => $v) {
    ${$k} = $v;
}

xoops_cp_header();
faqlinks();
xoops_cp_footer();
