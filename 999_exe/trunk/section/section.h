#ifndef SECTION_H
#define SECTION_H

#include <QtGui/QWidget>
#include "ui_section.h"

class Section : public QWidget
{
    Q_OBJECT

public:
    Section(QNetworkAccessManager *manager, QWebPluginFactory *factory,
    		QUrl *serverUrl, QWidget *parent = 0);
    ~Section() {};

public slots:
	void loadFinished(bool ok);

signals:
	void sessionStatusChanged(bool isActive);

protected:
    Ui::SectionClass ui;
    QUrl *m_ServerUrl;
};

#endif // SECTION_H
