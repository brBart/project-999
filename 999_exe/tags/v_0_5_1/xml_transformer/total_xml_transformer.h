/*
 * total_xml_transformer.h
 *
 *  Created on: 20/07/2010
 *      Author: pc
 */

#ifndef TOTAL_XML_TRANSFORMER_H_
#define TOTAL_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class TotalXmlTransformer: public XmlTransformer
{
public:
	TotalXmlTransformer() {};
	virtual ~TotalXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* TOTAL_XML_TRANSFORMER_H_ */
