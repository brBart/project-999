/*
 * bank_list_xml_transformer.h
 *
 *  Created on: 14/09/2010
 *      Author: pc
 */

#ifndef BANK_LIST_XML_TRANSFORMER_H_
#define BANK_LIST_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class BankListXmlTransformer: public XmlTransformer
{
public:
	BankListXmlTransformer() {}
	virtual ~BankListXmlTransformer() {}
	virtual void transform(QDomDocument *document);
};

#endif /* BANK_LIST_XML_TRANSFORMER_H_ */
