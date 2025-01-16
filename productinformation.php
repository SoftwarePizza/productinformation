<?php
/**
* 2007-2021 PrestaShop
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
*  @copyright 2007-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once('ProductinformationClass.php');

class Productinformation extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'productinformation';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Software Pizza';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Product information');
        $this->description = $this->l('Displays additional information about the product');

        $this->ps_versions_compliancy = array('min' => '8.1', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install() {
      include(dirname(__FILE__).'/sql/install.php');

      // Define the directory path
      $dir = _PS_UPLOAD_DIR_ . 'productinformation/';

      // Check if the directory exists, and if not, create it
      if (!file_exists($dir)) {
        if (!mkdir($dir, 0777, true)) {
            // If directory creation fails, return false with an error message
            $this->_errors[] = $this->trans('Failed to create directory %s', [$dir], 'Modules.MyModule.Admin');
            return false;
        }
      }

      return parent::install() &&
          $this->registerHook('displayProductInformation') &&
          $this->registerHook('displayProductActions') &&
          $this->registerHook('displayProductAdditionalInfo');
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminProductinformation'));
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookDisplayProductInformation()
    {
        $request = 'SELECT * FROM `' . _DB_PREFIX_ . 'product_information`';
        $result = Db::getInstance()->executeS($request);
        $this->context->smarty->assign([
          'productinformation' => $result ]);
        return $this->display(__FILE__, 'views/templates/hook/productinformation.tpl'
      );
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookDisplayProductActions()
    {
        $request = 'SELECT * FROM `' . _DB_PREFIX_ . 'product_information`';
        $result = Db::getInstance()->executeS($request);
        $this->context->smarty->assign([
          'productinformation' => $result ]);
        return $this->display(__FILE__, 'views/templates/hook/productinformation.tpl'
      );
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookDisplayProductAdditionalInfo()
    {
        $request = 'SELECT * FROM `' . _DB_PREFIX_ . 'product_information`';
        $result = Db::getInstance()->executeS($request);
        $this->context->smarty->assign([
          'productinformation' => $result ]);
        return $this->display(__FILE__, 'views/templates/hook/productinformation.tpl'
      );
    }

}
