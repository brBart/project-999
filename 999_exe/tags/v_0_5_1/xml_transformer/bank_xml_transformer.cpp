/*
 * bank_xml_transformer.cpp
 *
 *  Created on: 03/09/2010
 *      Author: pc
 */

#include "bank_xml_transformer.h"

/**
 * @class BankXmlTransformer
 * Transforms an xml document to a bank's name.
 */

/**
 * Stores the bank's name into the QList for future retrieval.
 */
void BankXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList banks = document->elementsByTagName("bank");

	QMap<QString, QString> *map = new QMap<QString, QString>();
	map->insert("bank", banks.at(0).toElement().text());

	m_Content << map;
}
