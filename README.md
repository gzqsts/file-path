# File Path - 文件路径处理工具包

提供强大的文件路径解析、操作和格式化功能。

## 功能特性

- ✅ 路径解析：支持解析文件路径、URL路径等
- ✅ 不可变对象：所有修改操作返回新实例，保证数据安全
- ✅ 链式调用：支持流畅的链式操作
- ✅ 路径操作：支持路径追加、修改、替换等操作
- ✅ 文件信息：获取文件名、扩展名、目录等
- ✅ 分隔符控制：支持自定义目录分隔符
- ✅ URL支持：支持解析和操作URL路径

## 环境要求

- PHP >= 8.0

## 安装

通过 Composer 安装：

```bash
composer require gzqsts/file-path
```

## 快速开始

```php
use Gzqsts\FilePath\Path;

// 创建路径对象
$path = new Path('uploads/images/2024/photo.jpg');

// 获取文件信息
echo $path->getFilename();    // photo
echo $path->getExtension();   // jpg
echo $path->getBasename();    // photo.jpg
echo $path->getPath();        // uploads/images/2024

// 转换为字符串
echo (string)$path;           // uploads/images/2024/photo.jpg
```

## 基本用法

### 创建路径对象

```php
use Gzqsts\FilePath\Path;

// 从文件路径创建
$path = new Path('uploads/images/photo.jpg');

// 从URL创建
$path = new Path('https://example.com/files/document.pdf');

// 从相对路径创建
$path = new Path('../images/logo.png');
```

### 获取路径信息

```php
$path = new Path('uploads/images/2024/photo.jpg');

// 获取文件名（不含扩展名）
$filename = $path->getFilename();  // photo

// 获取文件扩展名
$extension = $path->getExtension(); // jpg

// 获取完整文件名（含扩展名）
$basename = $path->getBasename();  // photo.jpg

// 获取目录路径
$dirPath = $path->getPath();       // uploads/images/2024

// 获取协议（URL）
$scheme = $path->getScheme();      // https 或 ''

// 获取主机（URL）
$host = $path->getHost();          // example.com 或 ''

// 获取端口（URL）
$port = $path->getPort();          // 80 或 ''

// 获取查询参数（URL）
$query = $path->getQuery();        // key=value 或 ''
```

### 修改路径

所有修改操作都返回新的 `Path` 实例，原对象不变（不可变对象模式）。

```php
$path = new Path('uploads/images/photo.jpg');

// 修改文件名
$newPath = $path->withFilename('newphoto');
// 结果: uploads/images/newphoto.jpg

// 修改扩展名
$newPath = $path->withExtension('png');
// 结果: uploads/images/photo.png

// 修改完整文件名
$newPath = $path->withBasename('newfile.png');
// 结果: uploads/images/newfile.png

// 追加目录
$newPath = $path->withPath('2024/11');
// 结果: uploads/images/2024/11/photo.jpg

// 替换整个路径
$newPath = $path->withPathAll('files/documents');
// 结果: files/documents/photo.jpg

// 修改协议
$newPath = $path->withScheme('https');
// 结果: https://uploads/images/photo.jpg

// 修改主机
$newPath = $path->withHost('example.com');
// 结果: //example.com/uploads/images/photo.jpg

// 修改端口
$newPath = $path->withPort('8080');
// 结果: //:8080/uploads/images/photo.jpg

// 添加查询参数
$newPath = $path->withQuery('token=abc123');
// 结果: uploads/images/photo.jpg?token=abc123
```

### 设置目录分隔符

```php
$path = new Path('uploads/images/photo.jpg');

// 使用正斜杠
$unixPath = $path->withDirSeparator('/');
// 结果: uploads/images/photo.jpg

// 使用反斜杠（Windows风格）
$windowsPath = $path->withDirSeparator('\\');
// 结果: uploads\images\photo.jpg
```

### 路径字符串转换

```php
$path = new Path('uploads/images/photo.jpg');

// 转换为字符串（默认）
echo (string)$path;                    // uploads/images/photo.jpg

// 转换为字符串（带前缀路径）
echo $path->toString('storage');       // storage/uploads/images/photo.jpg

// 获取完整路径（包含协议、主机等）
echo $path->getFull();                 // uploads/images/photo.jpg
```

### 创建目录

```php
// 静态方法：创建目录
Path::createDirs('/path/to/file.txt');
// 会自动创建 /path/to 目录（如果不存在）
```

### 链式调用

```php
$path = new Path('uploads/photo.jpg');

$newPath = $path
    ->withPath('2024/11')
    ->withFilename('newphoto')
    ->withExtension('png')
    ->withDirSeparator('/');

// 结果: 2024/11/newphoto.png
```

## 完整示例

```php
<?php

use Gzqsts\FilePath\Path;

// 示例1: 处理上传文件路径
$originalPath = 'temp/uploads/user123/photo.jpg';
$path = new Path($originalPath);

// 创建新的存储路径
$storagePath = $path
    ->withPathAll('storage/images')
    ->withPath(date('Y/m'))
    ->withFilename(uniqid())
    ->withExtension('jpg');

echo $storagePath->toString(); 
// 输出: storage/images/2024/11/507f1f77bcf86cd799439011.jpg

// 示例2: 处理URL路径
$url = 'https://example.com/files/document.pdf?download=1';
$path = new Path($url);

echo $path->getScheme();    // https
echo $path->getHost();     // example.com
echo $path->getBasename(); // document.pdf
echo $path->getQuery();    // download=1

// 修改URL
$newUrl = $path
    ->withHost('cdn.example.com')
    ->withPath('cdn/files')
    ->getFull();

echo $newUrl; 
// 输出: https://cdn.example.com/cdn/files/document.pdf?download=1

// 示例3: 路径规范化
$path = new Path('uploads\\images//photo.jpg');
echo (string)$path; 
// 输出: uploads/images/photo.jpg（自动规范化）

// 示例4: 创建目录
$filePath = '/var/www/uploads/2024/11/file.txt';
Path::createDirs($filePath);
// 自动创建 /var/www/uploads/2024/11 目录
```

## API 参考

### 构造函数

```php
public function __construct(string $uri)
```

创建一个新的 `Path` 实例。

### 获取方法

- `getScheme(): string` - 获取协议（如 http, https）
- `getHost(): string` - 获取主机名
- `getPort(): string` - 获取端口号
- `getQuery(): string` - 获取查询参数
- `getFilename(): ?string` - 获取文件名（不含扩展名）
- `getExtension(): ?string` - 获取文件扩展名
- `getBasename(): ?string` - 获取完整文件名（含扩展名）
- `getPath(): string` - 获取目录路径部分
- `getFull(): string` - 获取完整路径（包含协议、主机等）

### 修改方法（返回新实例）

- `withScheme(string $scheme): Path` - 设置协议
- `withHost(string $host): Path` - 设置主机
- `withPort(string $port): Path` - 设置端口
- `withQuery(string $query): Path` - 添加查询参数
- `withFilename(string $filename): Path` - 设置文件名
- `withExtension(string $extension): Path` - 设置扩展名
- `withBasename(string $basename): Path` - 设置完整文件名
- `withPath(string $directory): Path` - 追加目录路径
- `withPathAll(string $path): Path` - 替换整个目录路径
- `withDirSeparator(string $dirSeparator): Path` - 设置目录分隔符

### 转换方法

- `__toString(): string` - 转换为字符串
- `toString(string $addPath = ''): string` - 转换为字符串（可添加前缀路径）

### 静态方法

- `createDirs(string $filePath): void` - 创建目录（如果不存在）
- `composeComponents(array $pathInfo, string $dirSeparator): string` - 组合路径组件

## 注意事项

1. **不可变对象**：所有 `with*` 方法都返回新的 `Path` 实例，原对象不会被修改
2. **路径规范化**：构造函数会自动将反斜杠转换为正斜杠，并规范化路径
3. **空值处理**：获取方法可能返回空字符串或 `null`，请根据实际情况处理
4. **URL支持**：支持解析和操作URL路径，包括协议、主机、端口和查询参数
