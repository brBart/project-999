/*
 * invoice_customer_xml_transformer.h
 *
 *  Created on: 26/05/2010
 *      Author: pc
 */

#ifndef INVOICE_CUSTOMER_XML_TRANSFORMER_H_
#define INVOICE_CUSTOMER_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class InvoiceCustomerXmlTransformer: public XmlTransformer
{
public:
	InvoiceCustomerXmlTransformer() {};
	virtual ~InvoiceCustomerXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* INVOICE_CUSTOMER_XML_TRANSFORMER_H_ */
