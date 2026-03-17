$(document).ready(function() {
    let page = 1;
    let loading = false;
    let currentType = 'all';
    
    // 加载图片
    function loadImages(reset = false) {
        if (loading) return;
        
        loading = true;
        if (reset) {
            page = 1;
            $('#gallery').empty();
        }
        
        $('#loading').show();
        $('#loadMoreBtn').hide();
        
        $.ajax({
            url: 'api.php',
            type: 'GET',
            data: {
                page: page,
                type: currentType
            },
            dataType: 'json',
            timeout: 10000,
            success: function(response) {
                if (response.code === 200 && response.data.length > 0) {
                    renderImages(response.data);
                    page++;
                    
                    // 更新更新时间
                    $('.update-time').text('最新更新 ' + new Date().toLocaleString());
                } else {
                    // 没有更多图片
                    $('#loadMoreBtn').text('没有更多了').prop('disabled', true);
                }
                
                $('#loading').hide();
                $('#loadMoreBtn').show();
                loading = false;
            },
            error: function() {
                alert('加载失败，请稍后重试');
                $('#loading').hide();
                $('#loadMoreBtn').show();
                loading = false;
            }
        });
    }
    
    // 渲染图片
    function renderImages(images) {
        let html = '';
        
        $.each(images, function(index, item) {
            html += `
                <div class="gallery-item">
                    <div class="item-img">
                        <img src="${item.thumb}" alt="${item.title}" loading="lazy" onerror="this.src='https://picsum.photos/400/300?random=${Math.random()}'">
                    </div>
                    <div class="item-info">
                        <h4>${item.title}</h4>
                        <div class="item-meta">
                            <span><i>👤</i> ${item.author}</span>
                            <span><i>❤️</i> ${item.likes}</span>
                            <span><i>👁️</i> ${item.views}</span>
                        </div>
                        <span class="item-category">${item.category}</span>
                    </div>
                </div>
            `;
        });
        
        $('#gallery').append(html);
    }
    
    // 初始加载
    loadImages();
    
    // 点击加载更多
    $('#loadMoreBtn').click(function() {
        loadImages();
    });
    
    // 滚动加载
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
            if (!loading && page <= 50) { // 最多50页
                loadImages();
            }
        }
    });
    
    // 分类筛选
    $('.filter button').click(function() {
        $('.filter button').removeClass('active');
        $(this).addClass('active');
        
        currentType = $(this).text();
        loadImages(true); // 重置并加载新分类
    });
    
    // 搜索功能
    $('.search button').click(function() {
        let keyword = $('.search input').val();
        if (keyword) {
            alert('搜索功能开发中：' + keyword);
        }
    });
    
    $('.search input').keypress(function(e) {
        if (e.which === 13) {
            $('.search button').click();
        }
    });
});



