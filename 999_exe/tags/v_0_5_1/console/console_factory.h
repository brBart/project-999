/*
 * console_factory.h
 *
 *  Created on: 25/05/2010
 *      Author: pc
 */

#ifndef CONSOLE_FACTORY_H_
#define CONSOLE_FACTORY_H_

#include <QMap>
#include <QLabel>
#include "console.h"

class ConsoleFactory : public QObject
{
	Q_OBJECT

public:
	virtual ~ConsoleFactory() {};
	Console* createWidgetConsole(QMap<QString, QLabel*> elements);
	Console* createHtmlConsole();
	static ConsoleFactory* instance();

private:
	static ConsoleFactory *m_Instance;

	ConsoleFactory(QObject *parent = 0);
};

#endif /* CONSOLE_FACTORY_H_ */
