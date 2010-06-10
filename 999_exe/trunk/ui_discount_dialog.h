/********************************************************************************
** Form generated from reading UI file 'discount_dialog.ui'
**
** Created: Thu 10. Jun 09:23:06 2010
**      by: Qt User Interface Compiler version 4.6.2
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_DISCOUNT_DIALOG_H
#define UI_DISCOUNT_DIALOG_H

#include <QtCore/QVariant>
#include <QtGui/QAction>
#include <QtGui/QApplication>
#include <QtGui/QButtonGroup>
#include <QtGui/QDialog>
#include <QtGui/QGridLayout>
#include <QtGui/QHBoxLayout>
#include <QtGui/QHeaderView>
#include <QtGui/QLabel>
#include <QtGui/QPushButton>
#include <QtGui/QSpacerItem>
#include <QtWebKit/QWebView>
#include "line_edit/line_edit.h"

QT_BEGIN_NAMESPACE

class Ui_DiscountDialogClass
{
public:
    QGridLayout *gridLayout;
    QHBoxLayout *horizontalLayout_2;
    QLabel *label;
    LineEdit *percentageLineEdit;
    QLabel *percentageFailedLabel;
    QWebView *webView;
    QHBoxLayout *horizontalLayout;
    QSpacerItem *horizontalSpacer;
    QPushButton *okPushButton;
    QPushButton *cancelPushButton;

    void setupUi(QDialog *DiscountDialogClass)
    {
        if (DiscountDialogClass->objectName().isEmpty())
            DiscountDialogClass->setObjectName(QString::fromUtf8("DiscountDialogClass"));
        DiscountDialogClass->resize(310, 109);
        gridLayout = new QGridLayout(DiscountDialogClass);
        gridLayout->setSpacing(6);
        gridLayout->setContentsMargins(11, 11, 11, 11);
        gridLayout->setObjectName(QString::fromUtf8("gridLayout"));
        horizontalLayout_2 = new QHBoxLayout();
        horizontalLayout_2->setSpacing(6);
        horizontalLayout_2->setObjectName(QString::fromUtf8("horizontalLayout_2"));
        label = new QLabel(DiscountDialogClass);
        label->setObjectName(QString::fromUtf8("label"));

        horizontalLayout_2->addWidget(label);

        percentageLineEdit = new LineEdit(DiscountDialogClass);
        percentageLineEdit->setObjectName(QString::fromUtf8("percentageLineEdit"));

        horizontalLayout_2->addWidget(percentageLineEdit);

        percentageFailedLabel = new QLabel(DiscountDialogClass);
        percentageFailedLabel->setObjectName(QString::fromUtf8("percentageFailedLabel"));

        horizontalLayout_2->addWidget(percentageFailedLabel);


        gridLayout->addLayout(horizontalLayout_2, 0, 0, 1, 1);

        webView = new QWebView(DiscountDialogClass);
        webView->setObjectName(QString::fromUtf8("webView"));
        webView->setMinimumSize(QSize(0, 32));
        webView->setMaximumSize(QSize(1000, 100));
        QPalette palette;
        QBrush brush(QColor(240, 240, 240, 255));
        brush.setStyle(Qt::SolidPattern);
        palette.setBrush(QPalette::Active, QPalette::Base, brush);
        palette.setBrush(QPalette::Inactive, QPalette::Base, brush);
        palette.setBrush(QPalette::Disabled, QPalette::Base, brush);
        webView->setPalette(palette);
        webView->setFocusPolicy(Qt::NoFocus);
        webView->setUrl(QUrl("about:blank"));

        gridLayout->addWidget(webView, 1, 0, 1, 1);

        horizontalLayout = new QHBoxLayout();
        horizontalLayout->setSpacing(6);
        horizontalLayout->setObjectName(QString::fromUtf8("horizontalLayout"));
        horizontalSpacer = new QSpacerItem(40, 20, QSizePolicy::Expanding, QSizePolicy::Minimum);

        horizontalLayout->addItem(horizontalSpacer);

        okPushButton = new QPushButton(DiscountDialogClass);
        okPushButton->setObjectName(QString::fromUtf8("okPushButton"));
        okPushButton->setAutoDefault(false);

        horizontalLayout->addWidget(okPushButton);

        cancelPushButton = new QPushButton(DiscountDialogClass);
        cancelPushButton->setObjectName(QString::fromUtf8("cancelPushButton"));
        cancelPushButton->setAutoDefault(false);

        horizontalLayout->addWidget(cancelPushButton);


        gridLayout->addLayout(horizontalLayout, 2, 0, 1, 1);


        retranslateUi(DiscountDialogClass);
        QObject::connect(cancelPushButton, SIGNAL(clicked()), DiscountDialogClass, SLOT(reject()));

        QMetaObject::connectSlotsByName(DiscountDialogClass);
    } // setupUi

    void retranslateUi(QDialog *DiscountDialogClass)
    {
        DiscountDialogClass->setWindowTitle(QApplication::translate("DiscountDialogClass", "Descuento", 0, QApplication::UnicodeUTF8));
        label->setText(QApplication::translate("DiscountDialogClass", "Descuento (%):", 0, QApplication::UnicodeUTF8));
        percentageFailedLabel->setText(QApplication::translate("DiscountDialogClass", "*", 0, QApplication::UnicodeUTF8));
        okPushButton->setText(QApplication::translate("DiscountDialogClass", "&Aceptar", 0, QApplication::UnicodeUTF8));
        cancelPushButton->setText(QApplication::translate("DiscountDialogClass", "&Cancelar", 0, QApplication::UnicodeUTF8));
    } // retranslateUi

};

namespace Ui {
    class DiscountDialogClass: public Ui_DiscountDialogClass {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_DISCOUNT_DIALOG_H
