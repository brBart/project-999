/*
 * map_string_xml_transformer.h
 *
 *  Created on: 28/04/2010
 *      Author: pc
 */

#ifndef MAP_STRING_XML_TRANSFORMER_H_
#define MAP_STRING_XML_TRANSFORMER_H_

#include "xml_transformer.h"

#include <QMap>

class MapStringXmlTransformer : public XmlTransformer
{
public:
	MapStringXmlTransformer() {};
	virtual ~MapStringXmlTransformer() {};
	virtual bool transform(QDomDocument *document, QString *errorMsg = 0);
	QList<QMap<QString, QString>*> list();

private:
	QList<QMap<QString, QString>*> m_List;
};

#endif /* MAP_STRING_XML_TRANSFORMER_H_ */
