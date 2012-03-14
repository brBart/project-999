/*
 * printer_status_handler.cpp
 *
 *  Created on: 11/08/2010
 *      Author: pc
 */

#include "printer_status_handler.h"

/**
 * @class PrinterStatusHandler
 * Class which make use of the Epson APD4 driver for monitoring the TMU printer.
 */

/**
 * Constructs the object with the printer's name.
 */
PrinterStatusHandler::PrinterStatusHandler(QString printerName)
		: m_PrinterName(printerName)
{

}

/**
 * Returns true if the printer is ready to print. Otherwise false with the error
 * message stored in the errorMsg parameter.
 */
bool PrinterStatusHandler::isReady(QString *errorMsg)
{
	bool isReady = true;
	QString msg;

	/*
	 * Initialize the Status API object. This loads the functions from
	 * EpsStmApi.dll
	 */
	if ( m_StatAPI.Initialize() == FALSE ) {
		msg = "Failed to open StatusAPI.";
		isReady = false;
	} else {

		/*
		 * Open a printer status monitor for the selected printer
		 */
		const char *printerName = m_PrinterName.toStdString().c_str();
		m_Handle = m_StatAPI.BiOpenMonPrinter(TYPE_PRINTER,
				const_cast<CHAR*>(printerName));

		if ( m_Handle <= 0 )
		{
			msg = "Failed to open printer status monitor.";
			isReady = false;
		} else {

			DWORD dwPrintStatus;

			if ( m_StatAPI.BiGetStatus(m_Handle, &dwPrintStatus) != SUCCESS ) {
				msg = "Failed to get printer status";
				isReady = false;
			} else {

				/*
				 * Notify any errors that occur.
				 */
				if ( dwPrintStatus & ASB_NO_RESPONSE ) {
					msg = "Impresora esta apagada o desconectada.";
					isReady = false;
				}

				else if ( dwPrintStatus & ASB_COVER_OPEN ) {
					msg = "Impresora tiene la cubierta abierta.";
					isReady = false;
				}

				else if ( dwPrintStatus & ASB_RECEIPT_NEAR_END ) {
					msg = "No hay papel en la impresora.";
					isReady = false;
				}

				else if ( dwPrintStatus & ASB_OFF_LINE ) {
					msg = "Impresora desconectada.";
					isReady = false;
				}

				else if ( dwPrintStatus & ASB_RECEIPT_END ) {
					msg = "No hay papel en la impresora.";
					isReady = false;
				}

				else if ( dwPrintStatus & ASB_SPOOLER_IS_STOPPED ) {
					msg = "Cola de impresión esta en pausa.";
					isReady = false;
				}
			}

			/*
			 * Always close the Status Monitor after using the Status API
			 */
			m_StatAPI.BiCloseMonPrinter(m_Handle);
		}
	}

	if (errorMsg != 0)
		*errorMsg = msg;

	return isReady;
}
