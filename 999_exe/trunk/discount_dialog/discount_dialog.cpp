#include "discount_dialog.h"

#include "../console/console_factory.h"
#include "../enter_key_event_filter/enter_key_event_filter.h"
#include "../xml_transformer/xml_transformer_factory.h"

/**
 * @class DiscountDialog
 * Dialog use for setting the percentage to a discount.
 */

/**
 * Constructs the dialog.
 */
DiscountDialog::DiscountDialog(QNetworkCookieJar *jar, QUrl *url,
		QString discountKey, QWidget *parent, Qt::WindowFlags f)
    : QDialog(parent, f), m_ServerUrl(url), m_DiscountKey(discountKey)
{
	ui.setupUi(this);
	EnterKeyEventFilter *filter = new EnterKeyEventFilter(this);
	ui.okPushButton->installEventFilter(filter);
	ui.cancelPushButton->installEventFilter(filter);
	m_IsPercentageSet = false;

	setConsole();

	m_Request = new HttpRequest(jar, this);
	m_Handler = new XmlResponseHandler(this);

	connect(ui.percentageLineEdit, SIGNAL(blurAndChanged(QString)), this,
			SLOT(setPercentage(QString)));
	connect(ui.okPushButton, SIGNAL(clicked()), this, SLOT(okClicked()));
}

/**
 * Destroys the console.
 */
DiscountDialog::~DiscountDialog()
{
	delete m_Console;
}

/**
 * Sets the percentage to the discount object in the server.
 */
void DiscountDialog::setPercentage(QString value)
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "set_percentage_object");
	url.addQueryItem("key", m_DiscountKey);
	url.addQueryItem("value", value);
	url.addQueryItem("type", "xml");

	connect(m_Request, SIGNAL(finished(QString)), this,
			SLOT(percentageSetted(QString)));

	m_Request->get(url, true);
}

/**
 * Handles the setPercentage query response.
 * If it fails it displays the failure message on the console.
 */
void DiscountDialog::percentageSetted(QString content)
{
	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("stub");

	QString errorMsg;
	XmlResponseHandler::ResponseType response =
			m_Handler->handle(content, transformer, &errorMsg);
	if (response == XmlResponseHandler::Success) {
		m_Console->cleanFailure("percentage");
		m_IsPercentageSet = true;
	} else if (response == XmlResponseHandler::Failure) {
		m_Console->cleanFailure("percentage");
		m_Console->displayFailure(errorMsg, "percentage");
		m_IsPercentageSet = false;
	} else {
		m_Console->displayError(errorMsg);
		m_IsPercentageSet = false;
	}

	delete transformer;
}

void DiscountDialog::okClicked()
{
	if (m_IsPercentageSet) {
		accept();
	} else {
		ui.percentageLineEdit->setFocus();
		ui.percentageLineEdit->selectAll();
	}
}

/**
 * Sets the Console object.
 */
void DiscountDialog::setConsole()
{
	QMap<QString, QLabel*> elements;
	elements.insert("percentage", ui.percentageFailedLabel);

	m_Console = ConsoleFactory::instance()->createWidgetConsole(elements);
	m_Console->setFrame(ui.webView->page()->mainFrame());
}
