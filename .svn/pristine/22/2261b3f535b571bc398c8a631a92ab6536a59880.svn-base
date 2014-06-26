<?php
/**
 * VERICHECK INC CONFIDENTIAL
 * 
 * Vericheck Incorporated 
 * All Rights Reserved.
 * 
 * NOTICE: 
 * All information contained herein is, and remains the property of 
 * Vericheck Inc, if any.  The intellectual and technical concepts 
 * contained herein are proprietary to Vericheck Inc and may be covered 
 * by U.S. and Foreign Patents, patents in process, and are protected 
 * by trade secret or copyright law. Dissemination of this information 
 * or reproduction of this material is strictly forbidden unless prior 
 * written permission is obtained from Vericheck Inc.
 *
 * @copyright VeriCheck, Inc. 
 * @version $$Id: AppController.php 1350 2013-08-20 03:10:56Z anit $$
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $components = array(
		'Acl',
		'Auth' => array(
			'authorize' => array(
				'Actions' => array('actionPath' => 'controllers')
			)
		),
		'Session',
		'DebugKit.Toolbar'
		);

	public $helpers = array('Html', 'Form', 'Session', 'BootstrapCake.Bootstrap');

	protected $_pageTitle;
	
	protected $_pageSubTitle;

	public function beforeFilter() {
		//record activity is called to use Table behaviour
		// comment -anit
		// this is giving error while executing Billing
		// uncomment for use.
		//$this->recordActivity();
		//Configure AuthComponent
		//$this->Session->renew(); // deena 4/28/2013 added to check logout of session in active user
		$this->Auth->loginAction = array('controller' => 'Users', 'action' => 'login');
		$this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
		$this->Auth->loginRedirect = array('controller' => 'pages', 'action' => 'verihome');
	}

	public function beforeRender() {
		$this->set('title_for_layout', $this->_pageTitle);
		$this->set('sub_title_for_layout', $this->_pageSubTitle);
	}

/**
 * constructor in CakePhp constructClasses
 * display DebugKit only if reguired.
 */
	public function constructClasses() {
		parent::constructClasses();
		
		if (Configure::read('debug') >= 1):
			$this->components[] = 'DebugKit.Toolbar';
		endif;
		//pr(Configure::read('Session'));
	}
/**
 * used for using logable behaviour
 */
	public function recordActivity() {
		//active user as stated in Table behaviour
		//note: Where "$activeUser" should be an array in the standard format for the User model used :
		//$activeUser = array( $UserModel->alias => array( $UserModel->primaryKey => 123, $UserModel->displayField => 'Alexander'));
		$activeUser = $this->Auth->user();
		if (count($this->uses) && $this->{$this->modelClass}->Behaviors->attached('Table')) {
			$this->{$this->modelClass}->setUserData($activeUser);
			$this->{$this->modelClass}->saveActivities($activeUser);
		}
	}
}