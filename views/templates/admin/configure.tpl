{*
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
*}

<div class="panel">
	<div class="row moduleconfig-header">
		<div class="col-xs-5 text-right">
			<img src="{$module_dir|escape:'html':'UTF-8'}views/img/logo.jpg" />
		</div>
		<div class="col-xs-7 text-left">
			<h2>{l s='Export orders feed' mod='exportorders28a'}</h2>
			<h4>{l s='Export orders feed to use on external websites' mod='exportorders28a'}</h4>
		</div>
	</div>

	<hr />

	<div class="moduleconfig-content">
		<div class="row">
			<div class="col-xs-12">
				<p>
					<h4>{l s='Thanks for purchasing this module,hope you like it and help you boost your sales' mod='exportorders28a'}</h4>
					<ul class="ul-spaced">
						<li><strong>{l s='for any help or support not histate to contact me!' mod='exportorders28a'}</strong></li>
						
					</ul>
				</p>

				<br />

				<p class="text-center">
					<strong>
						<a href="#" target="_blank" title="Lorem ipsum dolor">
							{l s='Created with love by' mod='exportorders28a' } Taoufiq Ait Ali
						</a>
					</strong>
				</p>
			</div>
		</div>
	</div>
</div>

<div class="panel" id="fieldset_0">
												
						<div class="panel-heading">
														<i class="icon-cogs"></i>							export All orders
						</div>
					
								
	<div class="form-wrapper">
											
			<a href="{$linktomodule|escape:'htmlall':'UTF-8'}&exportordersbystat=all" class="btn btn-primary"><i class="icon icon-shopping-cart"></i> {l s='Export All orders' mod='exportorders28a' }</a>	&nbsp;		
			<a href="{$linktomodule|escape:'htmlall':'UTF-8'}&exportordersbystat=accepted" class="btn btn-success"><i class="icon icon-ok-sign"></i> {l s='Export Accepted orders' mod='exportorders28a' }</a>	&nbsp;		
																	
	</div>	
</div>
