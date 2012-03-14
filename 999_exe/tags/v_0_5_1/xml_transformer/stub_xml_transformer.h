/*
 * stub_xml_transformer.h
 *
 *  Created on: 21/05/2010
 *      Author: pc
 */

#ifndef STUB_XML_TRANSFORMER_H_
#define STUB_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class StubXmlTransformer: public XmlTransformer
{
public:
	StubXmlTransformer() {};
	virtual ~StubXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* STUB_XML_TRANSFORMER_H_ */
