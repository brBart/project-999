/*
 * bank_list_xml_transformer.cpp
 *
 *  Created on: 14/09/2010
 *      Author: pc
 */

#include "bank_list_xml_transformer.h"

/**
 * @class BankListXmlTransformer
 * Transforms an xml document into a list of banks.
 */

/**
 * Stores the list into the QList for future retrieval.
 */
void BankListXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList ids = document->elementsByTagName("bank_id");
	QDomNodeList names = document->elementsByTagName("name");

	for (int i = 0; i < ids.size(); i++) {
		QMap<QString, QString> *map = new QMap<QString, QString>();
		map->insert("bank_id", ids.at(i).toElement().text());
		map->insert("name", names.at(i).toElement().text());
		m_Content << map;
	}
}
