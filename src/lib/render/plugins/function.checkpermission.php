<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv2.1 (or at your option, any later version).
 * @package Render
 * @subpackage Template_Plugins
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Check permission.
 * 
 * Example:
 * {checkpermission comp="News::" inst=".*" level="ACCESS_ADMIN" assign="auth"}
 *
 * True/false will be returned.
 *
 * This file is a plugin for Renderer, the Zikula implementation of Smarty.
 * 
 * @param array  $params  All attributes passed to this function from the template.
 * @param Smarty &$smarty Reference to the Smarty object.
 * 
 * @return boolean Authorized?
 */
function smarty_function_checkpermission($params, &$smarty)
{
    $assign = isset($params['assign']) ? $params['assign'] : null;
    $comp   = isset($params['comp'])   ? $params['comp']   : null;
    $inst   = isset($params['inst'])   ? $params['inst']   : null;
    $level  = isset($params['level'])  ? $params['level']  : null;

    if (!$comp) {
        $smarty->trigger_error(__f('Error! in %1$s: the %2$s parameter must be specified.', array('smarty_function_secauthaction', 'comp')));
        return false;
    }

    if (!$inst) {
        $smarty->trigger_error(__f('Error! in %1$s: the %2$s parameter must be specified.', array('smarty_function_secauthaction', 'inst')));
        return false;
    }

    if (!$level) {
        $smarty->trigger_error(__f('Error! in %1$s: the %2$s parameter must be specified.', array('smarty_function_secauthaction', 'level')));
        return false;
    }

    $result = SecurityUtil::checkPermission($comp, $inst, constant($level));

    if ($assign) {
        $smarty->assign($assign, $result);
    } else {
        return $result;
    }
}
