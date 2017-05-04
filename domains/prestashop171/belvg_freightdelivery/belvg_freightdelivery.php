<?php
/*
* 2007-2014 PrestaShop
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
*         DISCLAIMER   *
* *************************************** */
/* Do not edit or add to this file if you wish to upgrade Prestashop to newer
* versions in the future.
* ****************************************************
* @category   Belvg
* @package    Belvg_FreightCompanyDelivery
* @author    Alexander Simonchik <support@belvg.com>
* @site    http://module-presta.com
* @copyright  Copyright (c) 2010 - 2014 BelVG LLC. (http://www.belvg.com)
* @license    http://store.belvg.com/BelVG-LICENSE-COMMUNITY.txt
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'belvg_freightdelivery/includer.php';

class belvg_freightdelivery extends CarrierModule
{
    const PREFIX = 'belvg_fcd_';

    public $id_carrier;

    protected $_hooks = array(
        'header',
        'actionCarrierUpdate',
        'displayOrderConfirmation',
        'displayAdminOrder',
        'displayBeforeCarrier',
    );

    protected $_carriers = array(
        'Freight Company' => 'fcd',
    );

    public function __construct()
    {
        $this->name = 'belvg_freightdelivery';
        $this->tab = 'shipping_logistics';
        $this->version = '1.6.2';
        $this->author = 'BelVG';
        $this->bootstrap = TRUE;
        $this->module_key = '';

        parent::__construct();

        $this->displayName = $this->l('Delivery by freight company');
        $this->description = $this->l('If your customer choose “Delivery by freight company”, you get a text field for entering information to the freight company');
    }

    public function getTemplate($area, $file)
    {
        return 'views/templates/' . $area . '/' . $file;
    }

    public function install()
    {
        if (parent::install()) {
            foreach ($this->_hooks as $hook) {
                if (!$this->registerHook($hook)) {
                    return FALSE;
                }
            }

//            if (!$this->installDB()) {
//                return FALSE;
//            }

            if (!$this->createCarriers()) {
                return FALSE;
            }

            return TRUE;
        }

        return FALSE;
    }

    protected function uninstallDB()
    {
        $sql = array();

        $sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'belvg_freightdelivery`';

        foreach ($sql as $_sql) {
            if (!Db::getInstance()->Execute($_sql)) {
                return FALSE;
            }
        }

        return TRUE;
    }

    protected function installDB()
    {
        $sql = array();

        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'belvg_freightdelivery` (
            `id_belvg_freightdelivery` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_order` INT( 11 ) UNSIGNED,
            `details` TEXT,
            `date_upd` DATETIME NOT NULL,
            PRIMARY KEY (`id_belvg_freightdelivery`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

        foreach ($sql as $_sql) {
            if (!Db::getInstance()->Execute($_sql)) {
                return FALSE;
            }
        }

        return TRUE;
    }

    protected function createCarriers()
    {
        foreach ($this->_carriers as $key => $value) {
            //Create own carrier
            $carrier = new Carrier();
            $carrier->name = $key;
            $carrier->active = TRUE;
            $carrier->deleted = 0;
            $carrier->shipping_handling = FALSE;
            $carrier->range_behavior = 0;
            $carrier->delay[Configuration::get('PS_LANG_DEFAULT')] = 'Depends on the freight company [1-2 days]';
            $carrier->shipping_external = TRUE;
            $carrier->is_module = TRUE;
            $carrier->external_module_name = $this->name;
            $carrier->need_range = TRUE;

            if ($carrier->add()) {
                Configuration::updateValue(self::PREFIX . $value, $carrier->id);
                Configuration::updateValue(self::PREFIX . $value . '_reference', $carrier->id);
                $groups = Group::getGroups(true);
                foreach ($groups as $group) {
                    Db::getInstance()->insert('carrier_group', array(
                        'id_carrier' => (int) $carrier->id,
                        'id_group' => (int) $group['id_group']
                    ));
                }

                $rangePrice = new RangePrice();
                $rangePrice->id_carrier = $carrier->id;
                $rangePrice->delimiter1 = '0';
                $rangePrice->delimiter2 = '1000000';
                $rangePrice->add();

                $rangeWeight = new RangeWeight();
                //$rangeWeight->force_id = true;
                $rangeWeight->id_carrier = $carrier->id;
                $rangeWeight->delimiter1 = '0';
                $rangeWeight->delimiter2 = '1000000';
                $rangeWeight->add();

                $zones = Zone::getZones(true);
                foreach ($zones as $z) {
                    Db::getInstance()->insert('carrier_zone',
                        array('id_carrier' => (int) $carrier->id, 'id_zone' => (int) $z['id_zone']));
                    Db::getInstance()->insert('delivery',
                        array('id_carrier' => $carrier->id, 'id_range_price' => (int) $rangePrice->id, 'id_range_weight' => NULL, 'id_zone' => (int) $z['id_zone'], 'price' => '25'));
                    Db::getInstance()->insert('delivery',
                        array('id_carrier' => $carrier->id, 'id_range_price' => NULL, 'id_range_weight' => (int) $rangeWeight->id, 'id_zone' => (int) $z['id_zone'], 'price' => '25'));
                }

                copy(dirname(__FILE__) . '/views/img/carrier.jpg', _PS_SHIP_IMG_DIR_ . '/' . (int) $carrier->id . '.jpg');

                
            }
        }
        return TRUE;
    }

    protected function deleteCarriers()
    {
        foreach ($this->_carriers as $value) {
            $tmp_carrier_id = Configuration::get(self::PREFIX . $value);
            $carrier = new Carrier($tmp_carrier_id);
            $carrier->delete();
        }

        return TRUE;
    }

    public function uninstall()
    {
        if (parent::uninstall()) {
            foreach ($this->_hooks as $hook) {
                if (!$this->unregisterHook($hook)) {
                    return FALSE;
                }
            }

            /*if (!$this->uninstallDB()) {
                return FALSE;
            }*/

            if (!$this->deleteCarriers()) {
                return FALSE;
            }

            return TRUE;
        }

        return FALSE;
    }

    public function getOrderShippingCost($params, $shipping_cost)
    {
		//reason of using $shipping_cost => CarrierModuleCore::getOrderShippingCost()
		//$params - you can use this parameter for customizing delivery price calculation
        $carrierObj = new Carrier(Configuration::get(self::PREFIX . 'fcd'));
        $delivery_price = Carrier::getDeliveryPriceByRanges($carrierObj->getRangeTable(), Configuration::get(self::PREFIX . 'fcd'));
        $max_delivery_price = 0;
        foreach ($delivery_price as $d_price) {
            if ($d_price['price'] > $max_delivery_price) {
                $max_delivery_price = $d_price['price'];
            }
        }

        return $max_delivery_price;
    }

    public function getOrderShippingCostExternal($params)
    {
        return $this->getOrderShippingCost($params, 0);
    }

    public function hookActionCarrierUpdate($params)
    {
        if ($params['carrier']->id_reference == Configuration::get(self::PREFIX . 'fcd_reference')) {
            Configuration::updateValue(self::PREFIX . 'fcd', $params['carrier']->id);
        }
    }

    public function hookHeader($params)
    {
        if (in_array(Context::getContext()->controller->php_self, array('order-opc', 'order'))) {
            //$this->context->controller->addCSS(($this->_path) . 'css/belvg_freightdelivery.css', 'all');
            $this->context->controller->addJS(array( $this->_path . 'views/js/belvg_freightdelivery.js', ));
//            $this->context->controller->addJS(array( $this->_path . 'views/js/select2.js', ));
            $this->smarty->assign(['freightdelivery_carrier_id' => Configuration::get(self::PREFIX . 'fcd')]);


            return $this->display(__FILE__, 'header.tpl');
        }
    }

    public function hookDisplayOrderConfirmation($params) {
        $objOrder = $params['objOrder'];
        /*Multishipping support*/
        $ordersCollectionResults = Order::getByReference($objOrder->reference)->getResults();
        foreach ($ordersCollectionResults as $itemOrderObj) {
            if (($itemOrderObj->id_carrier == Configuration::get(self::PREFIX . 'fcd')) && $itemOrderObj->id) {
                $freightDeliveryObj = FreightDelivery::loadByOrderId($itemOrderObj->id);
                //prevent multiple insert after reload success-page
                if (!$freightDeliveryObj->id) {
                    $freightDeliveryObj->id_order = (int)$itemOrderObj->id;
                    $freightDeliveryObj->details = pSQL($this->context->cookie->fcd_details);
                    if ($freightDeliveryObj->add()) {
                        unset($this->context->cookie->fcd_details);
                    }
                }
            }
        }
    }

    public function hookDisplayAdminOrder($params)
    {
        $freightDeliveryObj = FreightDelivery::loadByOrderId($params['id_order']);
        $this->context->smarty->assign('belvg_freightdelivery_obj', $freightDeliveryObj);
        return $this->display(__file__, $this->getTemplate('admin', 'productAdminTab.tpl'));
    }

    public function hookDisplayBeforeCarrier($params)
    {
        
        $this->context->smarty->assign(array(
            'freight_company_carrier_details' => $this->context->cookie->fcd_details,
            'freight_company_carrier_id' => Configuration::get('belvg_fcd_fcd'),
            'rposoft_nova_poshta_locations_delivery' => Db::getInstance()->executeS(
                    'SELECT deliveryRef, descriptionRu FROM `' . _DB_PREFIX_ . 'rposoft_nova_poshta_locations_delivery`'
            )
        ));

        return $this->display(__file__, 'displayBeforeCarrier.tpl');
    }
}