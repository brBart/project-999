<?php
/**
 * Library containing the GetDepositByWorkingDayCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the deposit class.
 */
require_once('business/cash.php');

/**
 * Displays the deposit form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetDepositByWorkingDayCommand extends GetObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$id = Deposit::getDepositIdByWorkingDay(
				WorkingDay::getInstance($this->_mRequest->getProperty('working_day')),
				$this->_mRequest->getProperty('id'));
				
		if($id != 0)
			return Deposit::getInstance($id);
		else
			throw new Exception('Deposito no existe en esa jornada.');
	}
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Caja', 'Depositos');
		
		$working_day = $this->_mRequest->getProperty('working_day');
		$id = $this->_mRequest->getProperty('id');
		
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'main_menu_pos_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'cash_register_menu_html.tpl',
				'content' => 'deposit_menu_html.tpl', 'notify' => '1',
				'type' => 'error', 'message' => $msg, 'working_day' => $working_day,
				'id' => $id), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		$back_trace = array('Inicio', 'Caja', 'Depositos');
		
		// Build the back link.
		$back_link = (is_null($backQuery)) ? 'index.php?cmd=show_deposit_menu' :
				'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'] . '&start_date=' .
				$this->_mRequest->getProperty('start_date') . '&end_date=' .
				$this->_mRequest->getProperty('end_date');
		
		$working_day = WorkingDay::getInstance($this->_mRequest->getProperty('working_day'));
		$cash_register = $obj->getCashRegister();
		$shift = $cash_register->getShift();
		$user = $obj->getUser();
		$bank_account = $obj->getBankAccount();
		$bank = $bank_account->getBank();
		
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'deposit_pos_admin_form_html.tpl', 'cash_register_id' => $cash_register->getId(),
				'date' => $working_day->getDate(), 'shift' => $shift->getName() . ', ' . $shift->getTimeTable(),
				'cash_register_status' => (int)$cash_register->isOpen(),
				'status' => $obj->getStatus(),'key' => $key, 'back_link' => $back_link,
				'date_time' => $obj->getDateTime(), 'username' => $user->getUserName(),
				'slip_number' => $obj->getNumber(),
				'bank_account' => $bank_account->getNumber() . ', ' . $bank_account->getHolderName(),
				'bank' => $bank->getName()), 'site_html.tpl');
	}
}
?>