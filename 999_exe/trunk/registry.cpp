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
	QString url = "127.0.0.1/999_project/pos";
	QFile file("preferences.txt");

	if (file.exists()) {
		file.open(QIODevice::ReadOnly);
		QTextStream stream(&file);

		QString line = stream.readLine();
		QStringList address = line.split(" ");
		url = ((address[1] == "localhost" || address[1] == "") ?
				"127.0.0.1" : address[1]);

		file.close();
	}

	m_Url = new QUrl("http://" + url + "/");
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
	return m_Url;
}
