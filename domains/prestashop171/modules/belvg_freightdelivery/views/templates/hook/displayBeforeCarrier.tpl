{*
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
*}

<div id="freight_company_details" class="unvisible">
    <!--<textarea class="form-control" rows="3" name="freight_company_details" placeholder="{l s='Please, enter information to the freight company' mod='belvg_freightdelivery'}">{if isset($freight_company_carrier_details) && $freight_company_carrier_details}{$freight_company_carrier_details}{/if}</textarea>
    -->
    <select id="deliveryCity">
        {foreach from=$rposoft_nova_poshta_locations_delivery item=item}
            <option value="{$item['deliveryRef']}">{$item['descriptionRu']}</option>
        {/foreach}
    </select>
    {*print_r('<pre>')}
    {print_r($rposoft_nova_poshta_locations_delivery)}
    {print_r('</pre>')*}
    <select id="warehouses">
        <option value="volvo">Volvo</option>
    </select>
    <script type="text/javascript">
        {literal}

            

        {/literal}
    </script>

    <style>
        {literal}
            #freight_company_details{ margin-bottom: 5px }
        {/literal}
    </style>
</div>