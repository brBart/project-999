/*
 * registry.h
 *
 *  Created on: 30/04/2010
 *      Author: pc
 */

#ifndef REGISTRY_H_
#define REGISTRY_H_

#include <QUrl>

class Registry
{
public:
	virtual ~Registry() {};
	QUrl* serverUrl();
	static Registry* instance();

private:
	QUrl *m_Url;
	static Registry *m_Instance;

	Registry();
};

#endif /* REGISTRY_H_ */
