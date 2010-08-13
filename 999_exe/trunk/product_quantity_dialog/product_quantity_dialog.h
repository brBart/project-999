#ifndef PRODUCT_QUANTITY_DIALOG_H
#define PRODUCT_QUANTITY_DIALOG_H

#include <QtGui/QDialog>
#include "ui_product_quantity_dialog.h"

class ProductQuantityDialog : public QDialog
{
    Q_OBJECT

public:
    ProductQuantityDialog(QWidget *parent = 0, Qt::WindowFlags f = 0);
    ~ProductQuantityDialog() {};
    QString barCode();
    int quantity();

private:
    Ui::ProductQuantityDialogClass ui;
    QString m_BarCode;
    int m_Quantity;
};

#endif // PRODUCT_QUANTITY_DIALOG_H
