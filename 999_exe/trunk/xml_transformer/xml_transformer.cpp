/*
 * xml_transformer.cpp
 *
 *  Created on: 19/05/2010
 *      Author: pc
 */

#include "xml_transformer.h"

/**
 * @class XmlTransformer
 * Abstract class for transforming xml documents into useful data.
 */

/**
 * Returns the transformed content.
 */
QList<QMap<QString, QString>*> XmlTransformer::content()
{
	return m_Content;
}
