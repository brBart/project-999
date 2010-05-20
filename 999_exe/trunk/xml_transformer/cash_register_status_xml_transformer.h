/*
 * cash_register_status_xml_transformer.h
 *
 *  Created on: 19/05/2010
 *      Author: pc
 */

#ifndef CASH_REGISTER_STATUS_XML_TRANSFORMER_H_
#define CASH_REGISTER_STATUS_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class CashRegisterStatusXmlTransformer: public XmlTransformer
{
public:
	CashRegisterStatusXmlTransformer() {};
	virtual ~CashRegisterStatusXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* CASH_REGISTER_STATUS_XML_TRANSFORMER_H_ */
