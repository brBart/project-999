/*
 * xml_transformer.h
 *
 *  Created on: 28/04/2010
 *      Author: pc
 */

#ifndef XML_TRANSFORMER_H_
#define XML_TRANSFORMER_H_

#include <QDomDocument>

class XmlTransformer
{
public:
	virtual bool transform(QDomDocument *document, QString *errorMsg = 0) = 0;
};

#endif /* XML_TRANSFORMER_H_ */
