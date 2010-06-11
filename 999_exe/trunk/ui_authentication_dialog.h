/********************************************************************************
** Form generated from reading UI file 'authentication_dialog.ui'
**
** Created: Fri 11. Jun 16:18:39 2010
**      by: Qt User Interface Compiler version 4.6.2
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_AUTHENTICATION_DIALOG_H
#define UI_AUTHENTICATION_DIALOG_H

#include <QtCore/QVariant>
#include <QtGui/QAction>
#include <QtGui/QApplication>
#include <QtGui/QButtonGroup>
#include <QtGui/QDialog>
#include <QtGui/QGridLayout>
#include <QtGui/QHBoxLayout>
#include <QtGui/QHeaderView>
#include <QtGui/QLabel>
#include <QtGui/QLineEdit>
#include <QtGui/QPushButton>
#include <QtGui/QSpacerItem>
#include <QtWebKit/QWebView>

QT_BEGIN_NAMESPACE

class Ui_AuthenticationDialogClass
{
public:
    QGridLayout *gridLayout_2;
    QGridLayout *gridLayout;
    QLabel *label;
    QLineEdit *usernameLineEdit;
    QLabel *label_2;
    QLineEdit *passwordLineEdit;
    QWebView *webView;
    QHBoxLayout *horizontalLayout;
    QSpacerItem *horizontalSpacer;
    QPushButton *okPushButton;
    QPushButton *cancelPushButton;

    void setupUi(QDialog *AuthenticationDialogClass)
    {
        if (AuthenticationDialogClass->objectName().isEmpty())
            AuthenticationDialogClass->setObjectName(QString::fromUtf8("AuthenticationDialogClass"));
        AuthenticationDialogClass->resize(310, 145);
        gridLayout_2 = new QGridLayout(AuthenticationDialogClass);
        gridLayout_2->setSpacing(6);
        gridLayout_2->setContentsMargins(11, 11, 11, 11);
        gridLayout_2->setObjectName(QString::fromUtf8("gridLayout_2"));
        gridLayout = new QGridLayout();
        gridLayout->setSpacing(6);
        gridLayout->setObjectName(QString::fromUtf8("gridLayout"));
        label = new QLabel(AuthenticationDialogClass);
        label->setObjectName(QString::fromUtf8("label"));

        gridLayout->addWidget(label, 0, 0, 1, 1);

        usernameLineEdit = new QLineEdit(AuthenticationDialogClass);
        usernameLineEdit->setObjectName(QString::fromUtf8("usernameLineEdit"));

        gridLayout->addWidget(usernameLineEdit, 0, 1, 1, 1);

        label_2 = new QLabel(AuthenticationDialogClass);
        label_2->setObjectName(QString::fromUtf8("label_2"));

        gridLayout->addWidget(label_2, 1, 0, 1, 1);

        passwordLineEdit = new QLineEdit(AuthenticationDialogClass);
        passwordLineEdit->setObjectName(QString::fromUtf8("passwordLineEdit"));
        passwordLineEdit->setEchoMode(QLineEdit::Password);

        gridLayout->addWidget(passwordLineEdit, 1, 1, 1, 1);


        gridLayout_2->addLayout(gridLayout, 0, 0, 1, 1);

        webView = new QWebView(AuthenticationDialogClass);
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
        webView->setUrl(QUrl("about:blank"));

        gridLayout_2->addWidget(webView, 1, 0, 1, 1);

        horizontalLayout = new QHBoxLayout();
        horizontalLayout->setSpacing(6);
        horizontalLayout->setObjectName(QString::fromUtf8("horizontalLayout"));
        horizontalSpacer = new QSpacerItem(40, 20, QSizePolicy::Expanding, QSizePolicy::Minimum);

        horizontalLayout->addItem(horizontalSpacer);

        okPushButton = new QPushButton(AuthenticationDialogClass);
        okPushButton->setObjectName(QString::fromUtf8("okPushButton"));

        horizontalLayout->addWidget(okPushButton);

        cancelPushButton = new QPushButton(AuthenticationDialogClass);
        cancelPushButton->setObjectName(QString::fromUtf8("cancelPushButton"));

        horizontalLayout->addWidget(cancelPushButton);


        gridLayout_2->addLayout(horizontalLayout, 2, 0, 1, 1);

#ifndef QT_NO_SHORTCUT
        label->setBuddy(usernameLineEdit);
        label_2->setBuddy(passwordLineEdit);
#endif // QT_NO_SHORTCUT

        retranslateUi(AuthenticationDialogClass);

        QMetaObject::connectSlotsByName(AuthenticationDialogClass);
    } // setupUi

    void retranslateUi(QDialog *AuthenticationDialogClass)
    {
        AuthenticationDialogClass->setWindowTitle(QApplication::translate("AuthenticationDialogClass", "Autenticaci\303\263n", 0, QApplication::UnicodeUTF8));
        label->setText(QApplication::translate("AuthenticationDialogClass", "Usuario:", 0, QApplication::UnicodeUTF8));
        label_2->setText(QApplication::translate("AuthenticationDialogClass", "Contrase\303\261a:", 0, QApplication::UnicodeUTF8));
        okPushButton->setText(QApplication::translate("AuthenticationDialogClass", "&Aceptar", 0, QApplication::UnicodeUTF8));
        cancelPushButton->setText(QApplication::translate("AuthenticationDialogClass", "&Cancelar", 0, QApplication::UnicodeUTF8));
    } // retranslateUi

};

namespace Ui {
    class AuthenticationDialogClass: public Ui_AuthenticationDialogClass {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_AUTHENTICATION_DIALOG_H
