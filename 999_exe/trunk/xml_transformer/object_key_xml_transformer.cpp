/*
 * object_key_xml_transformer.cpp
 *
 *  Created on: 07/05/2010
 *      Author: pc
 */

#include "object_key_xml_transformer.h"

/**
 * @class ObjectKeyXmlTransformer
 * Transform a xml document with a key value into a QString.
 */

/**
 * Transforms the key value into a QString.
 */
bool ObjectKeyXmlTransformer::transform(QDomDocument *document, QString *errorMsg)
{
	QDomNodeList keys = document->elementsByTagName("key");
	m_Key = keys.at(0).toElement().text();

	return true;
}

/**
 * Returns the key value.
 */
QString ObjectKeyXmlTransformer::key()
{
	return m_Key;
}
