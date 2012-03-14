/*
 * object_key_xml_transformer.cpp
 *
 *  Created on: 07/05/2010
 *      Author: pc
 */

#include "object_key_xml_transformer.h"

#include <QMap>

/**
 * @class ObjectKeyXmlTransformer
 * Transform a xml document with a key value into a QString.
 */

/**
 * Stores the fetched key into the QList for future retrieval.
 */
void ObjectKeyXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList keys = document->elementsByTagName("key");
	QMap<QString, QString> *map = new QMap<QString, QString>();
	map->insert("key", keys.at(0).toElement().text());
	m_Content << map;
}
