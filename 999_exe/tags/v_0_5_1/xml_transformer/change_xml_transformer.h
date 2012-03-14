/*
 * change_xml_transformer.h
 *
 *  Created on: 01/07/2010
 *      Author: pc
 */

#ifndef CHANGE_XML_TRANSFORMER_H_
#define CHANGE_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class ChangeXmlTransformer: public XmlTransformer
{
public:
	ChangeXmlTransformer() {};
	virtual ~ChangeXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* CHANGE_XML_TRANSFORMER_H_ */
