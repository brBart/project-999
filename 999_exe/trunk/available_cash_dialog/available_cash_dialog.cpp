#include "available_cash_dialog.h"

#include "../console/console_factory.h"
#include "../enter_key_event_filter/enter_key_event_filter.h"
#include "../xml_transformer/xml_transformer_factory.h"

/**
 * @class AvailableCashDialog
 * Dialog for displaying and adding available cash from the cash receipts to a
 * deposit document on the server.
 */

/**
 * Constructs the dialog.
 */
AvailableCashDialog::AvailableCashDialog(QNetworkCookieJar *jar, QUrl *url,
		QWidget *parent, Qt::WindowFlags f)
    : QDialog(parent, f), m_ServerUrl(url)
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
}

/**
 * Destroys the console object.
 */
AvailableCashDialog::~AvailableCashDialog()
{
	delete m_Console;
}

/**
 * Populates the the cash list with all the cash receipts available.
 */
void AvailableCashDialog::init()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_available_cash_receipt_list");
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("cash_receipt_list");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();


	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Sets the Console object.
 */
void DiscountDialog::setConsole()
{
	QMap<QString, QLabel*> elements;
	elements.insert("cash_receipt", ui.cashReceiptFailedLabel);
	elements.insert("amount", ui.amountFailedLabel);

	m_Console = ConsoleFactory::instance()->createWidgetConsole(elements);
	m_Console->setFrame(ui.webView->page()->mainFrame());
}
