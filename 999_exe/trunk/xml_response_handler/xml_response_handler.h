/*
 * xml_response_handler.h
 *
 *  Created on: 27/04/2010
 *      Author: pc
 */

#ifndef XML_RESPONSE_HANDLER_H_
#define XML_RESPONSE_HANDLER_H_

#include <QObject>
#include <QString>
#include "../xml_transformer/xml_transformer.h"

class XmlResponseHandler : public QObject
{
	Q_OBJECT

public:
	enum ResponseType {Success, Failure, Error};
	XmlResponseHandler(QObject *parent = 0);
	virtual ~XmlResponseHandler() {};
	ResponseType handle(QString content, XmlTransformer *transformer,
			QString *errorMsg = 0);

signals:
	void sessionStatusChanged(bool isActive);

private:
	bool validateResponse(QDomDocument *document, QString &failMsg);
};

#endif /* XML_RESPONSE_HANDLER_H_ */
