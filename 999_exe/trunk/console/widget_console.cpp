/*
 * widget_console.cpp
 *
 *  Created on: 24/05/2010
 *      Author: pc
 */

#include "widget_console.h"

#include <QMapIterator>
#include <QWebElementCollection>

/**
 * @class WidgetConsole
 * Use to display messages using widgets.
 */

/**
 * Sets all the widget elements's visible property to false to hide them.
 */
WidgetConsole::WidgetConsole(QMap<QString, QLabel*> elements)
{
	QMapIterator<QString, QLabel*> i(elements);
	while (i.hasNext()) {
		i.next();
		i.value()->setVisible(false);
	}

	m_Elements = elements;
}

/**
 * Creates the console div element first then calls its parent's setFrame method.
 */
void WidgetConsole::setFrame(QWebFrame *frame)
{
	frame->setHtml("<div id=\"console\" style=\"font-size: 10px; color: red;\">"
			"</div>");
	Console::setFrame(frame);
}

/**
 * Sets the widget element visible property to false.
 */
void WidgetConsole::hideElementIndicator(QString elementId)
{
	m_Elements.value(elementId)->setVisible(false);
}

/**
 * Sets the widget element visible property to true.
 */
void WidgetConsole::showElementIndicator(QString elementId)
{
	m_Elements.value(elementId)->setVisible(true);
}
