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
#include "plugin_widget.h"

class PluginFactory : public QWebPluginFactory
{
public:
	PluginFactory(QObject *parent = 0);
	virtual ~PluginFactory() {};
	QObject* create(const QString &mimeType, const QUrl &url,
			const QStringList &argumentNames,
			const QStringList &argumentValues) const;
	QList<QWebPluginFactory::Plugin> plugins() const;
	void install(QString mimeType, PluginWidget *widget);
	void remove(QString mimeType);

private:
	QMap<QString, PluginWidget*> m_Plugins;
};

#endif /* PLUGIN_FACTORY_H_ */
