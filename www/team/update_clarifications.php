<?php
/**
 * Supply information for AJAX RPC calls (update the number
 * of new clarifications in the menu line).
 *
 * $Id$
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */
require('init.php');

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Wed, 10 Feb 1971 05:00:00 GMT");
header("Content-type: text/plain");

$cid = getCurContest();
echo (int) $DB->q('VALUE SELECT COUNT(*) FROM team_unread
                   LEFT JOIN clarification ON(mesgid=clarid)
                   WHERE type="clarification" AND teamid = %s
                   AND team_unread.cid = %i', $login, $cid);
