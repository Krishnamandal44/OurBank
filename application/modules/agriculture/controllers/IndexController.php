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
class Agriculture_IndexController extends Zend_Controller_Action 
{
    public function init() 
    {
//it is create session and implement ACL concept...
        $this->view->pageTitle=$this->view->translate('Agriculture');
        $globalsession = new App_Model_Users();
        $this->view->globalvalue = $globalsession->getSession();
        $this->view->createdby = $this->view->globalvalue[0]['id'];
        $this->view->username = $this->view->globalvalue[0]['username'];
//         if (($this->view->globalvalue[0]['id'] == 0)) {
//             $this->_redirect('index/logout');
//         }
        $this->view->adm = new App_Model_Adm();
    }

    public function indexAction() 
    {
    }

//add family health add action
    public function addAction() 
    {
        $this->view->title = $this->view->translate("Add agriculture details");
        $this->view->memberid=$member_id=$this->_getParam('id');
//count number of family members
        $family_model=new Agriculture_Model_agriculture();
        $this->view->land_details=$count_member = $family_model->edit_landtypes();
        $this->view->family_number=$number=count($count_member);
//load form with respective to number of family member
        $addForm = new Agriculture_Form_agriculture($number);
        $this->view->form=$addForm;
//set the value of member name and sex

//         for($i=1;$i<=$number;$i++){
//         foreach($count_member as $land_types){
//             $addForm->tenant->addMultiOption($land_types['id'],$land_types['landtype_name']);
//             
//          }
//         }

//set the value of health problem and other drop down box...
        $owner = $this->view->adm->viewRecord("ourbank_master_ownershiptype","id","DESC");
       $this->view->ownertype = $owner;
//             foreach($owner as $owner1){ 
//             $addForm->ownertype->addMultiOption($owner1['id'],$owner1['ownertype']);
//             }

//insert the family health details 

        if ($this->_request->getPost('submit')) {
            $land_id=$this->_request->getParam('tenant');
            $village_id=$this->_request->getParam('villagename');
            $owner_id=$this->_request->getParam('ownertype');
            $ownername=$this->_request->getParam('ownername');
            $survey_no=$this->_request->getParam('survey');
            $acre=$this->_request->getParam('acre');
            $value=$this->_request->getParam('acrevalue');
            $i = 0;
            foreach($this->_getParam('tenant') as $val) {
                $agri = array('id' => '',
                            'member_id'=>$member_id,
                            'landowner_id'=>$owner_id[$i],
                            'landowner_name'=>$ownername[$i],
                            'land_id'=>$land_id[$i],
                            'villagename'=>$village_id[$i],
                            'survey_no' => $survey_no[$i],
                            'acre'=>$acre[$i], 'value'=>$value[$i]);
                $i++;
                $this->view->adm->addRecord("ourbank_agriculture",$agri);
            }
            $this->_redirect('/individualmcommonview/index/commonview/id/'.$member_id);
        }
    }

    public function editAction()
    {
        $this->view->title = $this->view->translate("Edit agriculture details");
        $this->view->memberid=$member_id=$this->_getParam('id');
//count number of family members
        $family_model=new Agriculture_Model_agriculture();
        $this->view->land_details=$count_member = $family_model->edit_landtypes();
        $this->view->family_number=$number=count($count_member);
//load form with respective to number of family member
        $addForm = new Agriculture_Form_agriculture($number);
        $this->view->form=$addForm;
        $owner = $this->view->adm->viewRecord("ourbank_master_ownershiptype","id","DESC");
       $this->view->ownertype = $owner;
        $this->view->agriculture=$family_model->getagriculturedetails($member_id);
        

            if ($this->_request->getPost('submit')) {
            $id=$this->_getParam('id');
            $editagri = $family_model->getlog_details($id);
            for ($j = 0 ; $j< count($editagri); $j++) {
                $this->view->adm->addRecord("ourbank_agriculture_log",$editagri[$j]);
            }

            $family_model->deleteagri($id);
            $land_id=$this->_request->getParam('tenant');
            $village_id=$this->_request->getParam('villagename');
            $owner_id=$this->_request->getParam('ownertype');
            $ownername=$this->_request->getParam('ownername');
            $survey_no=$this->_request->getParam('survey');
            $acre=$this->_request->getParam('acre');
            $value=$this->_request->getParam('acrevalue');
            $i = 0;
            foreach($this->_getParam('tenant') as $val) {
                $agri = array('id' => '',
                            'member_id'=>$member_id,
                            'landowner_id'=>$owner_id[$i],
                            'landowner_name'=>$ownername[$i],
                            'land_id'=>$land_id[$i],
                            'villagename'=>$village_id[$i],
                            'survey_no' => $survey_no[$i],
                            'acre'=>$acre[$i], 'value'=>$value[$i]);
                $i++;
                $this->view->adm->addRecord("ourbank_agriculture",$agri);
            }
            $this->_redirect('/individualmcommonview/index/commonview/id/'.$id);
        }


        }
   
}
