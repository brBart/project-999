/*
 * deposit_xml_transformer.cpp
 *
 *  Created on: 02/09/2010
 *      Author: pc
 */

#include "deposit_xml_transformer.h"

/**
 * @class DepositXmlTransformer
 * Transforms an xml document to a new deposit's data.
 */

/**
 * Stores the new deposit's data into the QList for future retrieval.
 */
void DepositXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList keys = document->elementsByTagName("key");
	QDomNodeList dateTimes = document->elementsByTagName("date_time");
	QDomNodeList usernames = document->elementsByTagName("username");

	QMap<QString, QString> *map = new QMap<QString, QString>();
	map->insert("key", keys.at(0).toElement().text());
	map->insert("date_time", dateTimes.at(0).toElement().text());
	map->insert("username", usernames.at(0).toElement().text());

	m_Content << map;

	QDomNodeList bankAccountIds = document->elementsByTagName("bank_account_id");
	QDomNodeList holderNames = document->elementsByTagName("holder_name");

	map = new QMap<QString, QString>();

	for (int i = 0; i < bankAccountIds.size(); i++) {
		map->insert(bankAccountIds.at(i).toElement().text(),
				holderNames.at(i).toElement().text());
	}

	m_Content << map;
}
