<?php
// オプションを取得する関数
function get_cocoon_access_log_options() {
    $default_options = [
        'columns' => ['post_id', 'title', 'author', 'categories', 'tags', 'date', 'total_count'],
        'period' => 'day',
        'limit' => 30,
    ];
    
    $options = get_option('cocoon_access_log_options', $default_options);
    
    // $_GETパラメータがある場合は上書き
    if (isset($_GET['period'])) $options['period'] = $_GET['period'];
    
    return $options;
}

// オプションを保存する関数
function save_cocoon_access_log_options($options) {
    update_option('cocoon_access_log_options', $options);
}

// フィルタフォームの出力
function cocoon_access_log_filter_form($options) {
    echo '<form method="get">';
    echo '<input type="hidden" name="page" value="cocoon-access-log">';
    
    // 期間選択
    echo '<select name="period">';
    echo '<option value="day" ' . selected($options['period'], 'day', false) . '>今日</option>';
    echo '<option value="week" ' . selected($options['period'], 'week', false) . '>今週</option>';
    echo '<option value="month" ' . selected($options['period'], 'month', false) . '>今月</option>';
    echo '</select>';

    echo '<input type="submit" value="フィルタ">';
    echo '</form>';
}

// 表示オプションフォームの出力
function cocoon_access_log_display_options_form($options) {
    echo '<form method="post" action="" id="display-options-form" style="display:none;">';
    echo '<h4>表示オプション</h4>';
    
    $all_columns = [
        'post_id' => '記事ID',
        'title' => '記事タイトル',
        'author' => '投稿者',
        'categories' => 'カテゴリー',
        'tags' => 'タグ',
        'date' => '投稿日',
        'total_count' => 'アクセス数'
    ];
    foreach ($all_columns as $key => $label) {
        echo '<label style="display:inline-block; margin-right:15px;"><input type="checkbox" name="columns[]" value="' . $key . '" ' 
             . checked(in_array($key, $options['columns']), true, false) . '> ' . $label . '</label>';
    }

    // 表示件数選択
    echo '<label style="display:inline-block; margin-right:15px;">表示件数: ';
    echo '<select name="limit">';
    foreach ([10, 30, 50, 100] as $count) {
        echo '<option value="' . $count . '" ' . selected($options['limit'], $count, false) . '>' . $count . '</option>';
    }
    echo '</select></label>';

    echo '<input type="submit" value="更新">';
    echo '</form>';
}
