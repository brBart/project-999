/*
 * invoice_xml_transformer.h
 *
 *  Created on: 19/05/2010
 *      Author: pc
 */

#ifndef INVOICE_XML_TRANSFORMER_H_
#define INVOICE_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class InvoiceXmlTransformer: public XmlTransformer
{
public:
	InvoiceXmlTransformer() {};
	virtual ~InvoiceXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* INVOICE_XML_TRANSFORMER_H_ */
