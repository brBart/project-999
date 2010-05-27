/*
 * widget_console.cpp
 *
 *  Created on: 24/05/2010
 *      Author: pc
 */

#include "widget_console.h"

#include <QMapIterator>
#include <QWebElementCollection>

WidgetConsole::WidgetConsole(QMap<QString, QLabel*> elements)
{
	QMapIterator<QString, QLabel*> i(elements);
	while (i.hasNext()) {
		i.next();
		i.value()->setVisible(false);
	}

	m_Elements = elements;
}

void WidgetConsole::setFrame(QWebFrame *frame)
{
	frame->setHtml("<div id=\"console\" style=\"font-size: 10px; color: red;\">"
			"</div>");
	Console::setFrame(frame);
}

void WidgetConsole::hideElementIndicator(QString elementId)
{
	m_Elements.value(elementId)->setVisible(false);
}

void WidgetConsole::showElementIndicator(QString elementId)
{
	m_Elements.value(elementId)->setVisible(true);
}
