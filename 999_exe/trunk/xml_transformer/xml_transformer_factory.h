/*
 * xml_transformer_factory.h
 *
 *  Created on: 28/05/2010
 *      Author: pc
 */

#ifndef XML_TRANSFORMER_FACTORY_H_
#define XML_TRANSFORMER_FACTORY_H_

#include "xml_transformer.h"

class XmlTransformerFactory
{
public:
	virtual ~XmlTransformerFactory() {};
	XmlTransformer* create(QString name);
	static XmlTransformerFactory* instance();

private:
	static XmlTransformerFactory* m_Instance;

	XmlTransformerFactory() {};
};

#endif /* XML_TRANSFORMER_FACTORY_H_ */
