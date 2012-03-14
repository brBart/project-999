/*
 * available_cash_receipt_list_transformer.h
 *
 *  Created on: 07/09/2010
 *      Author: pc
 */

#ifndef AVAILABLE_CASH_RECEIPT_LIST_XML_TRANSFORMER_H_
#define AVAILABLE_CASH_RECEIPT_LIST_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class AvailableCashReceiptListXmlTransformer: public XmlTransformer
{
public:
	AvailableCashReceiptListXmlTransformer() {};
	virtual ~AvailableCashReceiptListXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* AVAILABLE_CASH_RECEIPT_LIST_XML_TRANSFORMER_H_ */
