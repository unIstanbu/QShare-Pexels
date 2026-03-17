# QShare - 图片素材分享平台

> 基于 Pexels API 构建的高清图片素材站，支持分类浏览、关键词搜索、无限滚动。

![QShare 截图](screenshot.png)

## ✨ 特性

- 🚀 **基于 Pexels API** - 百万级高清正版图片，每日更新
- 📱 **响应式设计** - 手机、平板、电脑完美适配
- 🎨 **14个分类** - 热门、自然、人物、商务、科技、动物、美食等
- 🔍 **关键词搜索** - 支持中文搜索
- ♾️ **无限滚动** - 滚动到底部自动加载更多
- 🖼️ **灯箱预览** - 点击图片放大查看，支持左右切换
- 📝 **图片信息** - 显示摄影师、图片尺寸
- ⚡ **快速加载** - 图片懒加载，优化性能

## 🛠️ 技术栈

- 前端：HTML5、CSS3、原生 JavaScript
- 后端：PHP
- 图片API：[Pexels API](https://www.pexels.com/api/)
- 灯箱：[Lightbox2](https://github.com/lokesh/lightbox2)

## 📦 安装部署

### 1. 获取 Pexels API Key

1. 访问 [Pexels API](https://www.pexels.com/api/) 注册账号
2. 申请 API Key
3. 将获取到的 Key 保存备用

### 2. 下载源码

```bash
git clone https://github.com/unIstanbu/QShare-Pexels
```

3. 配置 API Key

打开 api.php，找到这一行：

```php
$api_key = '你的API_KEY';
```

替换成你申请的 API Key。

4. 上传到服务器

将整个项目上传到你的 Web 服务器（需支持 PHP）。

5. 访问网站

在浏览器中访问你的域名即可。

📁 项目结构

```
qshare-pexels/
├── index.php
├── api.php
├── style/
│   ├── base.css
│   └── index.css
└── README.md
```

⚙️ 配置说明

修改每页图片数量

在 api.php 中找到：

```php
$per_page = 24;
```

修改分类

编辑 index.php 中的分类列表和 api.php 中的 $categories 数组。

📄 许可证

MIT License © 2026 

🙏 致谢

· Pexels
· Lightbox2

⚠️ 说明

· 仅用于学习和交流
· 请遵守 Pexels API 条款
· 图片版权归原作者所有

---

如果对你有帮助，请给一个 ⭐️

```
