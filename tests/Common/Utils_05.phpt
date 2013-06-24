--TEST--
EDI_Common_Utils_unescapeXmlString test
--FILE--
<?php

require_once dirname(__FILE__) . '/../tests.inc.php';

echo EDI_Common_Utils_unescapeXmlString('a string &amp; another &lt;string&gt;');

?>
--EXPECT--
a string & another <string>
