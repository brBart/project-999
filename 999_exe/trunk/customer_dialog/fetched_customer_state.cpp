/*
 * fetched_customer_state.cpp
 *
 *  Created on: 25/05/2010
 *      Author: pc
 */

#include "fetched_customer_state.h"

#include <QUrl>
#include "../xml_transformer/xml_transformer_factory.h"

/**
 * @class FetchedCustomerState
 * Class which methods response according to its state.
 */

/**
 * Constructs the object.
 */
FetchedCustomerState::FetchedCustomerState(CustomerDialog *dialog, QObject *parent)
		: CustomerState(dialog, parent)
{

}

/**
 * Sets the customer name on the server.
 */
void FetchedCustomerState::setName(QString name)
{
	HttpRequest *request =
			new HttpRequest(m_Dialog->httpRequest()->cookieJar(), this);

	QUrl url = m_Dialog->url();
	url.addQueryItem("cmd", "set_name_object");
	url.addQueryItem("value", name);
	url.addQueryItem("key", m_Dialog->customerKey());
	url.addQueryItem("type", "xml");

	connect(request, SIGNAL(finished(QString)), this,
			SLOT(nameSetted(QString)));

	request->get(url, true);
}

/**
 * Saves the customer data on the server.
 */
void FetchedCustomerState::save()
{
	m_Dialog->console()->reset();

	QUrl url = m_Dialog->url();
	url.addQueryItem("cmd", "save_object");
	url.addQueryItem("key", m_Dialog->customerKey());
	url.addQueryItem("type", "xml");

	QString content = m_Dialog->httpRequest()->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("stub");

	QString errorMsg, elementId;
	XmlResponseHandler::ResponseType response =
			m_Dialog->xmlResponseHandler()
			->handle(content, transformer, &errorMsg, &elementId);
	if (response == XmlResponseHandler::Success) {
		m_Dialog->accept();
	} else if (response == XmlResponseHandler::Failure) {
		m_Dialog->console()->displayFailure(errorMsg, elementId);
		// Best I could...
		if (elementId == "nit") {
			m_Dialog->nitLineEdit()->setFocus();
			m_Dialog->nitLineEdit()->selectAll();
		} else {
			m_Dialog->nameLineEdit()->setFocus();
			m_Dialog->nameLineEdit()->selectAll();
		}
	} else {
		m_Dialog->console()->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Handles the setName query response.
 * If it fails it displays the failure message on the console.
 */
void FetchedCustomerState::nameSetted(QString content)
{
	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("stub");

	QString errorMsg;
	XmlResponseHandler::ResponseType response =
			m_Dialog->xmlResponseHandler()
			->handle(content, transformer, &errorMsg);
	if (response == XmlResponseHandler::Success) {
		m_Dialog->console()->cleanFailure("name");
	} else if (response == XmlResponseHandler::Failure) {
		m_Dialog->console()->displayFailure(errorMsg, "name");
	} else {
		m_Dialog->console()->displayError(errorMsg);
	}

	delete transformer;
}
