/*
 * deposit_list_xml_transformer.cpp
 *
 *  Created on: 17/09/2010
 *      Author: pc
 */

#include "deposit_list_xml_transformer.h"

/**
 * @DepositListXmlTransformer
 * Transforms an xml document into a deposit list.
 */

/**
 * Stores the deposit list into the QList for future retrieval.
 */
void DepositListXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList ids = document->elementsByTagName("id");
	QDomNodeList bankIds = document->elementsByTagName("bank_id");
	QDomNodeList numbers = document->elementsByTagName("number");
	QDomNodeList status = document->elementsByTagName("status");

	for (int i = 0; i < ids.size(); i++) {
		QMap<QString, QString> *map = new QMap<QString, QString>();
		map->insert("id", ids.at(i).toElement().text());
		map->insert("bank_id", bankIds.at(i).toElement().text());
		map->insert("number", numbers.at(i).toElement().text());
		map->insert("status", status.at(i).toElement().text());
		m_Content << map;
	}
}
