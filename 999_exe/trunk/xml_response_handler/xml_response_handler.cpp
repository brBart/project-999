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
	if (!document.setContent(content)) {
		if (errorMsg != 0)
			*errorMsg = (content == "") ?
					"FATAL ERROR: Parse error or connection lost." : content;
		emit sessionStatusChanged(false);
		return Error;
	}

	if (checkForError(&document, msg)) {
		if (errorMsg != 0)
			*errorMsg = msg;
		emit sessionStatusChanged(true);
		return Error;
	}

	QDomNodeList elements = document.elementsByTagName("logout");
	if (!elements.isEmpty()) {
		if (errorMsg != 0)
			*errorMsg = "La sesión ha terminado.";
		emit sessionStatusChanged(false);
		return Error;
	}

	if (!validateResponse(&document, msg)) {
		if (errorMsg != 0)
			*errorMsg = msg;
		emit sessionStatusChanged(true);
		return Failure;
	}

	transformer->transform(&document);

	emit sessionStatusChanged(true);
	return Success;
}

/**
 * Checks if the content contains a generated error from the server.
 */
bool XmlResponseHandler::checkForError(QDomDocument *document, QString &errorMsg)
{
	QDomNodeList elements = document->elementsByTagName("error");

	if (!elements.isEmpty()) {
		elements = document->elementsByTagName("message");
		errorMsg = elements.at(0).toElement().text();
		return true;
	}

	return false;
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
