<?php

if (!defined('_PS_VERSION_')){
	exit;
}

class ag_popupbox extends Module
{
	private $_html;
	public function __construct(){
		$this->name = 'ag_popupbox';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'agnius';
		$this->need_instance = 0;
		$this->bootstrap = true;

		parent::__construct();
		
		$this->displayName = $this->l('ag popupbox');
		$this->description = $this->l('Display popup box on exit');
	}

	public function install(){
		if (!parent::install() OR
			!$this->registerHook('displayFooter')
		) {
			return false;
		}
		return true;
	}
	
	public function uninstall(){
		if (!parent::uninstall())
			return false;
		return true;
	}
	
	function hookDisplayFooter($params){
		if ($this->context->customer->newsletter == 1)
			return null;

		global $smarty;
		$this->context->controller->addJS($this->_path.'js/ag_popup.js');
		$this->context->controller->addCSS($this->_path.'css/ag_popup.css');
		$popupbox_data = Configuration::get('popupbox_data');
		$popupbox_data_mobile = Configuration::get('popupbox_data_mobile');
		$smarty->assign(array(
			'popupbox_data' => $popupbox_data,
			'popupbox_data_mobile' => $popupbox_data_mobile,
			'mobile_device' => $this->checkMobileDevice()
		));
		
		return $this->display(__FILE__, '/views/templates/front/popupbox.tpl');		
	}
	
	public function getContent(){
		global $smarty, $cookie;
		if(Tools::isSubmit('submitUpdate')) {
			Configuration::updateValue('popupbox_reopentime', Tools::getValue('popupbox_reopentime'));
			Configuration::updateValue('popupbox_data', Tools::getValue('popupbox_data'),true);
			Configuration::updateValue('popupbox_data_mobile', Tools::getValue('popupbox_data_mobile'),true);
			
			$smarty->assign(array(
				'save_ok' => true
			));
		}
		$this->_html .= $this->_displayForm();
		return $this->_html;
	}
        public function getConfigFieldsValues()
	{
         return array(
               'popupbox_reopentime' => Tools::getValue('popupbox_reopentime',  Configuration::get('popupbox_reopentime')),
			   'popupbox_data' => Tools::getValue('popupbox_data', Configuration::get('popupbox_data')),
			   'popupbox_data_mobile' => Tools::getValue('popupbox_data_mobile', Configuration::get('popupbox_data_mobile'))
                );
	}
        private function _displayForm()
        {
            $fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'textarea',
						'label' => $this->l('Popup box code'),
						'name' => 'popupbox_data',
						'desc' => $this->l(''),'class' => 'rte',
						'autoload_rte' => true
					),
					array(
						'type' => 'textarea',
						'label' => $this->l('Popup box code for mobile'),
						'name' => 'popupbox_data_mobile',
						'desc' => $this->l(''),'class' => 'rte',
						'autoload_rte' => true
					)
				),
				'submit' => array(
					'title' => $this->l('Save')
				)
			),
		);
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitUpdate';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		$this->_html .=  $helper->generateForm(array($fields_form));
        }
	public function checkMobileDevice(){
		if (preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipaq|ipod|j2me|java|midp|mini|mmp|mobi\s|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|zte)/i', $_SERVER['HTTP_USER_AGENT'], $out))
			return true;
		else
			return false;
	}
}
