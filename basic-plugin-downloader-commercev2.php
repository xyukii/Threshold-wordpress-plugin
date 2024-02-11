<?php
/**
 * Plugin Name: Basic Plugin Installer
 * Plugin URI: https://yukiisanctuary.rf.gd/
 * Description: Basic Plugin for Wordpress Development
 * Author: Yukii Sanctuary
 * Author URI: https://yukiisanctuary.rf.gd/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version: 0.1
 */

defined('ABSPATH') or die('No script kiddies please!');

class Custom_Plugin_Downloader {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'download_and_install_plugins'));
    }

    public function add_admin_menu() {
        add_menu_page('Plugin Downloader', 'Plugin Downloader', 'manage_options', 'plugin-downloader', array($this, 'plugin_downloader_page'));
    }

    public function plugin_downloader_page() {
        ?>
        <div class="wrap">
            <h2>Plugin Downloader</h2>
            <form method="post" action="">
                <?php wp_nonce_field('plugin-downloader-nonce'); ?>
                <label>Pilih Plugin:</label>
                <br>    
                <?php
                    // Daftar plugin yang mau didownload
                    $available_plugins = array(
                        'Redirection' => 'Redirection',
                        'GSiteKit' => 'GSiteKit',
                        'SmoothBackToTop' => 'SmoothBackToTop',
                        'WPDashboardNote' => 'WPDashboardNote',
                        'DummyContentGenerator' => 'DummyContentGenerator',
                        'LiteSpeedCache' => 'LiteSpeedCache',
                        'Elementor' => 'Elementor',
                        'ElementorHeaderFooterBuilder' => 'ElementorHeaderFooterBuilder',
                        'CustomLoginPageCustomizer' => 'CustomLoginPageCustomizer',
                        'SmoothScroller' => 'SmoothScroller',
                        'WPJobManager' => 'WPJobManager',
                        'GoogleFont' => 'GoogleFont',
                        'WPCodeSnippet' => 'WPCodeSnippet',
                        'WPSHideLogin' => 'WPSHideLogin',
                        'MaintenanceMode' => 'MaintenanceMode',
                        'AkismetAnti-Spam' => 'AkismetAnti-Spam',
                        'MemberPress' => 'MemberPress',
                        'Wordfence-Security' => 'Wordfence-Security',
                        'YoastSEO' => 'YoastSEO',
                        'ReallySimpleSSL' => 'ReallySimpleSSL',
                        'WooCommerce' => 'WooCommerce',
                        'AllInOneSEO' => 'AllInOneSEO',
                        'AllInOneWPMigration' => 'AllInOneWPMigration',
                        'default' => 'default',
                    );

                    foreach ($available_plugins as $plugin_id => $plugin_name) {
                        echo '<input type="checkbox" name="selected_plugins[]" value="' . esc_attr($plugin_id) . '"> ' . esc_html($plugin_name) . '<br>';
                    }
                ?>
                <br>
                <input type="submit" name="install_plugins" class="button button-primary" value="Install Plugins">
            </form>
        </div>
        <?php
    }

    public function download_and_install_plugins() {
        if (isset($_POST['install_plugins'])) {
            check_admin_referer('plugin-downloader-nonce');

            $selected_plugins = isset($_POST['selected_plugins']) ? $_POST['selected_plugins'] : array();

            foreach ($selected_plugins as $plugin) {
                $plugin_url = $this->get_plugin_download_url($plugin);
                $this->download_and_install_plugin($plugin_url);
            }

            // logging section
        }
    }

    private function get_plugin_download_url($plugin) {
        // Sesuaikan dengan URL atau sumber plugin Anda
        $plugin_urls = array(
            'Redirection' => 'https://downloads.wordpress.org/plugin/redirection.5.4.2.zip',
            'GSiteKit' => 'https://downloads.wordpress.org/plugin/google-site-kit.1.119.0.zip',
            'SmoothBackToTop' => 'https://downloads.wordpress.org/plugin/smooth-back-to-top-button.zip',
            'WPDashboardNote' => 'https://downloads.wordpress.org/plugin/wp-dashboard-notes.1.0.11.zip',
            'DummyContentGenerator' => 'https://downloads.wordpress.org/plugin/wp-dummy-content-generator.zip',
            'LiteSpeedCache' => 'https://downloads.wordpress.org/plugin/litespeed-cache.6.0.0.1.zip',
            'Elementor' => 'https://downloads.wordpress.org/plugin/elementor.3.19.0.zip',
            'ElementorHeaderFooterBuilder' => 'https://downloads.wordpress.org/plugin/header-footer-elementor.1.6.24.zip',
            'CustomLoginPageCustomizer' => 'https://downloads.wordpress.org/plugin/login-customizer.2.3.2.zip',
            'SmoothScroller' => 'https://downloads.wordpress.org/plugin/mousewheel-smooth-scroll.6.4.1.zip',
            'WPJobManager' => 'https://downloads.wordpress.org/plugin/wp-job-manager.2.2.0.zip',
            'GoogleFont' => 'https://downloads.wordpress.org/plugin/olympus-google-fonts.3.5.1.zip',
            'WPCodeSnippet' => 'https://downloads.wordpress.org/plugin/insert-headers-and-footers.2.1.7.zip',
            'WPSHideLogin' => 'https://downloads.wordpress.org/plugin/wps-hide-login.1.9.13.2.zip',
            'MaintenanceMode' => 'https://downloads.wordpress.org/plugin/maintenance.4.08.zip',
            'AkismetAnti-Spam' => 'https://downloads.wordpress.org/plugin/akismet.5.3.1.zip',
            'MemberPress' => 'https://downloads.wordpress.org/plugin/akismet.5.3.1.zip',
            'Wordfence-Security' => 'https://downloads.wordpress.org/plugin/wordfence.7.11.1.zip',
            'YoastSEO' => 'https://downloads.wordpress.org/plugin/wordpress-seo.21.9.1.zip',
            'ReallySimpleSSL' => 'https://downloads.wordpress.org/plugin/really-simple-ssl.7.2.2.zip',
            'WooCommerce' => 'https://downloads.wordpress.org/plugin/woocommerce.8.5.2.zip',
            'AllInOneSEO' => 'https://downloads.wordpress.org/plugin/all-in-one-seo-pack.4.5.5.zip',
            'AllInOneWPMigration' => 'https://downloads.wordpress.org/plugin/all-in-one-wp-migration.7.79.zip',
            'default' => 'https://comfolio.my.id/#',
            // Tambahkan URL plugin lainnya sesuai kebutuhan
        );

        return $plugin_urls[$plugin];
    }

    private function download_and_install_plugin($plugin_url) {
        $plugins_dir = WP_PLUGIN_DIR;
        $zip_file = $plugins_dir . '/temp-plugin.zip';

        // Command untuk Download plugin
        file_put_contents($zip_file, file_get_contents($plugin_url));

        // Ekstrak plugin ke direktori plugin WordPress
        $zip = new ZipArchive;
        if ($zip->open($zip_file) === TRUE) {
            $zip->extractTo($plugins_dir);
            $zip->close();
            unlink($zip_file);

            // Ambil nama direktori plugin dari name file zip
            $plugin_directory = basename($zip_file, '.zip');

            // Aktifkan plugin jika belum aktif
            if (!is_plugin_active($plugin_directory . '/' . $plugin_directory . '.php')) {
                activate_plugin($plugin_directory . '/' . $plugin_directory . '.php');
            }
        } else {
            // Handle kesalahan ekstraksi jika diperlukan
        }
    }
}

// Inisialisasi plugin
$custom_plugin_downloader = new Custom_Plugin_Downloader();
