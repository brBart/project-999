/*
 * invoice_xml_transformer.cpp
 *
 *  Created on: 19/05/2010
 *      Author: pc
 */

#include "invoice_xml_transformer.h"

void InvoiceXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList keys = document->elementsByTagName("key");
	QDomNodeList dateTimes = document->elementsByTagName("date_time");
	QDomNodeList usernames = document->elementsByTagName("username");

	QMap<QString, QString> *map = new QMap<QString, QString>();
	map->insert("key", keys.at(0).toElement().text());
	map->insert("date_time", dateTimes.at(0).toElement().text());
	map->insert("username", usernames.at(0).toElement().text());

	m_Content << map;
}
