--TEST--
EDI_EDIFACT_MappingProvider test 04
--FILE--
<?php

require_once dirname(__FILE__) . '/../tests.inc.php';
require_once 'EDI/EDIFACT/MappingProvider.php';

try {
    $node = EDI_EDIFACT_MappingProvider::find('NONEXISTANTNODE');
} catch (Exception $exc) {
    echo $exc->getMessage();
    exit(1);
}

?>
--EXPECTF--
mapping file "%s/messages/nonexistantnode.xml" not found
