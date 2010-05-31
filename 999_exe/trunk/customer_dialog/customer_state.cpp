/*
 * customer_state.cpp
 *
 *  Created on: 25/05/2010
 *      Author: pc
 */

#include "customer_state.h"

#include "../xml_transformer/xml_transformer_factory.h"

CustomerState::CustomerState(CustomerDialog *dialog, QObject *parent)
		: QObject(parent), m_Dialog(dialog)
{

}

void CustomerState::fetchCustomer(QString nit)
{
	QUrl url = m_Dialog->url();
	url.addQueryItem("cmd", "get_customer");
	url.addQueryItem("nit", nit);
	url.addQueryItem("type", "xml");

	QString content = m_Dialog->httpRequest()->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("customer");

	QString errorMsg;
	if (m_Dialog->xmlResponseHandler()
			->handle(content, transformer, &errorMsg) ==
					XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();
		QMap<QString, QString> *params = list[0];
		m_Dialog->setCustomerKey(params->value("key"));
		m_Dialog->nameLineEdit()->setText(params->value("name"));
		m_Dialog->setState(m_Dialog->fetchedState());
		m_Dialog->console()->reset();
	} else {
		m_Dialog->console()->displayError(errorMsg);
		m_Dialog->setState(m_Dialog->notFetchedState());
	}

	delete transformer;
}
