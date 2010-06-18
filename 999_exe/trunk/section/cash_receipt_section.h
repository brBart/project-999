/*
 * cash_receipt_section.h
 *
 *  Created on: 15/06/2010
 *      Author: pc
 */

#ifndef CASH_RECEIPT_SECTION_H_
#define CASH_RECEIPT_SECTION_H_

#include "section.h"

#include <QMainWindow>
#include "../console/console.h"

class CashReceiptSection: public Section
{
	Q_OBJECT

public:
	CashReceiptSection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
			QUrl *serverUrl, QString cashReceiptKey, QWidget *parent = 0);
	virtual ~CashReceiptSection();

public slots:
	void loadFinished(bool ok);
	void setCash(QString amount);

private:
	QString m_CashReceiptKey;
	Console *m_Console;
	QMainWindow *m_Window;

	// File actions.
	QAction *m_SaveAction;
	QAction *m_ExitAction;

	// Edit actions.
	QAction *m_AddVoucherAction;
	QAction *m_DeleteVoucherAction;

	// View actions.
	QAction *m_ScrollUpAction;
	QAction *m_ScrollDownAction;

	void setActions();
	void setMenu();
	void setPlugins();
};

#endif /* CASH_RECEIPT_SECTION_H_ */
