/*
 * shift_list_xml_transformer.h
 *
 *  Created on: 19/05/2010
 *      Author: pc
 */

#ifndef SHIFT_LIST_XML_TRANSFORMER_H_
#define SHIFT_LIST_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class ShiftListXmlTransformer: public XmlTransformer
{
public:
	ShiftListXmlTransformer() {};
	virtual ~ShiftListXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* SHIFT_LIST_XML_TRANSFORMER_H_ */
