/*
 * customer_state.h
 *
 *  Created on: 25/05/2010
 *      Author: pc
 */

#ifndef CUSTOMER_STATE_H_
#define CUSTOMER_STATE_H_

#include <QObject>
#include "customer_dialog.h"

class CustomerState : public QObject
{
	Q_OBJECT

public:
	CustomerState(CustomerDialog *dialog, QObject *parent = 0);
	virtual ~CustomerState() {};
	virtual void fetchCustomer(QString nit);
	virtual void setName(QString name) = 0;
	virtual void save() = 0;

protected:
	CustomerDialog *m_Dialog;
};

#endif /* CUSTOMER_STATE_H_ */
