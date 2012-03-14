/*
 * customer_xml_transformer.h
 *
 *  Created on: 26/05/2010
 *      Author: pc
 */

#ifndef CUSTOMER_XML_TRANSFORMER_H_
#define CUSTOMER_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class CustomerXmlTransformer: public XmlTransformer
{
public:
	CustomerXmlTransformer() {};
	virtual ~CustomerXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* CUSTOMER_XML_TRANSFORMER_H_ */
