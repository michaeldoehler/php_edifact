--TEST--
EDI_EDIFACT_Parser test 07
--FILE--
<?php

require_once dirname(__FILE__) . '/../tests.inc.php';

try {
    $parser = EDI::parserFactory('EDIFACT');
    $edidoc = $parser->parse(TEST_DATA_DIR . '/EDIFACT/ex1.edi');
    echo $edidoc->toXML(true);
} catch (Exception $exc) {
    echo $exc->getMessage();
    exit(1);
}

?>
--EXPECT--
<?xml version="1.0" encoding="utf-8"?>
<interchange>
    <unb name="interchangeHeader">
        <s001 name="syntaxIdentifier">
            <!-- Coded identification of the agency controlling a syntax and syntax level used in an interchange. -->
            <e0001 name="syntaxIdentifier">UNOC</e0001>
            <!-- Version number of the syntax identified in the syntax identifier (0001) -->
            <e0002 name="syntaxVersionNumber">3</e0002>
        </s001>
        <s002 name="interchangeSender">
            <!-- Name or coded representation of the sender of a data interchange. -->
            <e0004 name="senderIdentification">Senderkennung</e0004>
        </s002>
        <s003 name="interchangeRecipient">
            <!-- Name or coded representation of the recipient of a data interchange. -->
            <e0010 name="recipientIdentification">Empfaengerkennung</e0010>
        </s003>
        <s004 name="datetimeOfPreparation">
            <!-- Local date when an interchange or a functional group was prepared. -->
            <e0017 name="dateOfPreparation">060620</e0017>
            <!-- Local time of day when an interchange or a functional group was prepared. -->
            <e0019 name="timeOfPreparation">0931</e0019>
        </s004>
        <!-- Unique reference assigned by the sender to an interchange. -->
        <e0020 name="interchangeControlReference">1</e0020>
        <s005 name="recipientsReferencePassword">
            <!-- Unique reference assigned by the recipient to the data interchange or a password to the recipient&apos;s system or to a third party network as specified in the partners interchange agreement. -->
            <e0022 name="recipientsReferencepassword"></e0022>
        </s005>
        <!-- Identification of the application area assigned by the sender, to which the messages in the interchange relate e.g. the message identifier if all the messages in the interchange are of the same type. -->
        <e0026 name="applicationReference">1234567</e0026>
    </unb>
    <orders>
        <unh name="messageHeader">
            <!-- Unique message reference assigned by the sender. -->
            <e0062 name="messageReferenceNumber">1</e0062>
            <s009 name="messageIdentifier">
                <!-- Code identifying a type of message and assigned by its controlling agency. -->
                <e0065 name="messageType">ORDERS</e0065>
                <!-- Version number of a message type. -->
                <e0052 name="messageVersionNumber">D</e0052>
                <!-- Release number within the current message type version number (0052). -->
                <e0054 name="messageReleaseNumber">96A</e0054>
                <!-- Code to identify the agency controlling the specification, maintenance and publication of the message type. -->
                <e0051 name="controllingAgency">UN</e0051>
            </s009>
        </unh>
        <bgm name="beginningOfMessage">
            <c002 name="documentmessageName">
                <!-- Document/message identifier expressed in code. -->
                <e1001 name="documentmessageNameCoded">220</e1001>
            </c002>
            <!-- Reference number assigned to the document/message by the issuer. -->
            <e1004 name="documentmessageNumber">B10001</e1004>
        </bgm>
        <dtm name="datetimeperiod">
            <c507 name="datetimeperiod">
                <!-- Code giving specific meaning to a date, time or period. -->
                <e2005 name="datetimeperiodQualifier">4</e2005>
                <!-- The value of a date, a date and time, a time or of a period in a specified representation. -->
                <e2380 name="datetimeperiod">20060620</e2380>
                <!-- Specification of the representation of a date, a date and time or of a period. -->
                <e2379 name="datetimeperiodFormatQualifier">102</e2379>
            </c507>
        </dtm>
        <nad_group>
            <nad name="nameAndAddress">
                <!-- Code giving specific meaning to a party. -->
                <e3035 name="partyQualifier">BY</e3035>
                <c082 name="partyIdentificationDetails">
                    <!-- Code identifying a party involved in a transaction. -->
                    <e3039 name="partyIdIdentification"></e3039>
                </c082>
                <c058 name="nameAndAddress">
                    <!-- Free form name and address description. -->
                    <e3124 name="nameAndAddressLine"></e3124>
                </c058>
                <c080 name="partyName">
                    <!-- Name of a party involved in a transaction. -->
                    <e3036 name="partyName">Bestellername</e3036>
                </c080>
                <c059 name="street">
                    <!-- Street and number in plain language, or Post Office Box No. -->
                    <e3042 name="streetAndNumberpoBox">Strasse</e3042>
                </c059>
                <!-- Name of a city (a town, a village) for addressing purposes. -->
                <e3164 name="cityName">Stadt</e3164>
                <!-- Identification of the name of sub-entities (state, province) defined by appropriate governmental agencies. -->
                <e3229 name="countrySubentityIdentification"></e3229>
                <!-- Code defining postal zones or addresses. -->
                <e3251 name="postcodeIdentification">23436</e3251>
                <!-- Identification of the name of a country or other geographical entity as specified in ISO 3166. -->
                <e3207 name="countryCoded">xx</e3207>
            </nad>
        </nad_group>
        <lin_group>
            <lin name="lineItem">
                <!-- Serial number designating each separate item within a series of articles. -->
                <e1082 name="lineItemNumber">1</e1082>
                <!-- Code specifying the action to be taken or already taken. -->
                <e1229 name="actionRequestnotificationCoded"></e1229>
                <c212 name="itemNumberIdentification">
                    <!-- A number allocated to a group or item. -->
                    <e7140 name="itemNumber">Produkt 2</e7140>
                    <!-- Identification of the type of item number. -->
                    <e7143 name="itemNumberTypeCoded">SA</e7143>
                </c212>
            </lin>
            <qty name="quantity">
                <c186 name="quantityDetails">
                    <!-- Code giving specific meaning to a quantity. -->
                    <e6063 name="quantityQualifier">1</e6063>
                    <!-- Numeric value of a quantity. -->
                    <e6060 name="quantity">1000</e6060>
                </c186>
            </qty>
        </lin_group>
        <lin_group>
            <lin name="lineItem">
                <!-- Serial number designating each separate item within a series of articles. -->
                <e1082 name="lineItemNumber">1</e1082>
                <!-- Code specifying the action to be taken or already taken. -->
                <e1229 name="actionRequestnotificationCoded"></e1229>
                <c212 name="itemNumberIdentification">
                    <!-- A number allocated to a group or item. -->
                    <e7140 name="itemNumber">Produkt 2</e7140>
                    <!-- Identification of the type of item number. -->
                    <e7143 name="itemNumberTypeCoded">SA</e7143>
                </c212>
            </lin>
            <qty name="quantity">
                <c186 name="quantityDetails">
                    <!-- Code giving specific meaning to a quantity. -->
                    <e6063 name="quantityQualifier">1</e6063>
                    <!-- Numeric value of a quantity. -->
                    <e6060 name="quantity">1000</e6060>
                </c186>
            </qty>
        </lin_group>
        <uns name="sectionControl">
            <!-- Separates sections in a message. -->
            <e0081 name="sectionIdentification">S</e0081>
        </uns>
        <cnt name="controlTotal">
            <c270 name="control">
                <!-- Determines the source data elements in the message which forms the basis for 6066 Control value. -->
                <e6069 name="controlQualifier">2</e6069>
                <!-- Value obtained from summing the values specified by the Control Qualifier throughout the message (Hash total). -->
                <e6066 name="controlValue">1</e6066>
            </c270>
        </cnt>
        <unt name="messageTrailer">
            <!-- Control count of number of segments in a message. -->
            <e0074 name="numberOfSegmentsInTheMessage">9</e0074>
            <!-- Unique message reference assigned by the sender. -->
            <e0062 name="messageReferenceNumber">1</e0062>
        </unt>
    </orders>
    <unz name="interchangeTrailer">
        <!-- The count either of the number of messages or, if used, of the number of functional groups in an interchange. One of these counts shall appear. -->
        <e0036 name="interchangeControlCount">1</e0036>
        <!-- Unique reference assigned by the sender to an interchange. -->
        <e0020 name="interchangeControlReference">1234567</e0020>
    </unz>
</interchange>
