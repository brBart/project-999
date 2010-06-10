#ifndef AUTHENTICATION_DIALOG_H
#define AUTHENTICATION_DIALOG_H

#include <QtGui/QDialog>
#include "ui_authentication_dialog.h"

#include "../console/console.h"

class AuthenticationDialog : public QDialog
{
    Q_OBJECT

public:
    AuthenticationDialog(QWidget *parent = 0, Qt::WindowFlags f = 0);
    ~AuthenticationDialog();
    QLineEdit* usernameLineEdit();
    QLineEdit* passwordLineEdit();
    Console* console();

public slots:
	void done();

signals:
	void okClicked();

private:
    Ui::AuthenticationDialogClass ui;
    Console *m_Console;
};

#endif // AUTHENTICATION_DIALOG_H
