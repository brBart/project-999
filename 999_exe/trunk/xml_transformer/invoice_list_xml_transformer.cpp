/*
 * invoice_list_xml_transformer.cpp
 *
 *  Created on: 11/05/2010
 *      Author: pc
 */

#include "invoice_list_xml_transformer.h"

/**
 * Transforms the xml document.
 */
bool InvoiceListXmlTransformer::transform(QDomDocument *document, QString *errorMsg)
{
	QDomNodeList ids = document->elementsByTagName("invoice_id");
	QDomNodeList serials = document->elementsByTagName("serial_number");
	QDomNodeList numbers = document->elementsByTagName("number");

	for (int i = 0; i < ids.size(); i++) {
		QMap<QString, QString> *map = new QMap<QString, QString>();
		map->insert("invoice_id", ids.at(i).toElement().text());
		map->insert("serial_number", serials.at(i).toElement().text());
		map->insert("number", numbers.at(i).toElement().text());
		m_List << map;
	}

	return true;
}

/**
 * Returns the transformed list.
 */
QList<QMap<QString, QString>*> InvoiceListXmlTransformer::list()
{
	return m_List;
}
