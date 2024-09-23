<?php
// アクセスログを取得する関数
function get_cocoon_access_logs($period = 'day', $limit = 30) {
    global $wpdb;

    $date_query = '';
    if ($period === 'day') {
        $date_query = "AND date >= CURDATE()";
    } elseif ($period === 'week') {
        $date_query = "AND date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    } elseif ($period === 'month') {
        $date_query = "AND date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
    }

    $query = "
        SELECT post_id, SUM(count) as total_count
        FROM {$wpdb->prefix}cocoon_accesses
        WHERE 1=1 $date_query
        GROUP BY post_id
        ORDER BY total_count DESC
        LIMIT %d
    ";
    
    return $wpdb->get_results($wpdb->prepare($query, $limit));
}

// 記事IDから投稿者、カテゴリー、タグ、投稿日を取得する関数
function get_post_details($post_id) {
    $author_id = get_post_field('post_author', $post_id);
    $author_name = get_the_author_meta('display_name', $author_id);
    $categories = get_the_category($post_id);
    $category_names = array_map(function($cat) { return $cat->name; }, $categories);
    $category_list = implode(', ', $category_names);
    $tags = get_the_tags($post_id);
    $tag_names = $tags ? array_map(function($tag) { return $tag->name; }, $tags) : [];
    $tag_list = implode(', ', $tag_names);
    $post_date = get_the_date('Y/m/d', $post_id);

    return [
        'author' => $author_name,
        'categories' => $category_list,
        'tags' => $tag_list,
        'date' => $post_date,
    ];
}
