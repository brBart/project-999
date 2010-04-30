/*
 * xml_response_handler.cpp
 *
 *  Created on: 27/04/2010
 *      Author: pc
 */

#include "xml_response_handler.h"

#include <QDomDocument>

/**
 * @class XmlResponseHandler
 * Class in charge of handle any response content received from the server.
 */

/**
 * Constructs XmlResponseHandler with parent.
 */
XmlResponseHandler::XmlResponseHandler(QObject *parent) : QObject(parent)
{
}

/**
 * Handles the response from the server.
 * Can return 3 types of response, Success, Failure and Error. In case of failure
 * is because a validation and for an error is for a parsing error or an end of
 * session.
 */
XmlResponseHandler::ResponseType XmlResponseHandler::handle(QString content,
		XmlTransformer *transformer, QString *errorMsg)
{
	QDomDocument document;

	QString msg;
	int line, column;
	if (!document.setContent(content, &msg, &line, &column)) {
		errorMsg = &QString("Interno: " + msg + ", Line: %1, Column: %2")
				.arg(line).arg(column);
		emit sessionStatusChanged(false);
		return Error;
	}

	QDomNodeList elements = document.elementsByTagName("logout");
	if (!elements.isEmpty()) {
		errorMsg = new QString("La sesión ha terminado.");
		emit sessionStatusChanged(false);
		return Error;
	}

	if (!validateResponse(&document, msg)) {
		errorMsg = &msg;
		emit sessionStatusChanged(true);
		return Failure;
	}

	if (!transformer->transform(&document, &msg)) {
		errorMsg = &msg;
		emit sessionStatusChanged(true);
		return Error;
	}

	emit sessionStatusChanged(true);
	return Success;
}

/**
 * Validate the response, success or failure.
 */
bool XmlResponseHandler::validateResponse(QDomDocument *document,
		QString &failMsg)
{
	QDomNodeList elements = document->elementsByTagName("success");
	QDomElement result = elements.at(0).toElement();

	if (result.text() == "0") {
		elements = document->elementsByTagName("message");
		failMsg = elements.at(0).toElement().text();
		return false;
	}

	return true;
}
