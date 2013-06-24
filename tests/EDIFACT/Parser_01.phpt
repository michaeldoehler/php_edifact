--TEST--
EDI_EDIFACT_Parser test 01
--FILE--
<?php

require_once dirname(__FILE__) . '/../tests.inc.php';

try {
    $parser = EDI::parserFactory('EDIFACT');
    $edidoc = $parser->parse(TEST_DATA_DIR . '/EDIFACT/ex1.edi');
    echo $edidoc->toEDI();
} catch (Exception $exc) {
    echo $exc->getMessage();
    exit(1);
}

?>
--EXPECT--
UNA:+.? '
UNB+UNOC:3+Senderkennung+Empfaengerkennung+060620:0931+1++1234567'
UNH+1+ORDERS:D:96A:UN'
BGM+220+B10001'
DTM+4:20060620:102'
NAD+BY+++Bestellername+Strasse+Stadt++23436+xx'
LIN+1++Produkt 2:SA'
QTY+1:1000'
LIN+1++Produkt 2:SA'
QTY+1:1000'
UNS+S'
CNT+2:1'
UNT+9+1'
UNZ+1+1234567'
