/*
 * bank_xml_transformer.h
 *
 *  Created on: 03/09/2010
 *      Author: pc
 */

#ifndef BANK_XML_TRANSFORMER_H_
#define BANK_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class BankXmlTransformer: public XmlTransformer
{
public:
	BankXmlTransformer() {};
	virtual ~BankXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* BANK_XML_TRANSFORMER_H_ */
