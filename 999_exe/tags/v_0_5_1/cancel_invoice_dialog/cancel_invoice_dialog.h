#ifndef CANCEL_INVOICE_DIALOG_H
#define CANCEL_INVOICE_DIALOG_H

#include <QtGui/QDialog>
#include "ui_cancel_invoice_dialog.h"

#include "../console/console.h"

class CancelInvoiceDialog : public QDialog
{
    Q_OBJECT

public:
    CancelInvoiceDialog(QWidget *parent = 0, Qt::WindowFlags f = 0);
    ~CancelInvoiceDialog();
    QLineEdit* usernameLineEdit();
    QLineEdit* passwordLineEdit();
    QLineEdit* reasonLineEdit();
    Console* console();

signals:
	void okClicked();

private:
    Ui::CancelInvoiceDialogClass ui;
    Console *m_Console;
};

#endif // CANCEL_INVOICE_DIALOG_H
