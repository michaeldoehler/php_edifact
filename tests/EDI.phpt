--TEST--
EDI test
--FILE--
<?php

require_once dirname(__FILE__) . '/tests.inc.php';

try {
    $parser = EDI::parserFactory('EDIFACT');
    var_dump($parser instanceof EDI_EDIFACT_Parser);
    $parser = EDI::parserFactory('unknown');
} catch (Exception $exc) {
    echo $exc->getMessage() . "\n";
}
try {
    $parser = EDI::interchangeFactory('EDIFACT');
    var_dump($parser instanceof EDI_EDIFACT_Interchange);
    $parser = EDI::interchangeFactory('unknown');
} catch (Exception $exc) {
    echo $exc->getMessage() . "\n";
}

?>
--EXPECT--
bool(true)
Unsupported standard unknown.
bool(true)
Unsupported standard unknown.
