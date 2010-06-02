#include "customer_dialog.h"

#include <QMap>
#include "../console/console_factory.h"
#include "not_fetched_customer_state.h"
#include "fetched_customer_state.h"
#include "../enter_key_event_filter/enter_key_event_filter.h"

/**
 * @class CustomerDialog
 * Dialog used to fetch a customer from the server.
 */

/**
 * Constructs the dialog.
 * Installs the filter for the push buttons for the enter key functinality. Also it
 * sets the 3 states.
 */
CustomerDialog::CustomerDialog(QNetworkCookieJar *jar, QUrl *url, QWidget *parent,
		Qt::WindowFlags f) : QDialog(parent, f), m_ServerUrl(url)
{
	ui.setupUi(this);
	EnterKeyEventFilter *filter = new EnterKeyEventFilter(this);
	ui.okPushButton->installEventFilter(filter);
	ui.cancelPushButton->installEventFilter(filter);

	setConsole();

	m_Request = new HttpRequest(jar, this);
	m_Handler = new XmlResponseHandler(this);

	connect(m_Handler, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)));

	m_NotFetchedState = new NotFetchedCustomerState(this, this);
	m_FetchedState = new FetchedCustomerState(this, this);
	m_State = m_NotFetchedState;

	connect(ui.nitLineEdit, SIGNAL(blurAndChanged(QString)), this,
			SLOT(fetchCustomer(QString)));
	connect(ui.nameLineEdit, SIGNAL(blurAndChanged(QString)), this,
			SLOT(setName(QString)));
	connect(ui.okPushButton, SIGNAL(clicked()), this, SLOT(save()));
}

/**
 * If a customer was fetch it removes from the session on the server. Destroys the
 * console object.
 */
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

/**
 * Sets the actual state.
 */
void CustomerDialog::setState(CustomerState *state)
{
	m_State = state;
}

/**
 * Returns the NotFetchedState object.
 */
CustomerState* CustomerDialog::notFetchedState()
{
	return m_NotFetchedState;
}

/**
 * Returns the FetchedState object.
 */
CustomerState* CustomerDialog::fetchedState()
{
	return m_FetchedState;
}

/**
 * Returns the server url.
 */
QUrl CustomerDialog::url()
{
	return *m_ServerUrl;
}

/**
 * Returns the Console object.
 */
Console* CustomerDialog::console()
{
	return m_Console;
}

/**
 * Returns the HttpRequest object.
 */
HttpRequest* CustomerDialog::httpRequest()
{
	return m_Request;
}

/**
 * Returns the XmlResponseHandler object.
 */
XmlResponseHandler* CustomerDialog::xmlResponseHandler()
{
	return m_Handler;
}

/**
 * Sets the customer session key.
 */
void CustomerDialog::setCustomerKey(QString key)
{
	m_Key = key;
}

/**
 * Returns the customer session key.
 */
QString CustomerDialog::customerKey()
{
	return m_Key;
}

/**
 * Returns the nameLineEdit widget.
 */
LineEdit* CustomerDialog::nameLineEdit()
{
	return ui.nameLineEdit;
}

/**
 * Calls the fetchCustomer method on the actual state.
 */
void CustomerDialog::fetchCustomer(QString nit)
{
	m_State->fetchCustomer(nit);
}

/**
 * Calls the setName method on the actual state.
 */
void CustomerDialog::setName(QString name)
{
	m_State->setName(name);
}

/**
 * Calls save method on the actual state.
 */
void CustomerDialog::save()
{
	m_State->save();
}

/**
 * Sets the Console object.
 */
void CustomerDialog::setConsole()
{
	QMap<QString, QLabel*> elements;
	elements.insert("nit", ui.nitFailedLabel);
	elements.insert("name", ui.nameFailedLabel);

	m_Console = ConsoleFactory::instance()->createWidgetConsole(elements);
	m_Console->setFrame(ui.webView->page()->mainFrame());
}
