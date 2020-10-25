<?php

/*
* $Id: search.inc.php,v 1.1 2006/03/27 08:05:31 mikhail Exp $
* Licence: GNU
*/

function wffaq_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;

    $ret = [];

    if (0 != $userid) {
        return $ret;
    }

    $sql = 'SELECT topicID, catID, question, answer, uid, datesub FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . ' WHERE submit = 1 ';

    // because count() returns 1 even if a supplied variable

    // is not an array, we must check if $querryarray is really an array

    $count = count($queryarray);

    if ($count > 0 && is_array($queryarray)) {
        $sql .= "AND ((question LIKE '%$queryarray[0]%' OR answer LIKE '%$queryarray[0]%')";

        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";

            $sql .= "(question LIKE '%$queryarray[$i]%' OR answer LIKE '%$queryarray[$i]%')";
        }

        $sql .= ') ';
    }

    $sql .= 'ORDER BY topicID DESC';

    $result = $xoopsDB->query($sql, $limit, $offset);

    $i = 0;

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $getSupID = $xoopsDB->query('SELECT supID FROM ' . $xoopsDB->prefix('ecotu_faqcategories ') . " WHERE catID='" . $myrow['catID'] . "'");

        [$supid] = $GLOBALS['xoopsDB']->fetchRow($getSupID);

        $ret[$i]['image'] = 'images/wf.gif';

        $ret[$i]['link'] = 'index.php?op=view&t=' . $myrow['topicID'] . '&sc=' . $supid . '&c=' . $myrow['catID'];

        $ret[$i]['title'] = $myrow['question'];

        $ret[$i]['time'] = $myrow['datesub'];

        $ret[$i]['uid'] = $myrow['uid'];

        $i++;
    }

    return $ret;
}
