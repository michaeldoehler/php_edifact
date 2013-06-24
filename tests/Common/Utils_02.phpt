--TEST--
EDI_Common_Utils_escapeEDIString test
--FILE--
<?php

require_once dirname(__FILE__) . '/../tests.inc.php';

echo EDI_Common_Utils_escapeEDIString("String: ?'+*", "?", "'", "+", ":", "*");

?>
--EXPECT--
String?: ???'?+?*
