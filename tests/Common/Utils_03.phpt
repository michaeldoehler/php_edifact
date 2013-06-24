--TEST--
EDI_Common_Utils_unescapeEDIString test
--FILE--
<?php

require_once dirname(__FILE__) . '/../tests.inc.php';

echo EDI_Common_Utils_unescapeEDIString("String: ???'?+?*", "?", "'", "+", ":", "*");

?>
--EXPECT--
String: ?'+*
