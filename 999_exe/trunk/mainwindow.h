#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QtGui/QMainWindow>
#include "ui_mainwindow.h"

#include <QNetworkAccessManager>
#include "section/section.h"

class MainWindow : public QMainWindow
{
    Q_OBJECT

public:
    MainWindow(QWidget *parent = 0);
    ~MainWindow(){};

public slots:
	void setIsSessionActive(bool isActive);
	void loadMainSection();
	//void loadSalesSection();

protected:
	void closeEvent(QCloseEvent *event);

private:
    Ui::MainWindowClass ui;
    bool m_IsSessionActive;
    QNetworkAccessManager m_Manager;
    QUrl *m_ServerUrl;

    void setSection(Section *section);
};

#endif // MAINWINDOW_H
