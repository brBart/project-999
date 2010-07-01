/*
 * change_xml_transformer.cpp
 *
 *  Created on: 01/07/2010
 *      Author: pc
 */

#include "change_xml_transformer.h"

/**
 * @class ChangeXmlTransformer
 * Transforms an xml document into change amount.
 */

/**
 * Stores the change amount data into the QList for future retrieval.
 */
void ChangeXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList changes = document->elementsByTagName("change");

	QMap<QString, QString> *map = new QMap<QString, QString>();
	map->insert("change", changes.at(0).toElement().text());
	m_Content << map;
}
