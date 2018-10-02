<?php
/**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Exportorders28a extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'exportorders28a';
        $this->tab = 'export';
        $this->version = '1.0.0';
        $this->author = 'Taoufiq Ait Ali';
        $this->need_instance = 0;
        $this->module_key = '3d51bc3f1e7b5c54829afdee115f42b0';

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('export orders feed');
        $this->description = $this->l('export orders feed to use on external websites');
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('actionOrderDetail') &&
            $this->registerHook('displayAdminOrderContentOrder') &&
            $this->registerHook('displayBackOfficeFooter') &&
            $this->registerHook('displayBackOfficeHeader');
    }

    public function uninstall()
    {

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $output="";
        if (((bool)Tools::isSubmit('submitExportorderbydate')) == true) {
            $output .= $this->exportordersbydate();
        }
        
        if (((bool)Tools::getValue('exportorderbyid')) == true ||
            ((bool)Tools::isSubmit('submitExportorderbyid')) == true) {
            $output .= $this->exportorderbyid();
        }
        if (((bool)Tools::getValue('exportordersbystat')) == true) {
            $output .= $this->exportordersbystat();
        }

        $linktomodule=$this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name
            .'&tab_module='.$this->tab
            .'&module_name='.$this->name
            .'&token='.Tools::getAdminTokenLite('AdminModules');
        $this->context->smarty->assign(array(
            
            'module_dir' => $this->_path,
            'linktomodule' => $linktomodule,
            
        ));
        
        $output .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
        return $output.$this->renderFormbystate().$this->renderFormbyid().$this->renderFormbydate();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderFormbyid()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitExportorderbyid';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' =>null,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array(array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Export order by id'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'required'=>true,
                        'prefix' => '<i class="icon icon-shopping-cart"></i>',
                        'desc' => $this->l('Export order by id'),
                        'name' => 'exportorderbyid',
                        'label' => $this->l('order id'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('export by id'),
                ),
            ),
        )));
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderFormbystate()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitExportorderbystat';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' =>null,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        $options = array();
        foreach (OrderState::getOrderStates((int)Context::getContext()->language->id) as $state) {
            $options[] = array(
                "id_option" => (int)$state['id_order_state'],
                "name" => $state['name']
            );
        }
        
        return $helper->generateForm(array(array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Export orders by state'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    
                    array(
                        'type' => 'select',
                        'required'=>true,
                        'prefix' => '<i class="icon icon-shopping-cart"></i>',
                        'desc' => $this->l('Export orders by state'),
                        'name' => 'exportordersbystat',
                        'label' => $this->l('select state to export'),
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                            )
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('export by state'),
                ),
            ),
        )));
    }



    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderFormbydate()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitExportorderbydate';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' =>null,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array(array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Export orders by date interval'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'date',
                        'required'=>true,
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('select interval date'),
                        'name' => 'date_from',
                        'label' => $this->l('From'),
                    ),
                    array(
                        'type' => 'date',
                        'required'=>true,
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('select interval date'),
                        'name' => 'date_to',
                        'label' => $this->l('To'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('export by date'),
                ),
            ),
        )));
    }
    
    protected function getorderformat($id_order)
    {
        $order=new Order($id_order);
        
        if (!Validate::isLoadedObject($order)) {
            return  'The order id '.$id_order.' cannot be found within your database.'."\n";
        }
        
        $customer = new Customer($order->id_customer);
        $products = $order->getProducts();
        $addresses=$customer->getAddresses($this->context->language->id);
        $output="";
        foreach ($addresses as $address) {
            if ($address["id_address"] == $order->id_address_delivery) {
                $output .= $address["firstname"]." ".$address["lastname"]." \n";
                $output .= $address["address1"]." \n";
                $output .= $address["postcode"]." ";
                $output .= $address["city"]." ";
                if (!empty($address["state"])) {
                    $output .= $address["state"]." ";
                }
                $output .= " \n";
                $output .= $address["country"]."\n";
                break;
            }
        }
        foreach ($products as $product) {
            $output .= $product["product_quantity"]." x ".$product["product_name"] ." \n";
        }
        return $output;
    }


    /**
     * export order by id.
     */
    protected function exportorderbyid()
    {
        $id_order=Tools::getValue('exportorderbyid');
        $output =$this->getorderformat($id_order);
        $link='http'.(Configuration::get('PS_SSL_ENABLED') &&
            Configuration::get('PS_SSL_ENABLED_EVERYWHERE') ? 's' : '').'://'.Tools::getShopDomain(false, true).
        __PS_BASE_URI__.'exportorders28a.txt';

        file_put_contents(Tools::normalizeDirectory(_PS_ROOT_DIR_).'exportorders28a.txt', $output);
        return $this->displayConfirmation($this->l('The order have been exported.you can download file here').
            "<h2><a href='$link' target='blank' >$link</a></h2>");
    }

    /**
     * export orders by stat.
     */
    protected function exportordersbystat()
    {
        if (Tools::getValue('exportordersbystat')=="all") {
            $orders=Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'orders`');
            if (!$orders) {
                return  $this->displayError("orders not exist");
            }
        } elseif (Tools::getValue('exportordersbystat')=="accepted") {
            $orders=Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'orders` od 
                WHERE od.`current_state` = 2');
            if (!$orders) {
                return  $this->displayError("accepted orders not exist");
            }
        } elseif (Tools::getValue('exportordersbystat')>0) {
            $orders=Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'orders` od 
                WHERE od.`current_state` = '.(int)Tools::getValue('exportordersbystat'));
            if (!$orders) {
                return  $this->displayError("orders not exist");
            }
        } else {
            return  $this->displayError("orders not exist");
        }
        $output ="";

        foreach ($orders as $order) {
            $id_order=$order['id_order'];
            $output .= "============= Order id #$id_order =============== \n";
            $output .= $this->getorderformat($id_order);
        }
        
        $link='http'.(Configuration::get('PS_SSL_ENABLED') &&
            Configuration::get('PS_SSL_ENABLED_EVERYWHERE') ? 's' : '').'://'.Tools::getShopDomain(false, true).
            __PS_BASE_URI__.'exportorders28a.txt';

        file_put_contents(Tools::normalizeDirectory(_PS_ROOT_DIR_).'exportorders28a.txt', $output);
        return $this->displayConfirmation($this->l('Orders have been exported.you can download file here').
            "<h2><a href='$link' target='blank' >$link</a></h2>");
    }

    /**
     * export orders by date.
     */
    protected function exportordersbydate()
    {
        if (Validate::isDate(Tools::getValue('date_from')) && Validate::isDate(Tools::getValue('date_to'))) {
            $query="SELECT * FROM `"._DB_PREFIX_."orders` od 
            WHERE DATE(od.`date_add`)>= DATE('".pSQL(Tools::getValue('date_from'))."') 
            and DATE(od.`date_add`)<=DATE('".pSQL(Tools::getValue('date_to'))."')";
            $orders=Db::getInstance()->ExecuteS($query);
            if (!$orders) {
                return  $this->displayError("orders not exist");
            }
        } else {
            return  $this->displayError("please select valid interval dates,orders not exist");
        }
        $output ="";

        foreach ($orders as $order) {
            $id_order=$order['id_order'];
            $output .= "============= Order id #$id_order =============== \n";
            $output .= $this->getorderformat($id_order);
        }
        
        $link='http'.(Configuration::get('PS_SSL_ENABLED') &&
            Configuration::get('PS_SSL_ENABLED_EVERYWHERE') ? 's' : '').'://'.Tools::getShopDomain(false, true).
            __PS_BASE_URI__.'exportorders28a.txt';

        file_put_contents(Tools::normalizeDirectory(_PS_ROOT_DIR_).'exportorders28a.txt', $output);
        return $this->displayConfirmation($this->l('Orders have been exported.you can download file here').
            "<h2><a href='$link' target='blank' >$link</a></h2>");
    }



    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookDisplayAdminOrderContentOrder()
    {
        
        /* Place your code here. */
        $linktomodule=$this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name
            .'&tab_module='.$this->tab
            .'&module_name='.$this->name
            .'&exportorderbyid='.Tools::getValue('id_order', 0)
            .'&token='.Tools::getAdminTokenLite('AdminModules');
        //    $this->smarty->assign('linktomodule', $linktomodule);
        $this->context->smarty->assign(array(
            
            'this_path' => $this->_path,
            'linktomodule' => $linktomodule,
            
        ));
        return $this->context->smarty->fetch($this->local_path.
            'views/templates/admin/displayadminordercontentorder.tpl');
    }
}
