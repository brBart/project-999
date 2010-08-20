#ifndef SEARCH_INVOICE_DIALOG_H
#define SEARCH_INVOICE_DIALOG_H

#include <QtGui/QDialog>
#include "ui_search_invoice_dialog.h"

class SearchInvoiceDialog : public QDialog
{
    Q_OBJECT

public:
    SearchInvoiceDialog(QWidget *parent = 0, Qt::WindowFlags f = 0);
    ~SearchInvoiceDialog() {};
    QString serialNumber();
    QString number();

private:
    Ui::SearchInvoiceDialogClass ui;
};

#endif // SEARCH_INVOICE_DIALOG_H
