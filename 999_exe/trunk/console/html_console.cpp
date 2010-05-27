/*
 * html_console.cpp
 *
 *  Created on: 24/05/2010
 *      Author: pc
 */

#include "html_console.h"

/**
 * @class HtmlConsole
 * Use to display messages to the user using a html page.
 */

void HtmlConsole::hideElementIndicator(QString elementId)
{
	QWebElement element = m_Div.findFirst("#" + elementId + "-failed");
	element.removeClass("failed");
	element.addClass("hidden");
}

void HtmlConsole::showElementIndicator(QString elementId)
{
	QWebElement element = m_Div.findFirst("#" + elementId + "-failed");
	element.removeClass("hidden");
	element.addClass("failed");
}
