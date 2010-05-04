/*
 * plugin_factory.h
 *
 *  Created on: 03/05/2010
 *      Author: pc
 */

#ifndef PLUGIN_FACTORY_H_
#define PLUGIN_FACTORY_H_

#include <QWebPluginFactory>
#include <QMap>

class PluginFactory : public QWebPluginFactory
{
public:
	PluginFactory(QObject *parent = 0);
	virtual ~PluginFactory() {};
	QObject* create(const QString &mimeType, const QUrl &url,
			const QStringList &argumentNames,
			const QStringList &argumentValues) const;
	QList<QWebPluginFactory::Plugin> plugins() const;
	void install(QString mimeType, QWidget *widget);
	void remove(QString mimeType);

private:
	QMap<QString, QWidget*> m_Plugins;
};

#endif /* PLUGIN_FACTORY_H_ */
