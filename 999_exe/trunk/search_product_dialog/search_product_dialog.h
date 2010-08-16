#ifndef SEARCH_PRODUCT_DIALOG_H
#define SEARCH_PRODUCT_DIALOG_H

#include <QtGui/QDialog>
#include "ui_search_product_dialog.h"

class SearchProductDialog : public QDialog
{
    Q_OBJECT

public:
    SearchProductDialog(QWidget *parent = 0);
    ~SearchProductDialog();

private:
    Ui::SearchProductDialogClass ui;
};

#endif // SEARCH_PRODUCT_DIALOG_H
