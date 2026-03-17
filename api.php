<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$api_key = '你的api';

// 获取参数
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 24; // 每页24张
$query = isset($_GET['q']) ? $_GET['q'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// 分类映射（Pexels搜索关键词）
$categories = [
    'popular' => 'popular',
    'nature' => 'nature landscape',
    'people' => 'people portrait',
    'business' => 'business work',
    'technology' => 'technology computer',
    'animals' => 'animals pets',
    'food' => 'food drink',
    'travel' => 'travel vacation',
    'fashion' => 'fashion style',
    'sports' => 'sports fitness',
    'music' => 'music instruments',
    'art' => 'art painting',
    'background' => 'background texture',
    'wallpaper' => 'wallpaper 4k'
];

// 确定搜索关键词
if (!empty($query)) {
    $search_term = urlencode($query);
} elseif (!empty($category) && isset($categories[$category])) {
    $search_term = urlencode($categories[$category]);
} else {
    $search_term = 'popular'; // 默认热门
}

// 构建API URL
if (empty($query) && $category == 'popular') {
    // 热门图片使用不同的API端点
    $url = "https://api.pexels.com/v1/curated?per_page={$per_page}&page={$page}";
} else {
    $url = "https://api.pexels.com/v1/search?query={$search_term}&per_page={$per_page}&page={$page}";
}

// 调用Pexels API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: ' . $api_key,
    'User-Agent: Mozilla/5.0'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$images = array();
$total_results = 0;

if ($http_code == 200 && $response) {
    $data = json_decode($response, true);
    
    if (isset($data['photos']) && is_array($data['photos'])) {
        $total_results = $data['total_results'] ?? 0;
        
        foreach ($data['photos'] as $photo) {
            $images[] = [
                'id' => $photo['id'],
                'pic' => $photo['src']['medium'],      // 缩略图
                'big' => $photo['src']['large'],       // 大图
                'original' => $photo['src']['original'], // 原图
                'title' => $photo['alt'] ?: 'Pexels图片',
                'photographer' => $photo['photographer'],
                'photographer_url' => $photo['photographer_url'],
                'url' => $photo['url'],
                'width' => $photo['width'],
                'height' => $photo['height']
            ];
        }
    }
}

echo json_encode([
    'success' => true,
    'page' => $page,
    'per_page' => $per_page,
    'total' => $total_results,
    'images' => $images
], JSON_UNESCAPED_UNICODE);
?>



