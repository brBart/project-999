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
	virtual void setFrame(QWebFrame *frame);
	virtual void displayFailure(QString msg, QString elementId);
	virtual void cleanFailure(QString elementId);
	virtual void displayError(QString msg);
	virtual void reset();

protected:
	QWebElement m_Div;

	void displayMessage(QString msg);
	virtual void hideElementIndicator(QString elementId) = 0;
	virtual void showElementIndicator(QString elementId) = 0;
};

#endif /* CONSOLE_H_ */
