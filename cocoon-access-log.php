<?php
/*
Plugin Name: Cocoon Access Log
Plugin URI: https://kasiri.icu/blog/wordpress/1522/
Description: Cocoon専用プラグイン。アクセスの多い順に日、週、月ごとにソートして記事一覧を表示します。
Version: 1.16
Author: Kasiri
Author URI: https://kasiri.icu/
*/


// ファイルの読み込み
include_once(plugin_dir_path(__FILE__) . 'cocoon-access-log-settings.php');
include_once(plugin_dir_path(__FILE__) . 'cocoon-access-log-fetch.php');
include_once(plugin_dir_path(__FILE__) . 'cocoon-access-log-display.php');

// 管理メニューに「Cocoon Access Log」を追加
function cocoon_access_log_menu() {
    add_menu_page(
        'Cocoon Access Log',
        'アクセスログ',
        'manage_options',
        'cocoon-access-log',
        'cocoon_access_log_page'
    );
}
add_action('admin_menu', 'cocoon_access_log_menu');

// 管理画面にアクセスログを表示するコールバック関数
function cocoon_access_log_page() {
    wp_enqueue_style('cocoon-access-log-style', plugin_dir_url(__FILE__) . 'cocoon-access-log.css');
    
    $options = get_cocoon_access_log_options();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['columns'])) {
            $options['columns'] = $_POST['columns'];
        }
        if (isset($_POST['limit'])) {
            $options['limit'] = intval($_POST['limit']);
        }
        save_cocoon_access_log_options($options);
    }

    echo '<h1>Cocoon Access Log</h1>';
    echo '<p>記事のアクセス統計を表示します。</p>';

    // フィルタフォームと表示オプションの出力
    cocoon_access_log_filter_form($options);
    echo '<button id="display-options-toggle" style="margin-top: 10px;">表示オプション</button>';
    cocoon_access_log_display_options_form($options);

    // フィルタに基づく見出しを表示
    $filter_names = [
        'day' => '今日のアクセス統計一覧',
        'week' => '今週のアクセス統計一覧',
        'month' => '今月のアクセス統計一覧'
    ];
    echo "<h2>" . $filter_names[$options['period']] . "</h2>";

    // アクセスログを取得して表示
    $logs = get_cocoon_access_logs($options['period'], $options['limit']);
    if ($logs) {
        cocoon_access_log_display_table($logs, $options);
    } else {
        echo '<p>アクセスログはありません。</p>';
    }

    // JavaScriptで表示オプションをトグルする処理
    echo '<script>
    document.getElementById("display-options-toggle").addEventListener("click", function() {
        var form = document.getElementById("display-options-form");
        form.style.display = form.style.display === "none" ? "block" : "none";
    });
    </script>';
}
