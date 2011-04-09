#include "main_window.h"

#include <QtGui>
#include <QApplication>
#include <QTranslator>

int main(int argc, char *argv[])
{
    QApplication a(argc, argv);

    QTranslator translator;
    translator.load(":/resources/qt_es.qm");
    a.installTranslator(&translator);

    MainWindow w;
    w.showFullScreen();

    return a.exec();
}
