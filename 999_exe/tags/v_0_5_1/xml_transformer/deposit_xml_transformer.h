/*
 * deposit_xml_transformer.h
 *
 *  Created on: 02/09/2010
 *      Author: pc
 */

#ifndef DEPOSIT_XML_TRANSFORMER_H_
#define DEPOSIT_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class DepositXmlTransformer: public XmlTransformer
{
public:
	DepositXmlTransformer() {};
	virtual ~DepositXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* DEPOSIT_XML_TRANSFORMER_H_ */
