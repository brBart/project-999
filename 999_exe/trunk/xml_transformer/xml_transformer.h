/*
 * xml_transformer.h
 *
 *  Created on: 28/04/2010
 *      Author: pc
 */

#ifndef XML_TRANSFORMER_H_
#define XML_TRANSFORMER_H_

#include <QDomDocument>
#include <QMap>

class XmlTransformer
{
public:
	virtual void transform(QDomDocument *document) = 0;
	QList<QMap<QString, QString>*> content();

protected:
	QList<QMap<QString, QString>*> m_Content;
};

#endif /* XML_TRANSFORMER_H_ */
