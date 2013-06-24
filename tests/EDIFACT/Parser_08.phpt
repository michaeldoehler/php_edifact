--TEST--
EDI_EDIFACT_Parser test 08
--FILE--
<?php

require_once dirname(__FILE__) . '/../tests.inc.php';

try {
    $parser = EDI::parserFactory('EDIFACT');
    $edidoc = $parser->parse(TEST_DATA_DIR . '/EDIFACT/ex4.edi');
    echo $edidoc->toXML();
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
            <e0001 name="syntaxIdentifier">UNOA</e0001>
            <e0002 name="syntaxVersionNumber">2</e0002>
        </s001>
        <s002 name="interchangeSender">
            <e0004 name="senderIdentification">D--01RFN011</e0004>
        </s002>
        <s003 name="interchangeRecipient">
            <e0010 name="recipientIdentification">RFN020</e0010>
        </s003>
        <s004 name="datetimeOfPreparation">
            <e0017 name="dateOfPreparation">980904</e0017>
            <e0019 name="timeOfPreparation">1616</e0019>
        </s004>
        <e0020 name="interchangeControlReference">EVA/0000003</e0020>
    </unb>
    <invoic>
        <unh name="messageHeader">
            <e0062 name="messageReferenceNumber">EVA0000001</e0062>
            <s009 name="messageIdentifier">
                <e0065 name="messageType">INVOIC</e0065>
                <e0052 name="messageVersionNumber">D</e0052>
                <e0054 name="messageReleaseNumber">95A</e0054>
                <e0051 name="controllingAgency">UN</e0051>
                <e0057 name="associationAssignedCode">ETEIB</e0057>
            </s009>
        </unh>
        <bgm name="beginningOfMessage">
            <c002 name="documentmessageName">
                <e1001 name="documentmessageNameCoded">130</e1001>
            </c002>
            <e1004 name="documentmessageNumber">D--01/4300900020</e1004>
            <e1225 name="messageFunctionCoded">9</e1225>
        </bgm>
        <dtm name="datetimeperiod">
            <c507 name="datetimeperiod">
                <e2005 name="datetimeperiodQualifier">3</e2005>
                <e2380 name="datetimeperiod">19981125</e2380>
            </c507>
        </dtm>
        <dtm name="datetimeperiod">
            <c507 name="datetimeperiod">
                <e2005 name="datetimeperiodQualifier">263</e2005>
                <e2380 name="datetimeperiod">9811</e2380>
                <e2379 name="datetimeperiodFormatQualifier">609</e2379>
            </c507>
        </dtm>
        <nad_group>
            <nad name="nameAndAddress">
                <e3035 name="partyQualifier">II</e3035>
                <c082 name="partyIdentificationDetails">
                    <e3039 name="partyIdIdentification"></e3039>
                </c082>
                <c058 name="nameAndAddress">
                    <e3124 name="nameAndAddressLine"></e3124>
                </c058>
                <c080 name="partyName">
                    <e3036 name="partyName">WUERZBURG</e3036>
                </c080>
                <c059 name="street">
                    <e3042 name="streetAndNumberpoBox">POSTFACH 10 00</e3042>
                </c059>
                <e3164 name="cityName">WUERZBURG</e3164>
                <e3229 name="countrySubentityIdentification"></e3229>
                <e3251 name="postcodeIdentification">97067</e3251>
                <e3207 name="countryCoded">DE</e3207>
            </nad>
            <sg5_group>
                <cta name="contactInformation">
                    <e3139 name="contactFunctionCoded">IC</e3139>
                </cta>
                <com name="communicationContact">
                    <c076 name="communicationContact">
                        <e3148 name="communicationNumber">0800 33 01094</e3148>
                        <e3155 name="communicationChannelQualifier">TE</e3155>
                    </c076>
                </com>
                <com name="communicationContact">
                    <c076 name="communicationContact">
                        <e3148 name="communicationNumber">(0931) 33-2309</e3148>
                        <e3155 name="communicationChannelQualifier">FX</e3155>
                    </c076>
                </com>
            </sg5_group>
        </nad_group>
        <nad_group>
            <nad name="nameAndAddress">
                <e3035 name="partyQualifier">IV</e3035>
                <c082 name="partyIdentificationDetails">
                    <e3039 name="partyIdIdentification"></e3039>
                </c082>
                <c058 name="nameAndAddress">
                    <e3124 name="nameAndAddressLine">381 4. DEB. 4300900020</e3124>
                    <e3124 name="nameAndAddressLine">ABNAHMESTR. 11</e3124>
                    <e3124 name="nameAndAddressLine">00739 BERLIN</e3124>
                </c058>
            </nad>
        </nad_group>
        <cux_group>
            <cux name="currencies">
                <c504 name="currencyDetails">
                    <e6347 name="currencyDetailsQualifier">1</e6347>
                    <e6345 name="currencyCoded">DEM</e6345>
                </c504>
            </cux>
        </cux_group>
        <lin_group>
            <lin name="lineItem">
                <e1082 name="lineItemNumber">1</e1082>
                <e1229 name="actionRequestnotificationCoded"></e1229>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">781120020007/781120020052</e7140>
                    <e7143 name="itemNumberTypeCoded">I33</e7143>
                    <e1131 name="codeListQualifier">DT6</e1131>
                    <e3055 name="codeListResponsibleAgencyCoded">DTC</e3055>
                </c212>
                <c829 name="sublineInformation">
                    <e5495 name="sublineIndicatorCoded"></e5495>
                </c829>
                <e1222 name="configurationLevel">0</e1222>
            </lin>
        </lin_group>
        <lin_group>
            <lin name="lineItem">
                <e1082 name="lineItemNumber">2</e1082>
                <e1229 name="actionRequestnotificationCoded"></e1229>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber"></e7140>
                </c212>
                <c829 name="sublineInformation">
                    <e5495 name="sublineIndicatorCoded">1</e5495>
                    <e1082 name="lineItemNumber">1</e1082>
                </c829>
                <e1222 name="configurationLevel">1</e1222>
            </lin>
            <pia name="additionalProductId">
                <e4347 name="productIdFunctionQualifier">1</e4347>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">R999</e7140>
                </c212>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">N</e7140>
                </c212>
            </pia>
            <sg26_group>
                <moa name="monetaryAmount">
                    <c516 name="monetaryAmount">
                        <e5025 name="monetaryAmountTypeQualifier">203</e5025>
                        <e5004 name="monetaryAmount">2.086</e5004>
                    </c516>
                </moa>
            </sg26_group>
            <sg32_group>
                <loc name="placelocationIdentification">
                    <e3227 name="placelocationQualifier">1</e3227>
                    <c517 name="locationIdentification">
                        <e3225 name="placelocationIdentification">030557000</e3225>
                        <e1131 name="codeListQualifier"></e1131>
                        <e3055 name="codeListResponsibleAgencyCoded"></e3055>
                        <e3224 name="placelocation">R999 02050 FKTO 7</e3224>
                    </c517>
                </loc>
                <qty name="quantity">
                    <c186 name="quantityDetails">
                        <e6063 name="quantityQualifier">107</e6063>
                        <e6060 name="quantity">20</e6060>
                        <e6411 name="measureUnitQualifier">PCE</e6411>
                    </c186>
                </qty>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">163</e2005>
                        <e2380 name="datetimeperiod">19980617120200</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">204</e2379>
                    </c507>
                </dtm>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">48</e2005>
                        <e2380 name="datetimeperiod">123</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">807</e2379>
                    </c507>
                </dtm>
            </sg32_group>
        </lin_group>
        <lin_group>
            <lin name="lineItem">
                <e1082 name="lineItemNumber">3</e1082>
                <e1229 name="actionRequestnotificationCoded"></e1229>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber"></e7140>
                </c212>
                <c829 name="sublineInformation">
                    <e5495 name="sublineIndicatorCoded">1</e5495>
                    <e1082 name="lineItemNumber">1</e1082>
                </c829>
                <e1222 name="configurationLevel">1</e1222>
            </lin>
            <pia name="additionalProductId">
                <e4347 name="productIdFunctionQualifier">1</e4347>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">W998</e7140>
                </c212>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">N</e7140>
                </c212>
            </pia>
            <sg26_group>
                <moa name="monetaryAmount">
                    <c516 name="monetaryAmount">
                        <e5025 name="monetaryAmountTypeQualifier">203</e5025>
                        <e5004 name="monetaryAmount">3.129</e5004>
                    </c516>
                </moa>
            </sg26_group>
            <sg32_group>
                <loc name="placelocationIdentification">
                    <e3227 name="placelocationQualifier">1</e3227>
                    <c517 name="locationIdentification">
                        <e3225 name="placelocationIdentification">030558000</e3225>
                        <e1131 name="codeListQualifier"></e1131>
                        <e3055 name="codeListResponsibleAgencyCoded"></e3055>
                        <e3224 name="placelocation">W998 02050 FKTO 7</e3224>
                    </c517>
                </loc>
                <qty name="quantity">
                    <c186 name="quantityDetails">
                        <e6063 name="quantityQualifier">107</e6063>
                        <e6060 name="quantity">30</e6060>
                        <e6411 name="measureUnitQualifier">PCE</e6411>
                    </c186>
                </qty>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">163</e2005>
                        <e2380 name="datetimeperiod">19980618120300</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">204</e2379>
                    </c507>
                </dtm>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">48</e2005>
                        <e2380 name="datetimeperiod">123</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">807</e2379>
                    </c507>
                </dtm>
            </sg32_group>
        </lin_group>
        <lin_group>
            <lin name="lineItem">
                <e1082 name="lineItemNumber">4</e1082>
                <e1229 name="actionRequestnotificationCoded"></e1229>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber"></e7140>
                </c212>
                <c829 name="sublineInformation">
                    <e5495 name="sublineIndicatorCoded">1</e5495>
                    <e1082 name="lineItemNumber">1</e1082>
                </c829>
                <e1222 name="configurationLevel">1</e1222>
            </lin>
            <pia name="additionalProductId">
                <e4347 name="productIdFunctionQualifier">1</e4347>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">W999</e7140>
                </c212>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">N</e7140>
                </c212>
            </pia>
            <sg26_group>
                <moa name="monetaryAmount">
                    <c516 name="monetaryAmount">
                        <e5025 name="monetaryAmountTypeQualifier">203</e5025>
                        <e5004 name="monetaryAmount">4.172</e5004>
                    </c516>
                </moa>
            </sg26_group>
            <sg32_group>
                <loc name="placelocationIdentification">
                    <e3227 name="placelocationQualifier">1</e3227>
                    <c517 name="locationIdentification">
                        <e3225 name="placelocationIdentification">030559000</e3225>
                        <e1131 name="codeListQualifier"></e1131>
                        <e3055 name="codeListResponsibleAgencyCoded"></e3055>
                        <e3224 name="placelocation">W999 02050 FKTO 7</e3224>
                    </c517>
                </loc>
                <qty name="quantity">
                    <c186 name="quantityDetails">
                        <e6063 name="quantityQualifier">107</e6063>
                        <e6060 name="quantity">40</e6060>
                        <e6411 name="measureUnitQualifier">PCE</e6411>
                    </c186>
                </qty>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">163</e2005>
                        <e2380 name="datetimeperiod">19980619120400</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">204</e2379>
                    </c507>
                </dtm>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">48</e2005>
                        <e2380 name="datetimeperiod">123</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">807</e2379>
                    </c507>
                </dtm>
            </sg32_group>
        </lin_group>
        <lin_group>
            <lin name="lineItem">
                <e1082 name="lineItemNumber">5</e1082>
                <e1229 name="actionRequestnotificationCoded"></e1229>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber"></e7140>
                </c212>
                <c829 name="sublineInformation">
                    <e5495 name="sublineIndicatorCoded">1</e5495>
                    <e1082 name="lineItemNumber">1</e1082>
                </c829>
                <e1222 name="configurationLevel">1</e1222>
            </lin>
            <pia name="additionalProductId">
                <e4347 name="productIdFunctionQualifier">1</e4347>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">B024</e7140>
                </c212>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">S</e7140>
                </c212>
            </pia>
            <sg26_group>
                <moa name="monetaryAmount">
                    <c516 name="monetaryAmount">
                        <e5025 name="monetaryAmountTypeQualifier">203</e5025>
                        <e5004 name="monetaryAmount">5.215</e5004>
                    </c516>
                </moa>
            </sg26_group>
            <sg32_group>
                <loc name="placelocationIdentification">
                    <e3227 name="placelocationQualifier">1</e3227>
                    <c517 name="locationIdentification">
                        <e3225 name="placelocationIdentification">030560000</e3225>
                        <e1131 name="codeListQualifier"></e1131>
                        <e3055 name="codeListResponsibleAgencyCoded"></e3055>
                        <e3224 name="placelocation">B024 02050 FKTO 7</e3224>
                    </c517>
                </loc>
                <qty name="quantity">
                    <c186 name="quantityDetails">
                        <e6063 name="quantityQualifier">107</e6063>
                        <e6060 name="quantity">50</e6060>
                        <e6411 name="measureUnitQualifier">PCE</e6411>
                    </c186>
                </qty>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">163</e2005>
                        <e2380 name="datetimeperiod">19980620120500</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">204</e2379>
                    </c507>
                </dtm>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">48</e2005>
                        <e2380 name="datetimeperiod">123</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">807</e2379>
                    </c507>
                </dtm>
            </sg32_group>
        </lin_group>
        <lin_group>
            <lin name="lineItem">
                <e1082 name="lineItemNumber">6</e1082>
                <e1229 name="actionRequestnotificationCoded"></e1229>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber"></e7140>
                </c212>
                <c829 name="sublineInformation">
                    <e5495 name="sublineIndicatorCoded">1</e5495>
                    <e1082 name="lineItemNumber">1</e1082>
                </c829>
                <e1222 name="configurationLevel">1</e1222>
            </lin>
            <pia name="additionalProductId">
                <e4347 name="productIdFunctionQualifier">1</e4347>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">N001</e7140>
                </c212>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">N</e7140>
                </c212>
            </pia>
            <sg26_group>
                <moa name="monetaryAmount">
                    <c516 name="monetaryAmount">
                        <e5025 name="monetaryAmountTypeQualifier">203</e5025>
                        <e5004 name="monetaryAmount">1.043</e5004>
                    </c516>
                </moa>
            </sg26_group>
            <sg32_group>
                <loc name="placelocationIdentification">
                    <e3227 name="placelocationQualifier">1</e3227>
                    <c517 name="locationIdentification">
                        <e3225 name="placelocationIdentification">030556000</e3225>
                        <e1131 name="codeListQualifier"></e1131>
                        <e3055 name="codeListResponsibleAgencyCoded"></e3055>
                        <e3224 name="placelocation">N001 02050 FKTO 7</e3224>
                    </c517>
                </loc>
                <qty name="quantity">
                    <c186 name="quantityDetails">
                        <e6063 name="quantityQualifier">107</e6063>
                        <e6060 name="quantity">10</e6060>
                        <e6411 name="measureUnitQualifier">PCE</e6411>
                    </c186>
                </qty>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">163</e2005>
                        <e2380 name="datetimeperiod">19981112120100</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">204</e2379>
                    </c507>
                </dtm>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">48</e2005>
                        <e2380 name="datetimeperiod">123</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">807</e2379>
                    </c507>
                </dtm>
            </sg32_group>
        </lin_group>
        <lin_group>
            <lin name="lineItem">
                <e1082 name="lineItemNumber">7</e1082>
                <e1229 name="actionRequestnotificationCoded"></e1229>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber"></e7140>
                </c212>
                <c829 name="sublineInformation">
                    <e5495 name="sublineIndicatorCoded">1</e5495>
                    <e1082 name="lineItemNumber">1</e1082>
                </c829>
                <e1222 name="configurationLevel">1</e1222>
            </lin>
            <pia name="additionalProductId">
                <e4347 name="productIdFunctionQualifier">1</e4347>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">R999</e7140>
                </c212>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">N</e7140>
                </c212>
            </pia>
            <sg26_group>
                <moa name="monetaryAmount">
                    <c516 name="monetaryAmount">
                        <e5025 name="monetaryAmountTypeQualifier">203</e5025>
                        <e5004 name="monetaryAmount">11.5773</e5004>
                    </c516>
                </moa>
            </sg26_group>
            <sg32_group>
                <loc name="placelocationIdentification">
                    <e3227 name="placelocationQualifier">1</e3227>
                    <c517 name="locationIdentification">
                        <e3225 name="placelocationIdentification">05555038699903024999</e3225>
                        <e1131 name="codeListQualifier"></e1131>
                        <e3055 name="codeListResponsibleAgencyCoded"></e3055>
                        <e3224 name="placelocation">R999</e3224>
                    </c517>
                </loc>
                <qty name="quantity">
                    <c186 name="quantityDetails">
                        <e6063 name="quantityQualifier">107</e6063>
                        <e6060 name="quantity">111</e6060>
                        <e6411 name="measureUnitQualifier">PCE</e6411>
                    </c186>
                </qty>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">163</e2005>
                        <e2380 name="datetimeperiod">19981115120600</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">204</e2379>
                    </c507>
                </dtm>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">48</e2005>
                        <e2380 name="datetimeperiod">123</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">807</e2379>
                    </c507>
                </dtm>
            </sg32_group>
        </lin_group>
        <lin_group>
            <lin name="lineItem">
                <e1082 name="lineItemNumber">8</e1082>
                <e1229 name="actionRequestnotificationCoded"></e1229>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber"></e7140>
                </c212>
                <c829 name="sublineInformation">
                    <e5495 name="sublineIndicatorCoded">1</e5495>
                    <e1082 name="lineItemNumber">1</e1082>
                </c829>
                <e1222 name="configurationLevel">1</e1222>
            </lin>
            <pia name="additionalProductId">
                <e4347 name="productIdFunctionQualifier">1</e4347>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">R999</e7140>
                </c212>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">N</e7140>
                </c212>
            </pia>
            <sg26_group>
                <moa name="monetaryAmount">
                    <c516 name="monetaryAmount">
                        <e5025 name="monetaryAmountTypeQualifier">203</e5025>
                        <e5004 name="monetaryAmount">20.86</e5004>
                    </c516>
                </moa>
            </sg26_group>
            <sg32_group>
                <loc name="placelocationIdentification">
                    <e3227 name="placelocationQualifier">1</e3227>
                    <c517 name="locationIdentification">
                        <e3225 name="placelocationIdentification">05555038699903024999</e3225>
                        <e1131 name="codeListQualifier"></e1131>
                        <e3055 name="codeListResponsibleAgencyCoded"></e3055>
                        <e3224 name="placelocation">R999</e3224>
                    </c517>
                </loc>
                <qty name="quantity">
                    <c186 name="quantityDetails">
                        <e6063 name="quantityQualifier">107</e6063>
                        <e6060 name="quantity">200</e6060>
                        <e6411 name="measureUnitQualifier">PCE</e6411>
                    </c186>
                </qty>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">163</e2005>
                        <e2380 name="datetimeperiod">19981115120700</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">204</e2379>
                    </c507>
                </dtm>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">48</e2005>
                        <e2380 name="datetimeperiod">123</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">807</e2379>
                    </c507>
                </dtm>
            </sg32_group>
        </lin_group>
        <lin_group>
            <lin name="lineItem">
                <e1082 name="lineItemNumber">9</e1082>
                <e1229 name="actionRequestnotificationCoded"></e1229>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber"></e7140>
                </c212>
                <c829 name="sublineInformation">
                    <e5495 name="sublineIndicatorCoded">1</e5495>
                    <e1082 name="lineItemNumber">1</e1082>
                </c829>
                <e1222 name="configurationLevel">1</e1222>
            </lin>
            <pia name="additionalProductId">
                <e4347 name="productIdFunctionQualifier">1</e4347>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">R999</e7140>
                </c212>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">N</e7140>
                </c212>
            </pia>
            <sg26_group>
                <moa name="monetaryAmount">
                    <c516 name="monetaryAmount">
                        <e5025 name="monetaryAmountTypeQualifier">203</e5025>
                        <e5004 name="monetaryAmount">34.7319</e5004>
                    </c516>
                </moa>
            </sg26_group>
            <sg32_group>
                <loc name="placelocationIdentification">
                    <e3227 name="placelocationQualifier">1</e3227>
                    <c517 name="locationIdentification">
                        <e3225 name="placelocationIdentification">05555038699903024999</e3225>
                        <e1131 name="codeListQualifier"></e1131>
                        <e3055 name="codeListResponsibleAgencyCoded"></e3055>
                        <e3224 name="placelocation">R999</e3224>
                    </c517>
                </loc>
                <qty name="quantity">
                    <c186 name="quantityDetails">
                        <e6063 name="quantityQualifier">107</e6063>
                        <e6060 name="quantity">333</e6060>
                        <e6411 name="measureUnitQualifier">PCE</e6411>
                    </c186>
                </qty>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">163</e2005>
                        <e2380 name="datetimeperiod">19981115120800</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">204</e2379>
                    </c507>
                </dtm>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">48</e2005>
                        <e2380 name="datetimeperiod">123</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">807</e2379>
                    </c507>
                </dtm>
            </sg32_group>
        </lin_group>
        <lin_group>
            <lin name="lineItem">
                <e1082 name="lineItemNumber">10</e1082>
                <e1229 name="actionRequestnotificationCoded"></e1229>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber"></e7140>
                </c212>
                <c829 name="sublineInformation">
                    <e5495 name="sublineIndicatorCoded">1</e5495>
                    <e1082 name="lineItemNumber">1</e1082>
                </c829>
                <e1222 name="configurationLevel">1</e1222>
            </lin>
            <pia name="additionalProductId">
                <e4347 name="productIdFunctionQualifier">1</e4347>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">R999</e7140>
                </c212>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">N</e7140>
                </c212>
            </pia>
            <sg26_group>
                <moa name="monetaryAmount">
                    <c516 name="monetaryAmount">
                        <e5025 name="monetaryAmountTypeQualifier">203</e5025>
                        <e5004 name="monetaryAmount">0.1517</e5004>
                    </c516>
                </moa>
            </sg26_group>
            <sg32_group>
                <loc name="placelocationIdentification">
                    <e3227 name="placelocationQualifier">1</e3227>
                    <c517 name="locationIdentification">
                        <e3225 name="placelocationIdentification">05555038699967636999</e3225>
                        <e1131 name="codeListQualifier"></e1131>
                        <e3055 name="codeListResponsibleAgencyCoded"></e3055>
                        <e3224 name="placelocation">R999</e3224>
                    </c517>
                </loc>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">163</e2005>
                        <e2380 name="datetimeperiod">19981117120900</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">204</e2379>
                    </c507>
                </dtm>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">48</e2005>
                        <e2380 name="datetimeperiod">123</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">807</e2379>
                    </c507>
                </dtm>
            </sg32_group>
        </lin_group>
        <lin_group>
            <lin name="lineItem">
                <e1082 name="lineItemNumber">11</e1082>
                <e1229 name="actionRequestnotificationCoded"></e1229>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber"></e7140>
                </c212>
                <c829 name="sublineInformation">
                    <e5495 name="sublineIndicatorCoded">1</e5495>
                    <e1082 name="lineItemNumber">1</e1082>
                </c829>
                <e1222 name="configurationLevel">1</e1222>
            </lin>
            <pia name="additionalProductId">
                <e4347 name="productIdFunctionQualifier">1</e4347>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">R999</e7140>
                </c212>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">N</e7140>
                </c212>
            </pia>
            <sg26_group>
                <moa name="monetaryAmount">
                    <c516 name="monetaryAmount">
                        <e5025 name="monetaryAmountTypeQualifier">203</e5025>
                        <e5004 name="monetaryAmount">0.0759</e5004>
                    </c516>
                </moa>
            </sg26_group>
            <sg32_group>
                <loc name="placelocationIdentification">
                    <e3227 name="placelocationQualifier">1</e3227>
                    <c517 name="locationIdentification">
                        <e3225 name="placelocationIdentification">05555038699967636999</e3225>
                        <e1131 name="codeListQualifier"></e1131>
                        <e3055 name="codeListResponsibleAgencyCoded"></e3055>
                        <e3224 name="placelocation">R999</e3224>
                    </c517>
                </loc>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">163</e2005>
                        <e2380 name="datetimeperiod">19981117121000</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">204</e2379>
                    </c507>
                </dtm>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">48</e2005>
                        <e2380 name="datetimeperiod">123</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">807</e2379>
                    </c507>
                </dtm>
            </sg32_group>
        </lin_group>
        <lin_group>
            <lin name="lineItem">
                <e1082 name="lineItemNumber">12</e1082>
                <e1229 name="actionRequestnotificationCoded"></e1229>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber"></e7140>
                </c212>
                <c829 name="sublineInformation">
                    <e5495 name="sublineIndicatorCoded">1</e5495>
                    <e1082 name="lineItemNumber">1</e1082>
                </c829>
                <e1222 name="configurationLevel">1</e1222>
            </lin>
            <pia name="additionalProductId">
                <e4347 name="productIdFunctionQualifier">1</e4347>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">R999</e7140>
                </c212>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">N</e7140>
                </c212>
            </pia>
            <sg26_group>
                <moa name="monetaryAmount">
                    <c516 name="monetaryAmount">
                        <e5025 name="monetaryAmountTypeQualifier">203</e5025>
                        <e5004 name="monetaryAmount">0.0759</e5004>
                    </c516>
                </moa>
            </sg26_group>
            <sg32_group>
                <loc name="placelocationIdentification">
                    <e3227 name="placelocationQualifier">1</e3227>
                    <c517 name="locationIdentification">
                        <e3225 name="placelocationIdentification">05555038699967636999</e3225>
                        <e1131 name="codeListQualifier"></e1131>
                        <e3055 name="codeListResponsibleAgencyCoded"></e3055>
                        <e3224 name="placelocation">R999</e3224>
                    </c517>
                </loc>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">163</e2005>
                        <e2380 name="datetimeperiod">19981117121100</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">204</e2379>
                    </c507>
                </dtm>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">48</e2005>
                        <e2380 name="datetimeperiod">123</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">807</e2379>
                    </c507>
                </dtm>
            </sg32_group>
        </lin_group>
        <lin_group>
            <lin name="lineItem">
                <e1082 name="lineItemNumber">13</e1082>
                <e1229 name="actionRequestnotificationCoded"></e1229>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber"></e7140>
                </c212>
                <c829 name="sublineInformation">
                    <e5495 name="sublineIndicatorCoded">1</e5495>
                    <e1082 name="lineItemNumber">1</e1082>
                </c829>
                <e1222 name="configurationLevel">1</e1222>
            </lin>
            <pia name="additionalProductId">
                <e4347 name="productIdFunctionQualifier">1</e4347>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">R999</e7140>
                </c212>
                <c212 name="itemNumberIdentification">
                    <e7140 name="itemNumber">N</e7140>
                </c212>
            </pia>
            <sg26_group>
                <moa name="monetaryAmount">
                    <c516 name="monetaryAmount">
                        <e5025 name="monetaryAmountTypeQualifier">203</e5025>
                        <e5004 name="monetaryAmount">0.0379</e5004>
                    </c516>
                </moa>
            </sg26_group>
            <sg32_group>
                <loc name="placelocationIdentification">
                    <e3227 name="placelocationQualifier">1</e3227>
                    <c517 name="locationIdentification">
                        <e3225 name="placelocationIdentification">05555038699967636999</e3225>
                        <e1131 name="codeListQualifier"></e1131>
                        <e3055 name="codeListResponsibleAgencyCoded"></e3055>
                        <e3224 name="placelocation">R999</e3224>
                    </c517>
                </loc>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">163</e2005>
                        <e2380 name="datetimeperiod">19981117121200</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">204</e2379>
                    </c507>
                </dtm>
                <dtm name="datetimeperiod">
                    <c507 name="datetimeperiod">
                        <e2005 name="datetimeperiodQualifier">48</e2005>
                        <e2380 name="datetimeperiod">123</e2380>
                        <e2379 name="datetimeperiodFormatQualifier">807</e2379>
                    </c507>
                </dtm>
            </sg32_group>
        </lin_group>
        <uns name="sectionControl">
            <e0081 name="sectionIdentification">S</e0081>
        </uns>
        <moa_group>
            <moa name="monetaryAmount">
                <c516 name="monetaryAmount">
                    <e5025 name="monetaryAmountTypeQualifier">79</e5025>
                    <e5004 name="monetaryAmount">83.1556</e5004>
                </c516>
            </moa>
        </moa_group>
        <unt name="messageTrailer">
            <e0074 name="numberOfSegmentsInTheMessage">94</e0074>
            <e0062 name="messageReferenceNumber">EVA0000001</e0062>
        </unt>
    </invoic>
    <unz name="interchangeTrailer">
        <e0036 name="interchangeControlCount">1</e0036>
        <e0020 name="interchangeControlReference">EVA/0000003</e0020>
    </unz>
</interchange>
