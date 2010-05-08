#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QtGui/QMainWindow>
#include "ui_mainwindow.h"

#include <QNetworkAccessManager>
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
	void setWorkingDayKey(QString wdayKey);
	void loadMainSection();
	void loadSalesSection();

protected:
	void closeEvent(QCloseEvent *event);

private:
    Ui::MainWindowClass ui;
    bool m_IsSessionActive;
    QNetworkAccessManager m_Manager;
    QUrl *m_ServerUrl;
    PluginFactory m_PluginFactory;
    QString m_WdayKey;

    void setSection(Section *section);
};

#endif // MAINWINDOW_H
