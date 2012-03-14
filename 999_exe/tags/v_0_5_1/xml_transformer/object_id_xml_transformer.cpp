/*
 * object_id_xml_transformer.cpp
 *
 *  Created on: 27/07/2010
 *      Author: pc
 */

#include "object_id_xml_transformer.h"

/**
 * @class ObjectIdXmlTransformer
 * Transform a xml document with a id value into a QString.
 */

/**
 * Stores the fetched id into the QList for future retrieval.
 */
void ObjectIdXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList keys = document->elementsByTagName("id");
	QMap<QString, QString> *map = new QMap<QString, QString>();
	map->insert("id", keys.at(0).toElement().text());
	m_Content << map;
}
