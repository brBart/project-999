/*
 * invoice_list_xml_transformer.cpp
 *
 *  Created on: 11/05/2010
 *      Author: pc
 */

#include "invoice_list_xml_transformer.h"

/**
 * @InvoiceListXmlTransformer
 * Transforms an xml document into a invoice list.
 */

/**
 * Stores the invoice list into the QList for future retrieval.
 */
void InvoiceListXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList ids = document->elementsByTagName("id");
	QDomNodeList serials = document->elementsByTagName("serial_number");
	QDomNodeList numbers = document->elementsByTagName("number");

	for (int i = 0; i < ids.size(); i++) {
		QMap<QString, QString> *map = new QMap<QString, QString>();
		map->insert("id", ids.at(i).toElement().text());
		map->insert("serial_number", serials.at(i).toElement().text());
		map->insert("number", numbers.at(i).toElement().text());
		m_Content << map;
	}
}
