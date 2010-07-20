/*
 * total_xml_transformer.cpp
 *
 *  Created on: 20/07/2010
 *      Author: pc
 */

#include "total_xml_transformer.h"

/**
 * @class ChangeXmlTransformer
 * Transforms an xml document into total amount.
 */

/**
 * Stores the total amount data into the QList for future retrieval.
 */
void TotalXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList changes = document->elementsByTagName("total");

	QMap<QString, QString> *map = new QMap<QString, QString>();
	map->insert("total", changes.at(0).toElement().text());
	m_Content << map;
}
