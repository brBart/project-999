/*
 * correlative_warning_xml_transformer.cpp
 *
 *  Created on: 8/06/2011
 *      Author: pc
 */

#include "correlative_warning_xml_transformer.h"

/**
 * @class CorrelativeWarningXmlTransformer
 * Transforms an xml correlative warning to data.
 */

/**
 * Stores the warning data into the QList for future retrieval.
 */
void CorrelativeWarningXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList status = document->elementsByTagName("status");
	QDomNodeList messages = document->elementsByTagName("message");

	QMap<QString, QString> *map = new QMap<QString, QString>();
	map->insert("status", status.at(0).toElement().text());
	map->insert("message", messages.at(0).toElement().text());

	m_Content << map;
}
