/*
 * registry.cpp
 *
 *  Created on: 30/04/2010
 *      Author: pc
 */

#include "registry.h"

#include <QStringList>
#include <QFile>
#include <QTextStream>
#include <QApplication>

/**
 * @class Registry
 * Class in charge of reading all the preferences in the preferences.txt file
 * located in the same path of the exe.
 */

Registry* Registry::m_Instance = 0;

/**
 * Constructs the Registry.
 * Opens and reads the file and loads the necessary objects.
 */
Registry::Registry()
{
	QString serverUrl = "127.0.0.1/999_project/pos/";
	QString xslUrl = "127.0.0.1/999_project/xsl/";

	QFile file(QApplication::applicationDirPath() + "/preferences.txt");

	if (file.exists()) {
		file.open(QIODevice::ReadOnly);
		QTextStream stream(&file);

		while (!stream.atEnd()) {
			QString line = stream.readLine();
			QString data = line.mid(0, line.indexOf("#") - 1);
			data = data.trimmed();

			if (data != "") {
				QStringList params = data.split(" ");

				if (params[0] == "commands_address") {
					QStringList values = params[1].split("/");
					values[0] = ((values[0] == "localhost" || values[0] == "") ?
								"127.0.0.1" : values[0]);
					serverUrl = values.join("/");
				} else if (params[0] == "xsl_address") {
					QStringList values = params[1].split("/");
					values[0] = ((values[0] == "localhost" || values[0] == "") ?
								"127.0.0.1" : values[0]);
					xslUrl = values.join("/");
				}
			}
		}

		file.close();
	}

	m_ServerUrl = new QUrl("http://" + serverUrl);
	m_XslUrl = new QUrl("http://" + xslUrl);
}

/**
 * Returns the only instance.
 */
Registry* Registry::instance()
{
	if (m_Instance == 0)
		m_Instance = new Registry();

	return m_Instance;
}

/**
 * Returns the QUrl with the server address.
 */
QUrl* Registry::serverUrl()
{
	return m_ServerUrl;
}

/**
 * Returns the QUrl with the xsl directory address.
 */
QUrl* Registry::xslUrl()
{
	return m_XslUrl;
}
