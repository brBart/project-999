/*
 * deposit_list_xml_transformer.h
 *
 *  Created on: 17/09/2010
 *      Author: pc
 */

#ifndef DEPOSIT_LIST_XML_TRANSFORMER_H_
#define DEPOSIT_LIST_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class DepositListXmlTransformer: public XmlTransformer
{
public:
	DepositListXmlTransformer() {}
	virtual ~DepositListXmlTransformer() {}
	virtual void transform(QDomDocument *document);
};

#endif /* DEPOSIT_LIST_XML_TRANSFORMER_H_ */
