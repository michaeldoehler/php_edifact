--TEST--
EDI_EDIFACT_MappingProvider test 03
--FILE--
<?php

require_once dirname(__FILE__) . '/../tests.inc.php';
require_once 'EDI/EDIFACT/MappingProvider.php';

try {
    $nodes = EDI_EDIFACT_MappingProvider::findCodesForDataElement('1001');
    echo count($nodes);
} catch (Exception $exc) {
    echo $exc->getMessage();
    exit(1);
}

?>
--EXPECT--
652
