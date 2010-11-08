{include file="modules_admin_menu.tpl"}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=windowlist.gif set=icons/large __alt="View plugins"}</div>
    {if $systemplugins}
        <h2>{gt text="System plugins"}</h2>
    {else}
        <h2>{gt text="Plugins list"}</h2>
    {/if}

    <table class="z-datatable">
        <thead>
            <tr>
                {if !$systemplugins}
                    <th>
                        <form action="{modurl modname="Modules" type="admin" func="viewPlugins"}" method="post" enctype="application/x-www-form-urlencoded">
                            <div>
                                <input type="hidden" name="sort" value="{$sort|safetext}" />
                                <input type="hidden" name="state" value="{$state|safetext}" />
                                <input type="hidden" name="systemplugins" value="{$systemplugins|safetext}" />
                                <div>
                                    <label for="bymodule">
                                        <a href="{modurl modname="Modules" type="admin" func="viewPlugins" sort="module" state=$state bymodule=$module systemplugins=$systemplugins}">{gt text="Module"}</a>
                                    </label><br />
                                    {selector_module name="bymodule" selectedValue=$module allValue="0" __allText="All" submit=true}
                                </div>
                            </div>
                        </form>
                    </th>
                {/if}
                <th><a href="{modurl modname="Modules" type="admin" func="viewPlugins" sort="name" state=$state bymodule=$module systemplugins=$systemplugins}">{gt text="Internal Plugin name"}</a></th>
                <th>{gt text="Plugin display name"}</th>
                <th>{gt text="Description"}</th>
                <th>{gt text="Version"}</th>
                <th class="z-nowrap">
                    <form action="{modurl modname="Modules" type="admin" func="viewPlugins"}" method="post" enctype="application/x-www-form-urlencoded">
                        <div>
                            <input type="hidden" name="sort" value="{$sort|safetext}" />
                            <input type="hidden" name="bymodule" value="{$module|safetext}" />
                            <input type="hidden" name="systemplugins" value="{$systemplugins|safetext}" />
                            <div>
                                <label for="modules_state">{gt text="State"}</label><br />
                                <select id="modules_state" name="state" onchange="submit()">
                                    <option value="-1">{gt text="All"}</option>
                                    <option value="{const name="PluginUtil::NOTINSTALLED"}"{if $state eq 2} selected="selected"{/if}>{gt text="Not installed"}</option>
                                    <option value="{const name="PluginUtil::DISABLED}"{if $state eq 0} selected="selected"{/if}>{gt text="Inactive"}</option>
                                    <option value="{const name="PluginUtil::ENABLED}"{if $state eq 1} selected="selected"{/if}>{gt text="Active"}</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </th>
                <th class="z-right">{gt text="Actions"}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$plugins item="plugin"}
            <tr class="{cycle values="z-odd,z-even"}">
                {if !$systemplugins}
                    <td>
                        {$plugin.instance->getModuleName()|safetext}
                    </td>
                {/if}
                <td>{if $plugin.instance instanceof Zikula_Plugin_Configurable and $plugin.instance->isInstalled() and $_type eq 'system'}
                        <a href="{modurl modname="Modules" type="adminplugin" func="dispatch" _plugin=$plugin.instance->getPluginName() _action="configure"}">{$plugin.instance->getPluginName()|safetext}</a>
                    {elseif $plugin.instance instanceof Zikula_Plugin_Configurable and $plugin.instance->isInstalled() and $_type eq 'module'}
                        <a href="{modurl modname="Modules" type="adminplugin" func="dispatch" _module=$plugin.instance->getModuleName() _plugin=$plugin.instance->getPluginName() _action="configure"}">{$plugin.instance->getPluginName()|safetext}</a>
                    {else}
                        {$plugin.instance->getPluginName()|safetext}
                    {/if}
               </td>
                <td>{$plugin.instance->getMetaDisplayName()|safetext}</td>
                <td>{$plugin.instance->getMetaDescription()|safetext}</td>
                <td>{$plugin.version|safetext}</td>
                <th class="z-nowrap">
                    {img src=$plugin.statusimage modname=core set=icons/extrasmall alt=$plugin.status title=$plugin.status}&nbsp;{$plugin.status|safetext}
                    {if isset($plugin.newversion)}
                    <br />({$plugin.newversion|safetext})
                    {/if}
                </td>
                <td class="z-right z-nowrap">
                    {strip}
                    {foreach from=$plugin.actions item="action"}
                    <a href="{$action.url|safetext}">{img modname=core src=$action.image set=icons/extrasmall title=$action.title alt=$action.title}</a>&nbsp;
                    {/foreach}
                    {/strip}
                </td>
            </tr>
            {foreachelse}
            <tr class="z-datatableempty"><td colspan="7">{gt text="No items found."}</td></tr>
            {/foreach}
        </tbody>
    </table>
</div>
