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

    {*<select class="js-search-city" style="width: 100%;">
    </select>

    <script id="tpl-new-post-list-city" type="text/template">

        <div class="wrapper">
            <h3><%- descriptionRu %></h3>
            <p>
                <% if ( regionsDescriptionRu !== '') { %>
                <%- regionsDescriptionRu %>,
                <% }; %>
                <%- areaDescriptionRu %>
            </p>

            <% if ( +warehouse) { %>
            <p>
                есть отделение новой почты
            </p>
            <% }; %>


        </div>

    </script>
*}
    <div class="new-post-wrapp ">
        <select class="js-new-post hide" style="width: 100%;">
            {*<option value="0" selected="selected">Выберите отделение</option>*}
        </select>

        <script id="tpl-new-post-dis" type="text/template">

            <h1><%- CityDescriptionRu%></h1>
            <p><%- DescriptionRu%></p>

            <ul>
                <li>Friday -<%- Schedule.Friday%></li>
                <li>Friday - <%- Schedule.Friday%></li>
                <li>Saturday - <%- Schedule.Saturday%></li>
                <li>Sunday - <%- Schedule.Sunday%></li>
                <li>Thursday -<%- Schedule.Thursday%></li>
            </ul>

        </script>

        <div class="result">

        </div>


    </div>
</div>
