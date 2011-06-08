/*
 * correlative_warning_xml_transformer.h
 *
 *  Created on: 8/06/2011
 *      Author: pc
 */

#ifndef CORRELATIVE_WARNING_XML_TRANSFORMER_H_
#define CORRELATIVE_WARNING_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class CorrelativeWarningXmlTransformer: public XmlTransformer {
public:
	CorrelativeWarningXmlTransformer() {};
	virtual ~CorrelativeWarningXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* CORRELATIVE_WARNING_XML_TRANSFORMER_H_ */
