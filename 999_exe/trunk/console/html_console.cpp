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

/**
 * Changes the element's style class from "failed" to "hidden".
 */
void HtmlConsole::hideElementIndicator(QString elementId)
{
	QWebElement element = m_Div.findFirst("#" + elementId + "-failed");
	element.removeClass("failed");
	element.addClass("hidden");
}

/**
 * Changes the element's style class form "hidden" to "failed".
 */
void HtmlConsole::showElementIndicator(QString elementId)
{
	QWebElement element = m_Div.findFirst("#" + elementId + "-failed");
	element.removeClass("hidden");
	element.addClass("failed");
}
