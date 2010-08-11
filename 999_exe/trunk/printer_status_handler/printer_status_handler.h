/*
 * statushandler.h
 *
 *  Created on: 07/04/2010
 *      Author: pc
 */

#ifndef PRINTER_STATUS_HANDLER_H_
#define PRINTER_STATUS_HANDLER_H_

#include <QString>
#include "StatusApi.h"

class PrinterStatusHandler
{
public:
	PrinterStatusHandler(QString printerName);
	virtual ~PrinterStatusHandler() {};
	bool isReady(QString *errorMsg = 0);

private:
    CStatusAPI	m_StatAPI;
    int m_Handle;
    QString m_PrinterName;
};

#endif /* PRINTER_STATUS_HANDLER_H_ */
