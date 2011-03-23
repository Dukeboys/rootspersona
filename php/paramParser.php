<?php
/**
 *
 * prepopulate params from XML for add person form
 *
 * @param unknown_type $xml_doc
 */
function paramsFromXML($xml_doc) {
	$xpath = new DOMXPath($xml_doc);
	$xpath->registerNamespace('persona', "http://ed4becky.net/rootsPersona");

	$rootPersonNodeList = $xpath->query('/persona:person');
	$rootPersonNode = $rootPersonNodeList->item(0);
	$p['personId'] = $rootPersonNode->getAttribute('id');

	$nodeList = $xpath->query('persona:characteristics/persona:characteristic[@type="name"]',$rootPersonNode);
	$p['personName'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';

	$nodeList = $xpath->query('persona:characteristics/persona:characteristic[@type="gender"]',$rootPersonNode);
	$p['gender'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';

	$nodeList = $xpath->query('persona:events/persona:event[@type="birth"]/persona:date',$rootPersonNode);
	$p['bDate'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';

	$nodeList = $xpath->query('persona:events/persona:event[@type="birth"]/persona:place',$rootPersonNode);
	$p['bPlace'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';

	$nodeList = $xpath->query('persona:events/persona:event[@type="death"]/persona:date',$rootPersonNode);
	$p['dDate'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';

	$nodeList = $xpath->query('persona:events/persona:event[@type="death"]/persona:place',$rootPersonNode);
	$p['dPlace'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';

	$nodeList = $xpath->query('persona:events/persona:event[@type="marriage"]/persona:date',$rootPersonNode);
	$p['mDate'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';

	$nodeList = $xpath->query('persona:events/persona:event[@type="marriage"]/persona:place',$rootPersonNode);
	$p['mPlace'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';

	$nodeList = $xpath->query('persona:events/persona:event[@type="marriage"]/persona:person',$rootPersonNode);
	$partnerNode = $nodeList->item(0);
	$p['pid'] = isset($partnerNode)?$partnerNode->getAttribute('id'):'';

	$nodeList = $xpath->query('persona:relations/persona:relation[@type="father"]/persona:person',$rootPersonNode);
	$fatherNode = $nodeList->item(0);
	$p['fid'] = $fatherNode->getAttribute('id');

	$nodeList = $xpath->query('persona:relations/persona:relation[@type="mother"]/persona:person',$rootPersonNode);
	$motherNode = $nodeList->item(0);
	$p['mid'] = $motherNode->getAttribute('id');

	$nodeList = $xpath->query('persona:references/persona:familyGroups/persona:familyGroup[@selfType="parent"]',$rootPersonNode);
	$node = $nodeList->item(0);
	$p['gpid'] = isset($node)?$node->getAttribute('refId'):'';

	$nodeList = $xpath->query('persona:references/persona:familyGroups/persona:familyGroup[@selfType="child"]',$rootPersonNode);
	$node = $nodeList->item(0);
	$p['gcid'] = isset($node)?$node->getAttribute('refId'):'';
		
	return $p;
}

/**
 *
 * get params from doc into XML file
 *
 * @param  $xml_doc
 * @param  $p
 */
function paramsToXML($xml_doc, $p) {
	$xpath = new DOMXPath($xml_doc);
	$xpath->registerNamespace('persona', "http://ed4becky.net/rootsPersona");

	$rootPersonNodeList = $xpath->query('/persona:person');
	$rootPersonNode = $rootPersonNodeList->item(0);
	$rootPersonNode->setAttribute('id',$p['personId']);

	$nodeList = $xpath->query('persona:characteristics/persona:characteristic[@type="name"]',$rootPersonNode);
	$nodeList->item(0)->nodeValue = $p['personName'];

	$nodeList = $xpath->query('persona:characteristics/persona:characteristic[@type="gender"]',$rootPersonNode);
	$nodeList->item(0)->nodeValue = $p['gender'];

	$nodeList = $xpath->query('persona:events/persona:event[@type="birth"]/persona:date',$rootPersonNode);
	$nodeList->item(0)->nodeValue = $p['bDate'];

	$nodeList = $xpath->query('persona:events/persona:event[@type="birth"]/persona:place',$rootPersonNode);
	$nodeList->item(0)->nodeValue = $p['bPlace'];

	$nodeList = $xpath->query('persona:events/persona:event[@type="death"]/persona:date',$rootPersonNode);
	$nodeList->item(0)->nodeValue = $p['dDate'];

	$nodeList = $xpath->query('persona:events/persona:event[@type="death"]/persona:place',$rootPersonNode);
	$nodeList->item(0)->nodeValue = $p['dPlace'];

	$nodeList = $xpath->query('persona:events/persona:event[@type="marriage"]/persona:date',$rootPersonNode);
	$nodeList->item(0)->nodeValue = $p['mDate'];

	$nodeList = $xpath->query('persona:events/persona:event[@type="marriage"]/persona:place',$rootPersonNode);
	$nodeList->item(0)->nodeValue = $p['mPlace'];

	$nodeList = $xpath->query('persona:events/persona:event[@type="marriage"]/persona:person',$rootPersonNode);
	$partnerNode = $nodeList->item(0);
	$partnerNode->setAttribute('id', $p['pid']);

	$nodeList = $xpath->query('persona:characteristics/persona:characteristic[@type="name"]',$partnerNode);
	$nodeList->item(0)->nodeValue = $p['pname'];

	$nodeList = $xpath->query('persona:relations/persona:relation[@type="father"]/persona:person',$rootPersonNode);
	$fatherNode = $nodeList->item(0);
	$fatherNode->setAttribute('id',$p['fid']);

	$nodeList = $xpath->query('persona:relations/persona:relation[@type="mother"]/persona:person',$rootPersonNode);
	$motherNode = $nodeList->item(0);
	$motherNode->setAttribute('id',$p['mid']);

	$nodeList = $xpath->query('persona:references/persona:familyGroups/persona:familyGroup[@selfType="parent"]',$rootPersonNode);
	$node = $nodeList->item(0);
	$node->setAttribute('refId',$p['gpid']);

	$nodeList = $xpath->query('persona:references/persona:familyGroups/persona:familyGroup[@selfType="child"]',$rootPersonNode);
	$node = $nodeList->item(0);
	$node->setAttribute('refId',$p['gcid']);

	return $xml_doc;
}

/**
 *
 * get params from posted form
 *
 * @param unknown_type $params
 */
function paramsFromHTML($params) {
	$p['personId']  = isset($params['personId'])  ? trim(esc_attr($params['personId']))  : '';
	$p['personName']  = isset($params['personName'])  ? trim(esc_attr($params['personName']))  : '';
	$p['gender']  = isset($params['gender'])  ? trim(esc_attr($params['gender']))  : '';
	$p['bDate']  = isset($params['bDate'])  ? trim(esc_attr($params['bDate']))  : '';
	$p['bPlace']  = isset($params['bPlace'])  ? trim(esc_attr($params['bPlace']))  : '';
	$p['dDate']  = isset($params['dDate'])  ? trim(esc_attr($params['dDate']))  : '';
	$p['dPlace']  = isset($params['dPlace'])  ? trim(esc_attr($params['dPlace']))  : '';

	$p['mDate']  = isset($params['mDate'])  ? trim(esc_attr($params['mDate']))  : '';
	$p['mPlace']  = isset($params['mPlace'])  ? trim(esc_attr($params['mPlace']))  : '';
	$p['pid']  = isset($params['pid'])  ? trim(esc_attr($params['pid']))  : '';

	$p['fid']  = isset($params['fid'])  ? trim(esc_attr($params['fid']))  : '';
	$p['mid']  = isset($params['mid'])  ? trim(esc_attr($params['mid']))  : '';

	$p['gpid']  = isset($params['gpid'])  ? trim(esc_attr($params['gpid']))  : '';
	$p['gcid']  = isset($params['gcid'])  ? trim(esc_attr($params['gcid']))  : '';
	$p['srcPage']  = isset($params['srcPage'])  ? trim(esc_attr($params['srcPage']))  : '';

	for ($i=1; $i < 8; $i++)  {
		$p['picFile' . $i]  = isset($params['picFile' . $i])  ? trim(esc_attr($params['picFile' . $i]))  : '';
		$p['picCap' . $i]  = isset($params['picCap' . $i])  ? trim(esc_attr($params['picCap' . $i]))  : '';
	}

	return $p;
}
?>