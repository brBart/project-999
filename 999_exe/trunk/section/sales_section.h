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
	void showAuthenticationDialogForDiscount();
	void createDiscount();
	void createCashReceipt();
	void finishInvoice(QString Id);
	void addProductWithQuantity();
	void searchProduct();
	void searchInvoice();
	void consultProduct();

protected:
	// Edit actions.
	QAction *m_ClientAction;
	QAction *m_DiscountAction;
	QAction *m_SearchProductAction;

	// View actions.
	QAction *m_ConsultProductAction;

	void setActions();
	void setMenu();
	void setActionsManager();
	void installRecordsetSearcher();
	void setPlugins();
	void updateActions();
	void removeNewDocumentFromSession();
	void prepareDocumentForm(QString dateTime, QString username);

	void createDocumentEvent(bool ok, QList<QMap<QString, QString>*> *list = 0);

private:
	BarCodeLineEdit *m_BarCodeLineEdit;
	QString m_CashReceiptKey;

	QString navigateValues();
	void updateCustomerData(QString nit, QString name);
	void setDiscountInvoice(QString discountKey);
	void printInvoice(QString id);
};

#endif /* SALES_SECTION_H_ */
