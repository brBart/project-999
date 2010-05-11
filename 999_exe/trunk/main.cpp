#include "mainwindow.h"

#include <QtGui>
#include <QApplication>
#include <QTranslator>

int main(int argc, char *argv[])
{
    QApplication a(argc, argv);

    QTranslator translator;
    translator.load("qt_es");
    a.installTranslator(&translator);

    MainWindow w;
    w.showMaximized();

    return a.exec();
}
