/*
 * cash_register_status_xml_transformer.cpp
 *
 *  Created on: 19/05/2010
 *      Author: pc
 */

#include "cash_register_status_xml_transformer.h"

/**
 * @class CashRegisterStatusXmlTransformer
 * Transforms the xml data to the cash register status fetched from the server.
 */

/**
 * Stores the cash register status into the QList for future retrieval.
 */
void CashRegisterStatusXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList status = document->elementsByTagName("status");
	QMap<QString, QString> *map = new QMap<QString, QString>();
	map->insert("status", status.at(0).toElement().text());
	m_Content << map;
}
