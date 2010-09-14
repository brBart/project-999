#include "search_deposit_dialog.h"

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
	connect(ui.okPushButton, SIGNAL(clicked()), this, SLOT(fetchKey()));
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

}
