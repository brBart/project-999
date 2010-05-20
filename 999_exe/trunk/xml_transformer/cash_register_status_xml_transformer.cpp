/*
 * cash_register_status_xml_transformer.cpp
 *
 *  Created on: 19/05/2010
 *      Author: pc
 */

#include "cash_register_status_xml_transformer.h"

void CashRegisterStatusXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList status = document->elementsByTagName("status");
	QMap<QString, QString> *map = new QMap<QString, QString>();
	map->insert("status", status.at(0).toElement().text());
	m_Content << map;
}
