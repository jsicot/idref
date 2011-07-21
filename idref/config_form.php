<?php 
/**
 * Config form include
 *
 * Included in the configuration page for the plugin to change settings.
 *
 */
?>
<div class="field">
    <label for="idref_proxy_host">Proxy host</label>
    <?php echo __v()->formText('idref_proxy_host', $idref_proxy_host);?>
    <p class="explanation">If your organization has a pass-through proxy required for Internet access, you must configure the proxy host.  Check with your web host for more 
    information.</p>
</div>
<div class="field">
    <label for="idref_proxy_port">Proxy port</label>
    <?php echo __v()->formText('idref_proxy_port', $idref_proxy_port);?>
    <p class="explanation">And the proxy host.  Check with your web host for more 
    information.</p>
</div>

