#ifndef SEARCH_PRODUCT_DIALOG_H
#define SEARCH_PRODUCT_DIALOG_H

#include <QtGui/QDialog>
#include "ui_search_product_dialog.h"

#include <QNetworkCookieJar>
#include <QUrl>
#include "../console/console.h"
#include "../search_product/search_product_model.h"

class SearchProductDialog : public QDialog
{
    Q_OBJECT

public:
    SearchProductDialog(QNetworkCookieJar *jar, QUrl *url, SearchProductModel *model,
    		QWidget *parent = 0, Qt::WindowFlags f = 0);
    ~SearchProductDialog();
    QString barCode();

signals:
	void sessionStatusChanged(bool isActive);

private:
    Ui::SearchProductDialogClass ui;
	Console *m_Console;
};

#endif // SEARCH_PRODUCT_DIALOG_H
