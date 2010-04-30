/*
 * map_string_xml_transformer.cpp
 *
 *  Created on: 28/04/2010
 *      Author: pc
 */

#include "map_string_xml_transformer.h"

/**
 * @class MapStringXmlTransformer
 * Transforms a QDomDocument into a QList<QMap<QString, QString>> object.
 */

/**
 * Transforms the xml document.
 */
bool MapStringXmlTransformer::transform(QDomDocument *document, QString *errorMsg)
{
	QDomNodeList ids = document->elementsByTagName("id");
	QDomNodeList names = document->elementsByTagName("name");

	for (int i = 0; i <= ids.size(); i++) {
		QMap<QString, QString> map;
		map[ids.at(i).toElement().text()] = names.at(i).toElement().text();
		m_List << &map;
	}

	return true;
}

/**
 * Returns the transformed list.
 */
QList<QMap<QString, QString>*> MapStringXmlTransformer::list()
{
	return m_List;
}
