/*
 * widget_console.h
 *
 *  Created on: 24/05/2010
 *      Author: pc
 */

#ifndef WIDGET_CONSOLE_H_
#define WIDGET_CONSOLE_H_

#include "console.h"

#include <QMap>
#include <QLabel>

class WidgetConsole: public Console
{
public:
	WidgetConsole(QMap<QString, QLabel*> elements);
	virtual ~WidgetConsole() {};
	void setFrame(QWebFrame *frame);

protected:
	void hideElementIndicator(QString elementId);
	void showElementIndicator(QString elementId);

private:
	QMap<QString, QLabel*> m_Elements;
};

#endif /* WIDGET_CONSOLE_H_ */
