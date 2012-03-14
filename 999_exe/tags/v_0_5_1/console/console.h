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
	virtual ~Console() {};
	virtual void setFrame(QWebFrame *frame);
	void displayFailure(QString msg, QString elementId);
	void cleanFailure(QString elementId);
	void displayError(QString msg);
	void reset();

protected:
	QWebElement m_Div;

	void displayMessage(QString msg);
	virtual void hideElementIndicator(QString elementId) = 0;
	virtual void showElementIndicator(QString elementId) = 0;
};

#endif /* CONSOLE_H_ */
