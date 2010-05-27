/*
 * invoice_customer_xml_transformer.cpp
 *
 *  Created on: 26/05/2010
 *      Author: pc
 */

#include "invoice_customer_xml_transformer.h"

void InvoiceCustomerXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList nits = document->elementsByTagName("nit");
	QDomNodeList names = document->elementsByTagName("name");

	QMap<QString, QString> *map = new QMap<QString, QString>();
	map->insert("nit", nits.at(0).toElement().text());
	map->insert("name", names.at(0).toElement().text());
	m_Content << map;
}
