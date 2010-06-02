/*
 * not_fetched_customer_state.cpp
 *
 *  Created on: 25/05/2010
 *      Author: pc
 */

#include "not_fetched_customer_state.h"

/**
 * @class NotFetchedCustomerState
 * Class which methods response according to its state.
 */

/**
 * Constructs the object.
 */
NotFetchedCustomerState::NotFetchedCustomerState(CustomerDialog *dialog,
		QObject *parent) : CustomerState(dialog, parent)
{

}

/**
 * Does nothing.
 */
void NotFetchedCustomerState::setName(QString name)
{

}

/**
 * Does nothing.
 */
void NotFetchedCustomerState::save()
{

}
