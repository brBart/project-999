#ifndef MAIN_WINDOW_H
#define MAIN_WINDOW_H

#include <QtGui/QMainWindow>
#include "ui_mainwindow.h"

#include <QNetworkCookieJar>
#include "section/section.h"
#include "plugins/web_plugin_factory.h"

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
	void loadDepositSection();
	void loadCashRegisterSection();
	void loadWorkingDaySection();

protected:
	void closeEvent(QCloseEvent *event);

private:
    Ui::MainWindowClass ui;
    bool m_IsSessionActive;
    QNetworkCookieJar m_CookieJar;
    QUrl *m_ServerUrl;
    WebPluginFactory m_PluginFactory;

    void setSection(Section *section);
};

#endif // MAIN_WINDOW_H
