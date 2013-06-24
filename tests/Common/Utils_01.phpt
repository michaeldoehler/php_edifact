--TEST--
EDI_Common_Utils_splitEDIString test
--FILE--
<?php

require_once dirname(__FILE__) . '/../tests.inc.php';

$tokens = EDI_Common_Utils_splitEDIString(
    "some string that will be cut here' but not here?'...",
    "'",
    "?"
);
print_r($tokens);

?>
--EXPECT--
Array
(
    [0] => some string that will be cut here
    [1] =>  but not here?'...
)
