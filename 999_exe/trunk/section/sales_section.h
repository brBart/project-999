/*
 * sales_section.h
 *
 *  Created on: 08/05/2010
 *      Author: pc
 */

#ifndef SALES_SECTION_H_
#define SALES_SECTION_H_

#include "document_section.h"

#include "../plugins/bar_code_line_edit.h"
#include "../plugins/label.h"

class SalesSection: public DocumentSection
{
	Q_OBJECT

public:
	SalesSection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
			QUrl *serverUrl, QString cashRegisterKey, QWidget *parent = 0);
	virtual ~SalesSection();

public slots:
	void setCustomer();
	void addProductInvoice(QString barCode, int quantity = 1);
	void deleteProductInvoice();
	void showAuthenticationDialogForDiscount();
	void createDiscount();
	void createCashReceipt();
	void finishInvoice(QString Id);
	void addProductWithQuantity();
	void searchProduct();
	void searchInvoice();
	void consultProduct();

protected:
	void setActions();
	void setMenu();
	void setActionsManager();
	void installRecordsetSearcher();
	void setPlugins();
	void updateActions();
	void removeNewDocumentFromSession();
	void prepareDocumentForm(QString dateTime, QString username);

	void createDocumentEvent(bool ok);

private:
	BarCodeLineEdit *m_BarCodeLineEdit;
	QString m_CashReceiptKey;
	Label *m_RecordsetLabel;

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
	QAction *m_DeleteProductAction;
	QAction *m_SearchProductAction;

	// View actions.
	QAction *m_ScrollUpAction;
	QAction *m_ScrollDownAction;
	QAction *m_MoveFirstAction;
	QAction *m_MovePreviousAction;
	QAction *m_MoveNextAction;
	QAction *m_MoveLastAction;
	QAction *m_SearchAction;
	QAction *m_ConsultProductAction;

	QString navigateValues();
	void updateCustomerData(QString nit, QString name);
	void setDiscountInvoice(QString discountKey);
	void printInvoice(QString id);
};

#endif /* SALES_SECTION_H_ */
