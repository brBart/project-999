/*
 * fetched_customer_state.h
 *
 *  Created on: 25/05/2010
 *      Author: pc
 */

#ifndef FETCHED_CUSTOMER_STATE_H_
#define FETCHED_CUSTOMER_STATE_H_

#include "customer_state.h"

class FetchedCustomerState: public CustomerState
{
	Q_OBJECT

public:
	FetchedCustomerState(CustomerDialog *dialog, QObject *parent = 0);
	virtual ~FetchedCustomerState() {};
	void setName(QString name);
	void save();

public slots:
	void nameSetted(QString content);
};

#endif /* FETCHED_CUSTOMER_STATE_H_ */
