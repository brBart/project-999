/*
 * object_key_xml_transformer.h
 *
 *  Created on: 07/05/2010
 *      Author: pc
 */

#ifndef OBJECT_KEY_XML_TRANSFORMER_H_
#define OBJECT_KEY_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class ObjectKeyXmlTransformer: public XmlTransformer {
public:
	ObjectKeyXmlTransformer() {};
	virtual ~ObjectKeyXmlTransformer() {};
	virtual bool transform(QDomDocument *document, QString *errorMsg = 0);
	QString key();

private:
	QString m_Key;
};

#endif /* OBJECT_KEY_XML_TRANSFORMER_H_ */
