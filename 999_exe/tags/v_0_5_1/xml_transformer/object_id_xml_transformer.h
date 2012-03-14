/*
 * object_id_xml_transformer.h
 *
 *  Created on: 27/07/2010
 *      Author: pc
 */

#ifndef OBJECT_ID_XML_TRANSFORMER_H_
#define OBJECT_ID_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class ObjectIdXmlTransformer: public XmlTransformer
{
public:
	ObjectIdXmlTransformer() {};
	virtual ~ObjectIdXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* OBJECT_ID_XML_TRANSFORMER_H_ */
