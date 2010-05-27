/*
 * fetched_customer_state.cpp
 *
 *  Created on: 25/05/2010
 *      Author: pc
 */

#include "fetched_customer_state.h"

#include <QUrl>
#include "../xml_transformer/stub_xml_transformer.h"
#include "../xml_response_handler/xml_response_handler.h"

FetchedCustomerState::FetchedCustomerState(CustomerDialog *dialog, QObject *parent)
		: CustomerState(dialog, parent)
{

}

void FetchedCustomerState::setName(QString name)
{
	QUrl url = m_Dialog->url();
	url.addQueryItem("cmd", "set_name_object");
	url.addQueryItem("value", name);
	url.addQueryItem("key", m_Dialog->customerKey());
	url.addQueryItem("type", "xml");

	connect(m_Dialog->httpRequest(), SIGNAL(finished(QString)), this,
			SLOT(nameSetted(QString)));

	m_Dialog->httpRequest()->get(url, true);
}

void FetchedCustomerState::save()
{
	QUrl url = m_Dialog->url();
	url.addQueryItem("cmd", "save_object");
	url.addQueryItem("key", m_Dialog->customerKey());
	url.addQueryItem("type", "xml");

	QString content = m_Dialog->httpRequest()->get(url);

	QString errorMsg, elementId;
	StubXmlTransformer *transformer = new StubXmlTransformer();
	XmlResponseHandler::ResponseType response =
			m_Dialog->xmlResponseHandler()
			->handle(content, transformer, &errorMsg, &elementId);
	if (response == XmlResponseHandler::Success) {
		m_Dialog->accept();
	} else if (response == XmlResponseHandler::Failure) {
		m_Dialog->console()->displayFailure(errorMsg, elementId);
	} else {
		m_Dialog->console()->displayError(errorMsg);
	}

	delete transformer;
}

void FetchedCustomerState::nameSetted(QString content)
{
	QString errorMsg;
	StubXmlTransformer *transformer = new StubXmlTransformer();
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

	m_Dialog->httpRequest()->disconnect(this);
}
