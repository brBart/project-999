/*
 * available_cash_receipt_list_transformer.cpp
 *
 *  Created on: 07/09/2010
 *      Author: pc
 */

#include "available_cash_receipt_list_xml_transformer.h"

/**
 * @AvailableCashReceiptListXmlTransformer
 * Transforms an xml document into a cash receipt list.
 */

/**
 * Stores the list into the QList for future retrieval.
 */
void AvailableCashReceiptListXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList ids = document->elementsByTagName("id");
	QDomNodeList serials = document->elementsByTagName("serial_number");
	QDomNodeList numbers = document->elementsByTagName("number");
	QDomNodeList receivedCash = document->elementsByTagName("received_cash");
	QDomNodeList availableCash = document->elementsByTagName("available_cash");

	for (int i = 0; i < ids.size(); i++) {
		QMap<QString, QString> *map = new QMap<QString, QString>();
		map->insert("id", ids.at(i).toElement().text());
		map->insert("serial_number", serials.at(i).toElement().text());
		map->insert("number", numbers.at(i).toElement().text());
		map->insert("received_cash", receivedCash.at(i).toElement().text());
		map->insert("available_cash", availableCash.at(i).toElement().text());
		m_Content << map;
	}
}
