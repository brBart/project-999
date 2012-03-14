/*
 * payment_card_brand_list_xml_transformer.h
 *
 *  Created on: 14/07/2010
 *      Author: pc
 */

#ifndef PAYMENT_CARD_BRAND_LIST_XML_TRANSFORMER_H_
#define PAYMENT_CARD_BRAND_LIST_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class PaymentCardBrandListXmlTransformer: public XmlTransformer {
public:
	PaymentCardBrandListXmlTransformer() {};
	virtual ~PaymentCardBrandListXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* PAYMENT_CARD_BRAND_LIST_XML_TRANSFORMER_H_ */
