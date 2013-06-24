--TEST--
EDI_Common_Utils_escapeXmlString test
--FILE--
<?php

require_once dirname(__FILE__) . '/../tests.inc.php';

echo EDI_Common_Utils_escapeXmlString('a string & another <string>');

?>
--EXPECT--
a string &amp; another &lt;string&gt;
