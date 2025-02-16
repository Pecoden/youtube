<?php
/**
 * Action on wp_footer
 * Print backlink html
 *
 * @param int|string $template_id
 */
function wpFooterActions($template_id)
{
    if (Nicepage::$override_with_plugin) {
        $data_provider = np_data_provider($template_id);
        $backlink = $data_provider->getPageBacklink();
        echo $backlink;
    }
}