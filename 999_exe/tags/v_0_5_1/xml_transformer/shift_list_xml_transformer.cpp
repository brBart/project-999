/*
 * shift_list_xml_transformer.cpp
 *
 *  Created on: 19/05/2010
 *      Author: pc
 */

#include "shift_list_xml_transformer.h"

/**
 * @class ShiftListXmlTransformer
 * Transforms an xml document into a list of shifts.
 */

/**
 * Stores the list into the QList for future retrieval.
 */
void ShiftListXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList ids = document->elementsByTagName("shift_id");
	QDomNodeList names = document->elementsByTagName("name");

	for (int i = 0; i < ids.size(); i++) {
		QMap<QString, QString> *map = new QMap<QString, QString>();
		map->insert("shift_id", ids.at(i).toElement().text());
		map->insert("name", names.at(i).toElement().text());
		m_Content << map;
	}
}
