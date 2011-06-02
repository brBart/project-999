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
Registry::Registry(QObject *parent) : QObject(parent)
{
	QString serverUrl;
	QString xslUrl;
	QString helpUrl;
	QString printerName;
	bool isTMUPrinter = IS_TMU_PRINTER;

	QFile file(QApplication::applicationDirPath() + "/preferences.txt");

	if (file.exists()) {
		file.open(QIODevice::ReadOnly);
		QTextStream stream(&file);

		while (!stream.atEnd()) {
			QString line = stream.readLine();
			QString data = line.mid(0, line.indexOf("#") - 1);
			data = data.trimmed();

			if (data != "") {
				QStringList params = data.split("=");

				if (params[0].trimmed() == "commands_address") {
					QStringList values = params[1].trimmed().split("/");
					values[0] = ((values[0] == "localhost" || values[0] == "") ?
								"127.0.0.1" : values[0]);
					serverUrl = values.join("/");
				} else if (params[0].trimmed() == "xsl_address") {
					QStringList values = params[1].trimmed().split("/");
					values[0] = ((values[0] == "localhost" || values[0] == "") ?
								"127.0.0.1" : values[0]);
					xslUrl = values.join("/");
				} else if (params[0].trimmed() == "help_address") {
					QStringList values = params[1].trimmed().split("/");
					values[0] = ((values[0] == "localhost" || values[0] == "") ?
								"127.0.0.1" : values[0]);
					helpUrl = values.join("/");
				} else if (params[0].trimmed() == "printer_name") {
					printerName = params[1].trimmed();
				} else if (params[0].trimmed() == "is_tmu_printer") {
					isTMUPrinter = (params[1].trimmed() == "yes");
				}
			}
		}

		file.close();
	}

	serverUrl = (serverUrl != "") ? serverUrl : SERVER_URL;
	xslUrl = (xslUrl != "") ? xslUrl : XSL_URL;
	helpUrl = (helpUrl != "") ? helpUrl : HELP_URL;

	m_ServerUrl = new QUrl("http://" + serverUrl);
	m_XslUrl = new QUrl("http://" + xslUrl);
	m_HelpUrl = new QUrl("http://" + helpUrl);
	m_PrinterName = (printerName != "") ? printerName : PRINTER_NAME;
	m_IsTMUPrinter = isTMUPrinter;
}

/**
 * Destroys the url objects.
 */
Registry::~Registry()
{
	delete m_ServerUrl;
	delete m_XslUrl;
	delete m_HelpUrl;
}

/**
 * Returns the only instance.
 */
Registry* Registry::instance()
{
	if (m_Instance == 0)
		m_Instance = new Registry(qApp);

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

/**
 * Returns the QUrl with the system's help directory address.
 */
QUrl* Registry::helpUrl()
{
	return m_HelpUrl;
}

/**
 * Returns the name of the printer to use.
 */
QString Registry::printerName()
{
	return m_PrinterName;
}

/**
 * Returns true if the printer is a Epson TM-U printer.
 */
bool Registry::isTMUPrinter()
{
	return m_IsTMUPrinter;
}
