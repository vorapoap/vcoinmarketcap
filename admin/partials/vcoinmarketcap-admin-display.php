<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://github.com/vorapoap
 * @since      1.0.0
 *
 * @package    Vcoinmarketcap
 * @subpackage Vcoinmarketcap/admin/partials
 */
?>


<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <form method="post" name="cleanup_options" action="options.php">
        <?php
        //Grab all options
        $options = get_option($this->plugin_name);
        if ($options) {
            $coinmarketcap_api = @esc_url($options['coinmarketcap_api']);
            //$coinmarketcap_page_slug = @esc_attr__($options['coinmarketcap_page_slug']);
            //$coinmarketcap_page_title = @esc_attr__($options['coinmarketcap_page_title']);
            $coinmarketcap_table_columns_json = @esc_textarea($options['coinmarketcap_table_columns_json']);
            $coinmarketcap_pagination_json = @esc_textarea($options['coinmarketcap_pagination_json']);
        }


        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);


        ?>

        <!-- remove some meta and generators from the <head> -->
        <table class="form-table">
            <tbody>
            <tr>
                <th><span><?php _e('CoinmarketCap API URL', $this->plugin_name); ?></span>
                    <legend class="screen-reader-text">
                        <span><?php _e('CoinmarketCap API URL', $this->plugin_name); ?></span></legend>
                </th>
                <td><input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-coinmarketcap-api"
                           name="<?php echo $this->plugin_name; ?>[coinmarketcap_api]"
                           value="<?php if (!empty($coinmarketcap_api))
                               echo($coinmarketcap_api); ?>"/></td>
            </tr>


            <tr>
                <th>
                    <span><?php _e('CoinmarketCap Columns (Json)', $this->plugin_name); ?></span>
                    <legend class="screen-reader-text">
                        <span><?php _e('CoinmarketCap Columns (Json)', $this->plugin_name); ?></span></legend>
                </th>
                <td>
                    <textarea rows="40" class="regular-text"
                              id="<?php echo $this->plugin_name; ?>-coinmarketcap-table-columns-json"
                              name="<?php echo $this->plugin_name; ?>[coinmarketcap_table_columns_json]"><?php if (!empty($coinmarketcap_table_columns_json))
                            echo($coinmarketcap_table_columns_json); ?></textarea>
                </td>
            </tr>


            <tr>
                <th><span><?php _e('CoinmarketCap Pagination Settings (Json)', $this->plugin_name); ?></span>
                    <legend class="screen-reader-text">
                        <span><?php _e('CoinmarketCap Pagination Settings (Json)', $this->plugin_name); ?></span>

                    </legend><br/>
                    <a href="https://codex.wordpress.org/Function_Reference/paginate_links" target="_blank">Parameter
                        Explanation</a> <br/>(Plus per_page - default = 10)
                </th>
                <td><textarea rows="15" class="regular-text"
                              id="<?php echo $this->plugin_name; ?>-coinmarketcap-pagination-json"
                              name="<?php echo $this->plugin_name; ?>[coinmarketcap_pagination_json]"><?php if (!empty($coinmarketcap_pagination_json))
                            echo($coinmarketcap_pagination_json); ?></textarea>
                </td>
            </tr>
            <tr>
                <th colspan="2">        <?php submit_button('Save all changes', 'primary', 'submit', TRUE); ?></th>
            </tr>


            </tbody>
        </table>
    </form>
</div>


<?php
/*
<tr>
                <th><span><?php _e('CoinmarketCap Page Slug', $this->plugin_name); ?></span>
<legend class="screen-reader-text">
    <span><?php _e('CoinmarketCap Page Slug', $this->plugin_name); ?></span></legend>
</th>
<td><input type="text" class="regular-text"
           id="<?php echo $this->plugin_name; ?>-coinmarketcap-page-slig"
           name="<?php echo $this->plugin_name; ?>[coinmarketcap_page_slug]"
           value="<?php if (!empty($coinmarketcap_page_slug))
               echo($coinmarketcap_page_slug); ?>"/></td>
</tr>


<tr>
    <th><span><?php _e('CoinmarketCap Page Title', $this->plugin_name); ?></span>
        <legend class="screen-reader-text">
            <span><?php _e('CoinmarketCap Page Title', $this->plugin_name); ?></span></legend>
    </th>
    <td><input type="text" class="regular-text"
               id="<?php echo $this->plugin_name; ?>-coinmarketcap-page-title"
               name="<?php echo $this->plugin_name; ?>[coinmarketcap_page_title]"
               value="<?php if (!empty($coinmarketcap_page_title))
                   echo($coinmarketcap_page_title); ?>"/></td>
</tr>
*/
?>