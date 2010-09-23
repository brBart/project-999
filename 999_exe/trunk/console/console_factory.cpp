/*
 * console_factory.cpp
 *
 *  Created on: 25/05/2010
 *      Author: pc
 */

#include "console_factory.h"

#include <QApplication>
#include "widget_console.h"
#include "html_console.h"

/**
 * @class ConsoleFactory
 * Class responsible for creating and returning console objects.
 */

ConsoleFactory* ConsoleFactory::m_Instance = 0;

/**
 * Constructs the factory with a parent.
 */
ConsoleFactory::ConsoleFactory(QObject *parent) : QObject(parent)
{

}

/**
 * Returns the only instance.
 */
ConsoleFactory* ConsoleFactory::instance()
{
	if (m_Instance == 0)
		m_Instance = new ConsoleFactory(qApp);

	return m_Instance;
}

/**
 * Creates and returns an WidgetConsole object.
 */
Console* ConsoleFactory::createWidgetConsole(QMap<QString, QLabel*> elements)
{
	return new WidgetConsole(elements);
}

/**
 * Creates and returns an HtmlConsole object.
 */
Console* ConsoleFactory::createHtmlConsole()
{
	return new HtmlConsole();
}
