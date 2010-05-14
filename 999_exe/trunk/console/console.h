/*
 * console.h
 *
 *  Created on: 27/04/2010
 *      Author: pc
 */

#ifndef CONSOLE_H_
#define CONSOLE_H_

#include <QWebFrame>
#include <QWebElement>
#include <QString>

class Console
{
public:
	Console() {};
	virtual ~Console() {};
	void setFrame(QWebFrame *frame);
	void displayError(QString msg);

private:
	QWebElement m_Div;

	void displayMessage(QString msg);
};

#endif /* CONSOLE_H_ */
