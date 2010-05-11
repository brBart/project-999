/*
 * sales_section.h
 *
 *  Created on: 08/05/2010
 *      Author: pc
 */

#ifndef SALES_SECTION_H_
#define SALES_SECTION_H_

#include "section.h"

#include <QAction>
#include "../mainwindow.h"

class SalesSection: public Section
{
public:
	SalesSection(QNetworkAccessManager *manager, QWebPluginFactory *factory,
			QUrl *serverUrl, QString cRegisterKey, QWidget *parent = 0);
	virtual ~SalesSection();

private:
	QString m_CRegisterKey;
	//Recordset m_Recordset;
	MainWindow *m_Window;

	// File actions.
	QAction *m_NewAction;
	QAction *m_SaveAction;
	QAction *m_DiscardAction;
	QAction *m_CancelAction;
	QAction *m_ExitAction;

	// Edit actions.
	QAction *m_ClientAction;
	QAction *m_DiscountAction;
	QAction *m_AddProductAction;
	QAction *m_RemoveProductAction;
	QAction *m_SearchProductAction;

	// View actions.
	QAction *m_MoveFirstAction;
	QAction *m_MovePreviousAction;
	QAction *m_MoveNextAction;
	QAction *m_MoveLastAction;
	QAction *m_SearchAction;
	QAction *m_ConsultProductAction;

	void setActions();
	void setMenu();
};

#endif /* SALES_SECTION_H_ */
