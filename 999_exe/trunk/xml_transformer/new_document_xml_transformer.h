/*
 * invoice_xml_transformer.h
 *
 *  Created on: 19/05/2010
 *      Author: pc
 */

#ifndef NEW_DOCUMENT_XML_TRANSFORMER_H_
#define NEW_DOCUMENT_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class NewDocumentXmlTransformer: public XmlTransformer
{
public:
	NewDocumentXmlTransformer() {};
	virtual ~NewDocumentXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* NEW_DOCUMENT_XML_TRANSFORMER_H_ */
