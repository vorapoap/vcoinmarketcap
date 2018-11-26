<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://github.com/vorapoap
 * @since      1.0.0
 *
 * @package    Vcoinmarketcap
 * @subpackage Vcoinmarketcap/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Vcoinmarketcap
 * @subpackage Vcoinmarketcap/public
 * @author     Vorapoap Lohwongwatana <vorapoap@hotmail.com>
 */
class Vcoinmarketcap_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Vcoinmarketcap_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Vcoinmarketcap_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name.'_stacktable', plugin_dir_url(__FILE__) . 'lib/stacktable.js/stacktable.css', array(), $this->version, 'all');

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/vcoinmarketcap-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Vcoinmarketcap_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Vcoinmarketcap_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name.'_stacktable', plugin_dir_url(__FILE__) . 'lib/stacktable.js/stacktable.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/vcoinmarketcap-public.js', array($this->plugin_name.'_stacktable'), $this->version, false);

    }

    public function add_plugin_public_init() {
        $options = get_option($this->plugin_name);
        if ($options) {
            $coinmarketcap_page_slug = $options['coinmarketcap_page_slug'];
            $coinmarketcap_page_title = $options['coinmarketcap_page_title'];

        }
        add_shortcode("coinmarketcap", array($this, 'display_plugin_coinmarketcap_page'));

        /*
        echo $coinmarketcap_page_slug;
	    echo $coinmarketcap_page_title;
	    exit;
        add_menu_page($coinmarketcap_page_title, $coinmarketcap_page_title, 'guest', $coinmarketcap_page_slug, array($this, 'display_plugin_coinmarketcap_page'));
        //remove_menu_page($coinmarketcap_page_slug);*/
    }

    public function get_coinmarketcap_data() {
        if (@!$_GET['clearcache']) {
            $data = wp_cache_get('coinmarketcap_data', $this->plugin_name);
            if ($data)
                return $data;
        }
        if (!class_exists('WP_Http'))
            include_once(ABSPATH . WPINC . '/class-http.php');

        $options = get_option($this->plugin_name);
        $request = new WP_Http;
        $result = $request->request($options['coinmarketcap_api']);
        $data = json_decode($result['body'], TRUE);
        wp_cache_set('coinmarketcap_data', $data, $this->plugin_name, 10);

        return $data;
    }

    public function html_table($headerColumns = array(), $data = array(), $attr = array()) {
        $headerHtml = array();
        $torep = $topat = array();
        foreach ($headerColumns as $key => $header) {
            if ($header['visible']) {
                if (@!$header['label'])
                    $header['label'] = $key;
                $sorted = '';
                if (@$header['sortable']) {
                    $order = @$_GET['order'];
                    switch ($order) {
                        case 'asc':
                            $order = 'desc';
                            break;
                        case 'desc':
                            $order = 'asc';
                            break;
                        default:
                            $order = 'asc';
                    }
                    if ($key == @$_GET['sort']) {
                        $indicator = '<span class="sorting-indicator"></span>';
                        $sorted = ' sorted ' . $order;
                    } else {
                        $indicator = '';
                    }
                    $header['label'] = '<a href="' . get_permalink() . '?sort=' . $key . '&order=' . $order . '">' . $header['label'] . $indicator . '</a>';
                }
                $headerHtml[] = "<th class='vtable-th-cell{$sorted}'>" . $header['label'] . "</th>";
            }
        }
        $rows = array();
        foreach ($data as $row) {
            $cells = array();
            $topat = [];
            $torep = [];

            foreach ($headerColumns as $key => $header) {
                $topat[] = '{' . $key . '}';
                $torep[] = $row[$key];
            }

            foreach ($headerColumns as $key => $header) {
                if ($header['visible']) {
                    $cell = @$row[$key];
                    if (@is_array($header['numformat'])) {
                        $numformat = array_merge([
                            'decimals' => 4, 'dec_point' => '.', 'thousands_sep' => ','
                        ], $header['numformat']);
                        $cell = number_format(floatval($cell), $numformat['decimals'], $numformat['dec_point'], $numformat['thousands_sep']);
                    }
                    if (@$header['format']) {
                        if (is_callable($header['format'])) {
                            $cell = call_user_func($header['format'], $cell);
                        }
                    }
                    if (@$header['image']) {
                        $image = str_replace($topat, $torep, $header['image']);
                        $cell = '<img class="coinimage" src="' . $image . '"/> ' . $cell;
                    }
                    $cells[] = "<td class='vtable-td-cell coinmarketcap-cell-{$key}'>{$cell}</td>";
                }
            }

            $rows[] = "<tr>" . implode('', $cells) . "</tr>";
        }
        $attrs = '';
        @$attr['class'][] = 'vtable';
        foreach ($attr as $k => $v) {
            if (is_array($v)) {
                $s = esc_attr__(implode(" ", $v));
            } else {
                $s = esc_attr($v);
            }
            $attrs .= $k . '="' . $s . '" ';
        }

        return "<table " . $attrs . "><thead class='vtable-head'>" . implode('', $headerHtml) . "</thead><tbody class='vtable-body'>" . implode('', $rows) . "</tbody></table>";
    }

    /* [ "id": { "visible": true, "sortable": true }
     *
     * {
        "id": "bitcoin",
        "name": "Bitcoin",
        "symbol": "BTC",
        "rank": "1",
        "price_usd": "3553.9",
        "price_btc": "1.0",
        "24h_volume_usd": "1703960000.0",
        "market_cap_usd": "58891410358.0",
        "available_supply": "16570925.0",
        "total_supply": "16570925.0",
        "percent_change_1h": "-0.5",
        "percent_change_24h": "-4.55",
        "percent_change_7d": "-13.83",
        "last_updated": "1505619565"
    },
     */
    public function display_plugin_coinmarketcap_page() {
        global $paged;
        if (empty($paged)) {
            $paged = 1;
        }
        $data = $this->get_coinmarketcap_data();
        $options = get_option($this->plugin_name);
        $headerColumns = json_decode($options['coinmarketcap_table_columns_json'], true);
        if (empty($headerColumns)) {
            $headerColumns = array(
                'rank' => array(
                    'visible' => true, 'sortable' => true, 'label' => 'Rank',
                ), 'id' => array(
                    'visible' => false,
                ), 'symbol' => array(
                    'visible' => true, 'label' => 'Symbol', 'sortable' => true, 'format' => 'strtoupper',
                ), 'name' => array(
                    'image' => 'https://files.coinmarketcap.com/static/img/coins/16x16/{id}.png', 'visible' => true, 'sortable' => true, 'label' => 'Name',
                ), 'price_usd' => array(
                    'visible' => true, 'sortable' => true, 'label' => 'Price',
                ), 'price_btc' => array(
                    'visible' => false,
                ), '24h_volume_usd' => array(
                    'visible' => true, 'sortable' => true, 'label' => '24h Volume', 'numformat' => array(
                        'decimals' => 2, 'dec_point' => '.', 'thousands_sep' => ',',
                    ),
                ), 'market_cap_usd' => array(
                    'visible' => true, 'sortable' => true, 'label' => 'Market Cap', 'numformat' => array(
                        'decimals' => 2, 'dec_point' => '.', 'thousands_sep' => ',',
                    ),
                ), 'available_supply' => array(
                    'visible' => true, 'sortable' => true, 'label' => 'Available Supply', 'numformat' => array(
                        'decimals' => 2, 'dec_point' => '.', 'thousands_sep' => ',',
                    ),
                ), 'total_supply' => array(
                    'visible' => true, 'sortable' => true, 'label' => 'Total Supply', 'numformat' => array(
                        'decimals' => 2, 'dec_point' => '.', 'thousands_sep' => ',',
                    ),
                ), 'percent_change_1h' => array(
                    'visible' => true, 'sortable' => true, 'label' => 'Percent Change (1H)', 'numformat' => array(
                        'decimals' => 2, 'dec_point' => '.', 'thousands_sep' => ',',
                    ),
                ), 'percent_change_24h' => array(
                    'visible' => true, 'sortable' => true, 'label' => 'Percent Change (24H)', 'numformat' => array(
                        'decimals' => 2, 'dec_point' => '.', 'thousands_sep' => ',',
                    ),
                ), 'percent_change_7d' => array(
                    'visible' => true, 'sortable' => true, 'label' => 'Percent Change (7D)', 'numformat' => array(
                        'decimals' => 2, 'dec_point' => '.', 'thousands_sep' => ',',
                    ),
                ), 'last_updated' => array(
                    'visible' => false,
                ),
            );
        }
        $current = (get_query_var('page')) ? absint(get_query_var('page')) : 1;

        $pagination = json_decode($options['coinmarketcap_pagination_json'], true);
        $perpage = intval($pagination['per_page']);
        $defaultPagination = array(
            'base' => remove_query_arg('page', get_permalink()) . '%_%', 'format' => '?page=%#%', 'show_all' => false, 'end_size' => 1, 'mid_size' => 2, 'prev_next' => true, 'prev_text' => __('« Previous'), 'next_text' => __('Next »'), 'type' => 'plain', 'add_args' => false, 'add_fragment' => '', 'before_page_number' => '', 'after_page_number' => '', 'per_page' => 10
        );
        $total = intval(count($data) / $perpage) + 1;
        $pagination = array_merge($defaultPagination, $pagination);
        $pagination['current'] = $current;
        $pagination['total'] = $total;
        $this->sort = @$_GET['sort'];
        switch (@$_GET['order']) {
            case 'desc':
                $this->order = [-1, 1];
                break;
            case 'asc':
            default:
                $this->order = [1, -1];
                break;
        }
        if (!empty($this->sort))
            usort($data, array($this, 'sort_coinmarketcap'));
        $data = array_slice($data, ($current - 1) * $perpage, $perpage);
        return "<div class='vtable-container'>" . $this->html_table($headerColumns, $data, ['id' => 'vcoinmarketcap-table']) . paginate_links($pagination) . "</div>";
    }

    function sort_coinmarketcap($a, $b) {
        if (empty($this->sort))
            return 0;
        if (is_numeric($a[$this->sort]) && is_numeric($b[$this->sort])) {
            $a = floatval($a[$this->sort]);
            $b = floatval($b[$this->sort]);
            if ($a > $b)
                return $this->order[0]; elseif ($a < $b)
                return $this->order[1];
            else return 0;
        } else {
            $a = $a[$this->sort];
            $b = $b[$this->sort];
            return strcmp($a, $b) * $this->order[0];
        }
    }

}
