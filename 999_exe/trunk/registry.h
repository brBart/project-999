/*
 * registry.h
 *
 *  Created on: 30/04/2010
 *      Author: pc
 */

#ifndef REGISTRY_H_
#define REGISTRY_H_

#include <QObject>
#include <QUrl>

const QString SERVER_URL = "127.0.0.1/999_project/pos/";
const QString XSL_URL = "127.0.0.1/999_project/xsl/";
const QString HELP_URL = "127.0.0.1/999_help/";
const QString PRINTER_NAME = "EPSON TM-U220 Receipt";
const bool IS_TMU_PRINTER = true;

class Registry : public QObject
{
	Q_OBJECT

public:
	virtual ~Registry();
	QUrl* serverUrl();
	QUrl* xslUrl();
	QUrl* helpUrl();
	QString printerName();
	bool isTMUPrinter();
	static Registry* instance();

private:
	QUrl *m_ServerUrl;
	QUrl *m_XslUrl;
	QUrl *m_HelpUrl;
	QString m_PrinterName;
	bool m_IsTMUPrinter;
	static Registry *m_Instance;

	Registry(QObject *parent = 0);
};

#endif /* REGISTRY_H_ */
