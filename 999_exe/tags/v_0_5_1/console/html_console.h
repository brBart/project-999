/*
 * html_console.h
 *
 *  Created on: 24/05/2010
 *      Author: pc
 */

#ifndef HTML_CONSOLE_H_
#define HTML_CONSOLE_H_

#include "console.h"

class HtmlConsole: public Console
{
public:
	HtmlConsole() {};
	virtual ~HtmlConsole() {};
	void setFrame(QWebFrame *frame);

protected:
	void hideElementIndicator(QString elementId);
	void showElementIndicator(QString elementId);

private:
	QWebFrame *m_Frame;
};

#endif /* HTML_CONSOLE_H_ */
