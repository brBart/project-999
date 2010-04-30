/*
 * console.h
 *
 *  Created on: 27/04/2010
 *      Author: pc
 */

#ifndef CONSOLE_H_
#define CONSOLE_H_

#include <QWebFrame>
#include <QString>

class Console
{
public:
	Console(QWebFrame *frame);
	virtual ~Console() {};
	void displayError(QString msg);

private:
	QWebFrame *m_Frame;
	QWebElement *m_Div;

	void displayMessage(QString msg);
};

#endif /* CONSOLE_H_ */
