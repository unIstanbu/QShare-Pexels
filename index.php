<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QShare - Pexels图片素材库 | 高清免费图片</title>
    <meta name="description" content="免费高清图片素材，基于Pexels API，提供海量优质图片。支持分类浏览、关键词搜索。">
    <link rel="stylesheet" href="style/base.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>QShare<span>图片素材</span></h1>
            </div>
            
            <!-- 搜索框 -->
            <div class="search-box">
                <form id="searchForm">
                    <input type="text" id="searchInput" placeholder="搜索图片... 例如: 美女 风景 科技" autocomplete="off">
                    <button type="submit">🔍 搜索</button>
                </form>
            </div>
        </div>
        
        <!-- 分类导航 -->
        <nav class="categories">
            <div class="categories-wrapper">
                <a href="#" data-category="popular" class="active">🔥 热门</a>
                <a href="#" data-category="nature">🌲 自然</a>
                <a href="#" data-category="people">👥 人物</a>
                <a href="#" data-category="business">💼 商务</a>
                <a href="#" data-category="technology">💻 科技</a>
                <a href="#" data-category="animals">🐶 动物</a>
                <a href="#" data-category="food">🍔 美食</a>
                <a href="#" data-category="travel">✈️ 旅行</a>
                <a href="#" data-category="fashion">👗 时尚</a>
                <a href="#" data-category="sports">⚽ 运动</a>
                <a href="#" data-category="music">🎵 音乐</a>
                <a href="#" data-category="art">🎨 艺术</a>
                <a href="#" data-category="background">🌈 背景</a>
                <a href="#" data-category="wallpaper">🖼️ 壁纸</a>
            </div>
        </nav>
    </header>

    <main class="main">
        <div class="container">
            <!-- 当前分类标题 -->
            <div class="category-header">
                <h2 id="categoryTitle">🔥 热门图片</h2>
                <span id="resultCount" class="result-count"></span>
            </div>
            
            <!-- 图片网格 -->
            <div class="gallery" id="gallery"></div>
            
            <!-- 加载更多 -->
            <div class="load-more">
                <div class="loading" id="loading">
                    <span></span> 加载中...
                </div>
                <button class="load-more-btn" id="loadMoreBtn" style="display: none;">加载更多</button>
            </div>
        </div>
    </main>

    <!-- 图片详情弹窗（Lightbox配置）-->
    <div style="display: none;">
        <a id="lightbox-trigger" data-lightbox="gallery"></a>
    </div>

    <footer class="footer">
        <div class="container">
            <p>© 2026 QShare - 基于 Pexels API 构建 | 所有图片版权归原作者所有</p>
            <p>本网站仅用于学习和展示，图片来自 <a href="https://www.pexels.com" target="_blank">Pexels</a></p>
        </div>
    </footer>

    <script>
        let currentPage = 1;
        let currentCategory = 'popular';
        let currentQuery = '';
        let loading = false;
        let hasMore = true;
        let totalImages = 0;

        const gallery = document.getElementById('gallery');
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        const loadingDiv = document.getElementById('loading');
        const categoryTitle = document.getElementById('categoryTitle');
        const resultCount = document.getElementById('resultCount');
        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('searchInput');

        // 分类点击事件
        document.querySelectorAll('.categories a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // 更新active状态
                document.querySelectorAll('.categories a').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                // 更新当前分类
                currentCategory = this.dataset.category;
                currentQuery = '';
                searchInput.value = '';
                currentPage = 1;
                hasMore = true;
                
                // 更新标题
                const categoryNames = {
                    'popular': '🔥 热门图片',
                    'nature': '🌲 自然风光',
                    'people': '👥 人物肖像',
                    'business': '💼 商务职场',
                    'technology': '💻 科技数码',
                    'animals': '🐶 萌宠动物',
                    'food': '🍔 美食料理',
                    'travel': '✈️ 旅行摄影',
                    'fashion': '👗 时尚穿搭',
                    'sports': '⚽ 运动健身',
                    'music': '🎵 音乐艺术',
                    'art': '🎨 绘画艺术',
                    'background': '🌈 背景素材',
                    'wallpaper': '🖼️ 高清壁纸'
                };
                categoryTitle.textContent = categoryNames[currentCategory] || '图片库';
                
                // 清空并重新加载
                gallery.innerHTML = '';
                loadImages();
            });
        });

        // 搜索提交
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const query = searchInput.value.trim();
            if (!query) return;
            
            currentQuery = query;
            currentCategory = '';
            currentPage = 1;
            hasMore = true;
            
            // 移除分类active状态
            document.querySelectorAll('.categories a').forEach(l => l.classList.remove('active'));
            
            categoryTitle.textContent = `🔍 搜索: "${query}"`;
            gallery.innerHTML = '';
            loadImages();
        });

        // 加载图片
        function loadImages() {
            if (loading || !hasMore) return;
            
            loading = true;
            loadingDiv.style.display = 'block';
            loadMoreBtn.style.display = 'none';

            let url = `api.php?page=${currentPage}`;
            if (currentQuery) {
                url += `&q=${encodeURIComponent(currentQuery)}`;
            } else if (currentCategory) {
                url += `&category=${currentCategory}`;
            }

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.images.length > 0) {
                        renderImages(data.images);
                        
                        totalImages = data.total || 0;
                        currentPage++;
                        
                        // 判断是否还有更多
                        if (data.images.length < 24 || (totalImages > 0 && currentPage * 24 >= totalImages)) {
                            hasMore = false;
                            loadMoreBtn.textContent = '没有更多了';
                            loadMoreBtn.disabled = true;
                        } else {
                            loadMoreBtn.style.display = 'block';
                        }
                        
                        // 更新结果数量
                        if (totalImages > 0) {
                            resultCount.textContent = `共 ${totalImages} 张图片`;
                        }
                    } else {
                        gallery.innerHTML = '<div class="no-results">没有找到相关图片</div>';
                        hasMore = false;
                        loadMoreBtn.style.display = 'none';
                    }
                    
                    loading = false;
                    loadingDiv.style.display = 'none';
                })
                .catch(err => {
                    console.error('加载失败:', err);
                    loading = false;
                    loadingDiv.style.display = 'none';
                    loadMoreBtn.style.display = 'block';
                });
        }

        // 渲染图片
        function renderImages(images) {
            images.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'gallery-item';
                
                const link = document.createElement('a');
                link.href = item.big;
                link.setAttribute('data-lightbox', 'gallery');
                link.setAttribute('data-title', `${item.title}\n摄影: ${item.photographer}\n点击查看原图`);
                
                const img = document.createElement('img');
                img.src = item.pic;
                img.loading = 'lazy';
                img.alt = item.title;
                
                // 图片信息浮层
                const infoDiv = document.createElement('div');
                infoDiv.className = 'image-info';
                infoDiv.innerHTML = `
                    <span class="photographer">📷 ${item.photographer}</span>
                    <span class="size">${item.width}x${item.height}</span>
                `;
                
                link.appendChild(img);
                link.appendChild(infoDiv);
                itemDiv.appendChild(link);
                gallery.appendChild(itemDiv);
            });
            
            // 重新初始化lightbox（针对新添加的图片）
            lightbox.init();
        }

        // 初始加载
        loadImages();

        // 点击加载更多
        loadMoreBtn.addEventListener('click', loadImages);

        // 滚动加载
        window.addEventListener('scroll', () => {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 1000) {
                loadImages();
            }
        });
    </script>
</body>
</html>



