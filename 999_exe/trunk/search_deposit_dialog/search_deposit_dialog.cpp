#include "search_deposit_dialog.h"

#include "../xml_transformer/xml_transformer_factory.h"
#include "../console/console_factory.h"

/**
 * @class SearchDepositDialog
 * Dialog for searching a deposit by id or slip number and bank.
 */

/**
 * Constructs the dialog.
 */
SearchDepositDialog::SearchDepositDialog(QNetworkCookieJar *jar, QUrl *url,
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
}

/**
 * Destroys the console.
 */
SearchDepositDialog::~SearchDepositDialog()
{
	delete m_Console;
}

/**
 * Populates the combo box with a list of all the banks available.
 */
void SearchDepositDialog::init()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_bank_list");
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("bank_list");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();

		ui.bankIdComboBox->addItem("", "");
		QMap<QString, QString> *bank;
		for (int i = 0; i < list.size(); i++) {
			bank = list[i];
			ui.bankIdComboBox->addItem(bank->value("name"),
					bank->value("bank_id"));
		}
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}
