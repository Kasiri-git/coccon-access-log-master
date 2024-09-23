<?php
// アクセスログをテーブル形式で表示する関数
function cocoon_access_log_display_table($logs, $options) {
    echo '<table class="widefat striped">';
    echo '<thead><tr>';
    foreach ($options['columns'] as $column) {
        echo '<th>' . esc_html(ucwords(str_replace('_', ' ', $column))) . '</th>';
    }
    echo '</tr></thead>';
    echo '<tbody>';

    foreach ($logs as $log) {
        $post_details = get_post_details($log->post_id);
        echo '<tr>';
        foreach ($options['columns'] as $column) {
            if ($column === 'title') {
                // 記事タイトルに編集画面へのリンクを設定
                echo '<td><a href="' . get_edit_post_link($log->post_id) . '" target="_blank">' . get_the_title($log->post_id) . '</a></td>';
            } else {
                // その他のカラムの処理
                echo '<td>' . esc_html($post_details[$column] ?? $log->$column) . '</td>';
            }
        }
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
}
?>
