/*
 * customer_state.cpp
 *
 *  Created on: 25/05/2010
 *      Author: pc
 */

#include "customer_state.h"

#include "../xml_transformer/xml_transformer_factory.h"

/**
 * @class CustomerState
 * Defines common functionality for the derived classes.
 */

/**
 * Constructs an CustomerState object.
 */
CustomerState::CustomerState(CustomerDialog *dialog, QObject *parent)
		: QObject(parent), m_Dialog(dialog)
{

}

/**
 * Fetchs a customer from the server.
 * If it succeeds it changes to FetchedState as the actual state on the dialog. If
 * it fails it changes to NotFetchedState as the actual state on the dialog.
 */
void CustomerState::fetchCustomer(QString nit)
{
	QUrl url = m_Dialog->url();
	url.addQueryItem("cmd", "get_customer");
	url.addQueryItem("nit", nit);
	url.addQueryItem("type", "xml");

	QString content = m_Dialog->httpRequest()->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("customer");

	QString errorMsg, elementId;
	XmlResponseHandler::ResponseType response = m_Dialog->xmlResponseHandler()
					->handle(content, transformer, &errorMsg, &elementId);
	if (response == XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();
		QMap<QString, QString> *params = list[0];
		m_Dialog->setCustomerKey(params->value("key"));
		m_Dialog->nameLineEdit()->setText(params->value("name"));
		m_Dialog->setState(m_Dialog->fetchedState());
		m_Dialog->console()->reset();
	} else if (response == XmlResponseHandler::Failure) {
		m_Dialog->console()->displayFailure(errorMsg, elementId);
		m_Dialog->setState(m_Dialog->notFetchedState());
	} else {
		m_Dialog->console()->displayError(errorMsg);
		m_Dialog->setState(m_Dialog->notFetchedState());
	}

	delete transformer;
}
