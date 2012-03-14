#include "search_deposit_dialog.h"

#include <QSignalMapper>
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

	QSignalMapper *mapper = new QSignalMapper(this);
	mapper->setMapping(ui.depositIdPushButton, 0);
	mapper->setMapping(ui.numberBankPushButton, 1);

	connect(ui.depositIdPushButton, SIGNAL(clicked()), mapper, SLOT(map()));
	connect(ui.numberBankPushButton, SIGNAL(clicked()), mapper, SLOT(map()));

	connect(mapper, SIGNAL(mapped(int)), this, SLOT(setSearchMode(int)));
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

/**
 * Returns the search mode to use.
 */
SearchDepositDialog::SearchMode SearchDepositDialog::searchMode()
{
	return m_SearchMode;
}

/**
 * Returns the deposit id.
 */
QString SearchDepositDialog::depositId()
{
	return ui.depositIdLineEdit->text().trimmed();
}

/**
 * Returns the bank id.
 */
QString SearchDepositDialog::bankId()
{
	return ui.bankIdComboBox->itemData(ui.bankIdComboBox->currentIndex()).toString();
}

/**
 * Returns the slip number.
 */
QString SearchDepositDialog::slipNumber()
{
	return ui.slipNumberLineEdit->text().trimmed();
}

/**
 * Sets the search mode to use.
 */
void SearchDepositDialog::setSearchMode(int button)
{
	if (button == 0) {
		m_SearchMode = ById;
	} else {
		m_SearchMode = BySlipNumber;
	}

	accept();
}
