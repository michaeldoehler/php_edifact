--TEST--
EDI_EDIFACT_Parser test 06
--FILE--
<?php

require_once dirname(__FILE__) . '/../tests.inc.php';

try {
    $parser = EDI::parserFactory('EDIFACT');
    $edidoc = $parser->parse(TEST_DATA_DIR . '/EDIFACT/ex2.edi');
    $elts   = $edidoc->find('nameAndAddressLine');
    echo $elts[0]->getValue() . "\n" . $elts[1]->getValue() . "\n";
    $elts[0]->setValue('Foo ?');
    $elts[1]->setValue('Bar + Baz + ?');
    echo $elts[0]->getValue() . "\n" . $elts[1]->getValue() . "\n";
} catch (Exception $exc) {
    echo $exc->getMessage();
    exit(1);
}

?>
--EXPECT--
Fahrradhandel Pedal
Huber GmbH
Foo ?
Bar + Baz + ?
