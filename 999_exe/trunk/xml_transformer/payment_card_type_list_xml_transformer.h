/*
 * payment_card_type_list_xml_transformer.h
 *
 *  Created on: 14/07/2010
 *      Author: pc
 */

#ifndef PAYMENT_CARD_TYPE_LIST_XML_TRANSFORMER_H_
#define PAYMENT_CARD_TYPE_LIST_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class PaymentCardTypeListXmlTransformer: public XmlTransformer
{
public:
	PaymentCardTypeListXmlTransformer() {};
	virtual ~PaymentCardTypeListXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* PAYMENT_CARD_TYPE_LIST_XML_TRANSFORMER_H_ */
