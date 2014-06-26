<?php
class NavigationComponent extends Component {
	
	public $components = array('Acl','Session');
	public function navigate() {
		
		$this->Session->write('navigatesession',$this->Acl->Aro->Permission->find('all'));
	
	}
	
}