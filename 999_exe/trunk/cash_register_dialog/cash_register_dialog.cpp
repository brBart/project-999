#include "cash_register_dialog.h"

#include "../xml_transformer/xml_transformer_factory.h"
#include "../console/console_factory.h"

/**
 * @class CashRegisterDialog
 * Dialog used to obtain a cash register object key from the server for future
 * transactions.
 */

/**
 * Constructs the dialog.
 */
CashRegisterDialog::CashRegisterDialog(QNetworkCookieJar *jar, QUrl *url,
		QWidget *parent, Qt::WindowFlags f) : QDialog(parent, f), m_ServerUrl(url)
{
	ui.setupUi(this);

	m_Console = ConsoleFactory::instance()
			->createWidgetConsole(QMap<QString, QLabel*>());
	m_Console->setFrame(ui.webView->page()->mainFrame());

	m_Request = new HttpRequest(jar, this);
	m_Handler = new XmlResponseHandler(this);

	connect(m_Handler, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)));
	connect(ui.okPushButton, SIGNAL(clicked()), this, SLOT(fetchKey()));
}

/**
 * Destroys the console object.
 */
CashRegisterDialog::~CashRegisterDialog()
{
	delete m_Console;
}

/**
 * Populates the combo box with a list of all the shifts available.
 */
void CashRegisterDialog::init()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_shift_list");
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("shift_list");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();

		ui.shiftIdComboBox->addItem("", "");
		QMap<QString, QString> *shift;
		for (int i = 0; i < list.size(); i++) {
			shift = list[i];
			ui.shiftIdComboBox->addItem(shift->value("name"),
					shift->value("shift_id"));
		}
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Fetchs for the cash register object key on the server.
 */
void CashRegisterDialog::fetchKey()
{
	QVariant id = ui.shiftIdComboBox->itemData(ui.shiftIdComboBox->currentIndex());

	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_cash_register");
	url.addQueryItem("shift_id", id.toString());
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("object_key");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();
		QMap<QString, QString> *params = list[0];
		m_Key = params->value("key");
		accept();
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Returns the cash register object key.
 */
QString CashRegisterDialog::key()
{
	return m_Key;
}
