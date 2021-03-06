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

class Ledger_Form_Ledger extends Zend_Form
{
    public function init()
    {
    }

    public function __construct($path) 
{
        parent::__construct($path);
        $formfield = new App_Form_Field ();

        $accountHeader = $formfield->field('Text','accountHeader','','','txt_put','',true,'','','','','','','');

        $glcodeDescription = new Zend_Form_Element_Textarea
        ('glcodeDescription', array('rows' => 3,'cols' => 25,));
        $glcodeDescription->setAttrib('id', 'glcodeDescription');
	$glcodeDescription->setRequired(true)
		->addValidators(array(array('NotEmpty')));

        $product = $formfield->field('Select','product','','','txt_put','',true,'','','','','','','');
        $product->setAttrib('onchange','getGlcode("'.$path.'",this.value)');

        $offerproduct = $formfield->field('Select','offerproduct','','','txt_put','','','','','','','','','');

        $officelevel = $formfield->field('Select','officelevel','','','','','','','','','','',0,0);
        $officelevel->setRegisterInArrayValidator(false);
        $officelevel->setAttrib('onchange', 'getoffice("'.$path.'",this.value)');

        $office = $formfield->field('Select','office','','','','','','','','','','',0,0);
        $office->setRegisterInArrayValidator(false);

        $subheader = $formfield->field('Text','subheader','','','txt_put','','','','','','','','','');

	$glsubaccountdescription = new Zend_Form_Element_Textarea
        ('glsubaccountdescription', array('rows' => 3,'cols' => 25,));
        $glsubaccountdescription->setAttrib('id', 'glsubaccountdescription');

        $submit = $formfield->field('Submit','Save','','','','holiday1','','','','','','','','');
        $this->addElements( array ($submit,$accountHeader,$glcodeDescription,$subheader,$glsubaccountdescription,$product,$offerproduct,$officelevel,$office));

        $glcodeHiddenId = new Zend_Form_Element_Hidden('hidden_glcodeid');
        $glsubcodeHiddenId = new Zend_Form_Element_Hidden('hidden_glsubcodeid');
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setAttrib('class', 'holiday');
        $this->addElements(array($submit,$glcodeHiddenId,$glsubcodeHiddenId));
    }
}
