/*
 * not_fetched_customer_state.h
 *
 *  Created on: 25/05/2010
 *      Author: pc
 */

#ifndef NOT_FETCHED_CUSTOMER_STATE_H_
#define NOT_FETCHED_CUSTOMER_STATE_H_

#include "customer_state.h"

class NotFetchedCustomerState: public CustomerState
{
	Q_OBJECT

public:
	NotFetchedCustomerState(CustomerDialog *dialog, QObject *parent = 0);
	virtual ~NotFetchedCustomerState() {};
	void setName(QString name);
	void save();
};

#endif /* NOT_FETCHED_CUSTOMER_STATE_H_ */
