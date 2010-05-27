/*
 * console_factory.cpp
 *
 *  Created on: 25/05/2010
 *      Author: pc
 */

#include "console_factory.h"

#include "widget_console.h"
#include "html_console.h"

ConsoleFactory* ConsoleFactory::m_Instance = 0;

/**
 * Returns the only instance.
 */
ConsoleFactory* ConsoleFactory::instance()
{
	if (m_Instance == 0)
		m_Instance = new ConsoleFactory();

	return m_Instance;
}

Console* ConsoleFactory::createWidgetConsole(QMap<QString, QLabel*> elements)
{
	return new WidgetConsole(elements);
}

Console* ConsoleFactory::createHtmlConsole()
{
	return new HtmlConsole();
}
