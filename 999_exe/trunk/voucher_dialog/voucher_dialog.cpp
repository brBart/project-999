#include "voucher_dialog.h"

#include <QUrl>
#include "../console/console_factory.h"
#include "../xml_transformer/xml_transformer_factory.h"

/**
 * @class VoucherDialog
 * Dialog for adding a voucher to cash receipt on the server.
 */

/**
 * Constructs the dialog.
 */
VoucherDialog::VoucherDialog(QNetworkCookieJar *jar, QUrl *url,
		QWidget *parent, Qt::WindowFlags f) : QDialog(parent, f), m_ServerUrl(url)
{
	ui.setupUi(this);

	setConsole();

	m_Request = new HttpRequest(jar, this);
	m_Handler = new XmlResponseHandler(this);

	connect(m_Handler, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)));
	//connect(ui.okPushButton, SIGNAL(clicked()), this, SLOT(addVoucher()));
}

/**
 * Destroys the console object.
 */
VoucherDialog::~VoucherDialog()
{
	delete m_Console;
}

/**
 * Populates the combo boxes with data from the server.
 */
void VoucherDialog::init()
{
	fetchTypes();
	fetchBrands();
}

/**
 * Sets the Console object.
 */
void VoucherDialog::setConsole()
{
	QMap<QString, QLabel*> elements;
	elements.insert("transaction", ui.transactionFailedLabel);
	elements.insert("payment_card_number", ui.paymentCardNumberFailedLabel);
	elements.insert("type_id", ui.typeIdFailedLabel);
	elements.insert("brand_id", ui.brandIdFailedLabel);
	elements.insert("name", ui.nameFailedLabel);
	elements.insert("expiration_date", ui.expirationDateFailedLabel);
	elements.insert("amount", ui.amountFailedLabel);

	m_Console = ConsoleFactory::instance()->createWidgetConsole(elements);
	m_Console->setFrame(ui.webView->page()->mainFrame());
}

/**
 * Populates the types combo box with data from the server.
 */
void VoucherDialog::fetchTypes()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_payment_card_type_list");
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("payment_card_type_list");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();

		ui.typeIdComboBox->addItem("", "");
		QMap<QString, QString> *type;
		for (int i = 0; i < list.size(); i++) {
			type = list[i];
			ui.typeIdComboBox->addItem(type->value("name"),
					type->value("payment_card_type_id"));
		}
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Populates the brands combo box with data from the server.
 */
void VoucherDialog::fetchBrands()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_payment_card_brand_list");
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("payment_card_brand_list");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();

		ui.brandIdComboBox->addItem("", "");
		QMap<QString, QString> *brand;
		for (int i = 0; i < list.size(); i++) {
			brand = list[i];
			ui.brandIdComboBox->addItem(brand->value("name"),
					brand->value("payment_card_brand_id"));
		}
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}
