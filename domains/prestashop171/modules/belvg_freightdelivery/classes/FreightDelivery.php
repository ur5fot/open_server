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

class FreightDelivery extends ObjectModel
{
    public $id;

    public $id_belvg_freightdelivery;

    public $id_order;

    public $details;

    public $date_upd;

    public static $definition = array(
        'table' => 'belvg_freightdelivery',
        'primary' => 'id_belvg_freightdelivery',
        'fields' => array(
            'id_order' =>       array('type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'copy_post' => FALSE),
            'details'  =>       array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'copy_post' => FALSE),
            'date_upd' =>       array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
        ),
    );

    public static function loadByOrderId($id_order)
    {
        $collection = new Collection('FreightDelivery');
        $collection->where('id_order', '=', (int)$id_order);
        if ($collection->getFirst()) {
            return $collection->getFirst();
        } else {
            return new self();
        }
    }
}