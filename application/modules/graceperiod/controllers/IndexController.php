<?php
/*
############################################################################
#  This file is part of OurBank.
############################################################################
#  OurBank is free software: you can redistribute it and/or modify
#  it under the terms of the GNU Affero General Public License as
#  published by the Free Software Foundation, either version 3 of the
#  License, or (at your option) any later version.
############################################################################
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU Affero General Public License for more details.
############################################################################
#  You should have received a copy of the GNU Affero General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
############################################################################
*/
?>

<?php
class Graceperiod_IndexController extends Zend_Controller_Action{

	public function init() {
		$this->view->pageTitle = "Grace period";

		$globalsession = new App_Model_Users();
		$this->view->globalvalue = $globalsession->getSession();
			$this->view->username = $this->view->globalvalue[0]['username'];
			$this->view->createdby = $this->view->globalvalue[0]['id'];
		
// 		if (($this->view->globalvalue[0]['id'] == 0)) {
// 			$this->_redirect('index/logout');
// 		}
		$this->view->adm = new App_Model_Adm();
		$this->view->dateconvert = new Creditline_Model_dateConvertor();

		$test = new DH_ClassInfo(APPLICATION_PATH . '/modules/graceperiodindex/controllers/');
		$module = $test->getControllerClassNames();
		$modulename = explode("_", $module[0]);
 		$this->view->modulename = strtolower($modulename[0]);
	}

	public function indexAction() {
	}
	public function graceperiodaddAction() {
		//Acl
// 		$access = new App_Model_Access();
// 		$checkaccess = $access->accessRights('Institution',$this->view->globalvalue[0]['name'],'addinstitutionAction');
// 		if (($checkaccess != NULL)) {

		//add
		$form=$this->view->form=new Graceperiod_Form_Graceperiod();

		$creditline = $this->view->adm->viewRecord("ob_creditline","id","DESC");

		foreach($creditline as $creditline){
			$form->creditline_id->addMultiOption($creditline['id'],$creditline['name']);
		}

		if ($this->_request->isPost() && $this->_request->getPost('Submit')) { 
			$formData = $this->_request->getPost();
				if ($form->isValid($formData)) {

					$formdata1=array('name'=>$formData['name'],'days'=>$formData['days'],'creditline_id'=>$formData['creditline_id'],'status'=>$formData['status'],'created_by'=>$this->view->createdby);

					$this->view->adm->addRecord("ob_graceperiod",$formdata1);
					$this->_redirect('graceperiodindex/index');
				}
		}


// 		} else {
// 		$this->_redirect('index/index');
// 		}
	}

	public function graceperiodeditAction() {
		//Acl
// 		$access = new App_Model_Access();
// 		$checkaccess = $access->accessRights('Institution',$this->view->globalvalue[0]['name'],'addinstitutionAction');
// 		if (($checkaccess != NULL)) {

		//edit
		$form=$this->view->form=new Graceperiod_Form_Graceperiod();
		
		$creditline = $this->view->adm->viewRecord("ob_creditline","id","DESC");

		foreach($creditline as $creditline){
			$form->creditline_id->addMultiOption($creditline['id'],$creditline['name']);
		}

		$graceperiod_id=$this->view->id=$this->_request->getParam('graceperiod_id');
		
		$viewgrace=new Graceperiod_Model_Graceperiod();
		$this->view->viewgracedetails=$viewgracedetails1=$viewgrace->fetchgraceperiodforID($graceperiod_id);
		foreach($viewgracedetails1 as $viewgracedetails){
			$this->view->form->name->setValue($viewgracedetails['name']);
			$this->view->form->days->setValue($viewgracedetails['days']);
			$this->view->form->creditline_id->setValue($viewgracedetails['creditline_id']);
			$this->view->form->status->setValue($viewgracedetails['status']);

			$formdata2=array('id'=>$viewgracedetails['id'],'name'=>$viewgracedetails['name'],'days'=>$viewgracedetails['days'],'creditline_id'=>$viewgracedetails['creditline_id'],'status'=>$viewgracedetails['status'],'created_by'=>$viewgracedetails['created_by'],'created_date'=>$viewgracedetails['created_date']);
		}
                $form->name->removeValidator('Db_NoRecordExists');
		if ($this->_request->isPost() && $this->_request->getPost('Update')) {  

			$id=$this->view->id=$this->_request->getParam('graceperiod_id');
			$formData = $this->_request->getPost();

			if ($form->isValid($formData)) {
				$this->view->adm->updateLog("ob_graceperiod_log",$formdata2,$this->view->createdby);
				//update 					
				$formdata1=array('name'=>$formData['name'],'days'=>$formData['days'],'creditline_id'=>$formData['creditline_id'],'status'=>$formData['status'],'created_by'=>$this->view->createdby);

				$this->view->adm->updateRecord("ob_graceperiod",$id,$formdata1);
				$this->_redirect('/graceperiodindex');
			}
		}

// 		$this->view->form = $form;
// 		$this->view->form->Submit->setName('Update');
// 		} else {
// 		$this->_redirect('index/index');
// 		}
	}

	public function graceperiodviewAction() {
	}

	public function graceperioddeleteAction() {
// 		$access = new App_Model_Access();
// 		$checkaccess = $access->accessRights('Graceperiod',$this->view->globalvalue[0]['name'],'graceperioddeleteAction');
// 		if (($checkaccess != NULL)) {
			$form = new Graceperiod_Form_Delete();
			$this->view->form = $form;
			$this->view->id = $this->_getParam('graceperiod_id');
			if($this->_request->isPost() && $this->_request->getPost('Delete')) {
				$formdata = $this->_request->getPost();
				if($form->isValid($formdata)) {
					$redirect = $this->view->adm->deleteAction("ob_graceperiod",$this->view->modulename,$this->view->id);
					$this->_redirect("/".$redirect);
	
				}
			}


// 		} else {
//              $this->_redirect('index/error');
// 		}
	}
}
