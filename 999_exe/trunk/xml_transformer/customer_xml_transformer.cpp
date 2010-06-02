/*
 * customer_xml_transformer.cpp
 *
 *  Created on: 26/05/2010
 *      Author: pc
 */

#include "customer_xml_transformer.h"

/**
 * @class CustomerXmlTransformer
 * Transforms an xml document into customer data.
 */

/**
 * Stores the customer data into the QList for future retrieval.
 */
void CustomerXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList keys = document->elementsByTagName("key");
	QDomNodeList names = document->elementsByTagName("name");

	QMap<QString, QString> *map = new QMap<QString, QString>();
	map->insert("key", keys.at(0).toElement().text());
	map->insert("name", names.at(0).toElement().text());
	m_Content << map;
}
