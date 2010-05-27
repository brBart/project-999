#include "customer_dialog.h"

#include <QMap>
#include "../console/console_factory.h"
#include "not_fetched_customer_state.h"
#include "fetched_customer_state.h"

CustomerDialog::CustomerDialog(QNetworkCookieJar *jar, QUrl *url, QWidget *parent,
		Qt::WindowFlags f) : QDialog(parent, f), m_ServerUrl(url)
{
	ui.setupUi(this);

	setConsole();

	m_Request = new HttpRequest(jar, this);
	m_Handler = new XmlResponseHandler(this);

	connect(m_Handler, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)));

	m_NotFetchedState = new NotFetchedCustomerState(this, this);
	m_FetchedState = new FetchedCustomerState(this, this);
	m_State = m_NotFetchedState;

	connect(ui.nitLineEdit, SIGNAL(blur(QString)), this,
			SLOT(fetchCustomer(QString)));
	connect(ui.nameLineEdit, SIGNAL(blur(QString)), this, SLOT(setName(QString)));
	connect(ui.okPushButton, SIGNAL(clicked()), this, SLOT(save()));
}

CustomerDialog::~CustomerDialog()
{
	if (result() == QDialog::Accepted) {
		// Remove the customer object from the session on the server.
		QUrl url(*m_ServerUrl);
		url.addQueryItem("cmd", "remove_session_object");
		url.addQueryItem("key", customerKey());
		url.addQueryItem("type", "xml");

		m_Request->get(url, true);
	}

	delete m_Console;
}

void CustomerDialog::setState(CustomerState *state)
{
	m_State = state;
}

CustomerState* CustomerDialog::notFetchedState()
{
	return m_NotFetchedState;
}

CustomerState* CustomerDialog::fetchedState()
{
	return m_FetchedState;
}

QUrl CustomerDialog::url()
{
	return *m_ServerUrl;
}

Console* CustomerDialog::console()
{
	return m_Console;
}

HttpRequest* CustomerDialog::httpRequest()
{
	return m_Request;
}

XmlResponseHandler* CustomerDialog::xmlResponseHandler()
{
	return m_Handler;
}

void CustomerDialog::setCustomerKey(QString key)
{
	m_Key = key;
}

QString CustomerDialog::customerKey()
{
	return m_Key;
}

LineEdit* CustomerDialog::nameLineEdit()
{
	return ui.nameLineEdit;
}

void CustomerDialog::fetchCustomer(QString nit)
{
	m_State->fetchCustomer(nit);
}

void CustomerDialog::setName(QString name)
{
	m_State->setName(name);
}

void CustomerDialog::save()
{
	m_State->save();
}

void CustomerDialog::setConsole()
{
	QMap<QString, QLabel*> elements;
	elements.insert("nit", ui.nitFailedLabel);
	elements.insert("name", ui.nameFailedLabel);

	m_Console = ConsoleFactory::instance()->createWidgetConsole(elements);
	m_Console->setFrame(ui.webView->page()->mainFrame());
}
