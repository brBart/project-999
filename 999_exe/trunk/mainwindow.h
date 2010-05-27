#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QtGui/QMainWindow>
#include "ui_mainwindow.h"

#include <QNetworkCookieJar>
#include "section/section.h"
#include "plugin_factory/plugin_factory.h"

class MainWindow : public QMainWindow
{
    Q_OBJECT

public:
    MainWindow(QWidget *parent = 0);
    ~MainWindow(){};

public slots:
	void setIsSessionActive(bool isActive);
	void loadMainSection();
	void loadSalesSection();

protected:
	void closeEvent(QCloseEvent *event);

private:
    Ui::MainWindowClass ui;
    bool m_IsSessionActive;
    QNetworkCookieJar m_CookieJar;
    QUrl *m_ServerUrl;
    PluginFactory m_PluginFactory;

    void setSection(Section *section);
};

#endif // MAINWINDOW_H
