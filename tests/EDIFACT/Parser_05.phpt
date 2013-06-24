--TEST--
EDI_EDIFACT_Parser test 05
--FILE--
<?php

require_once dirname(__FILE__) . '/../tests.inc.php';

try {
    $parser = EDI::parserFactory('EDIFACT');
    $edidoc = $parser->parse(TEST_DATA_DIR . '/EDIFACT/ex5.edi');
    echo $edidoc->toEDI();
} catch (Exception $exc) {
    echo $exc->getMessage();
    exit(1);
}

?>
--EXPECT--
UNA:+.? '
UNB+UNOA:3+STYLUSSTUDIO:1+DATADIRECT:1+20051107:1159+6002'
UNH+SSDD1+ORDERS:D:03B:UN:EAN008'
BGM+220+BKOD99+9'
DTM+137:20051107:102'
NAD+BY+5412345000176::9'
NAD+SU+4012345000094::9'
LIN+1+1+0764569104:IB'
QTY+1:25'
FTX+AFM+1++XPath 2.0 Programmer?'s Reference'
LIN+2+1+0764569090:IB'
QTY+1:25'
FTX+AFM+1++XSLT 2.0 Programmer?'s Reference'
LIN+3+1+1861004656:IB'
QTY+1:16'
FTX+AFM+1++Java Server Programming'
LIN+4+1+0596006756:IB'
QTY+1:10'
FTX+AFM+1++Enterprise Service Bus'
UNS+S'
CNT+2:4'
UNT+22+SSDD1'
UNZ+1+6002'
