<?php
/**
 * File Path 使用示例
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Gzqsts\FilePath\Path;

echo "=== File Path 使用示例 ===\n\n";

// 示例1: 基本路径操作
echo "1. 基本路径操作:\n";
$path = new Path('uploads/images/photo.jpg');
echo "   原始路径: " . (string)$path . "\n";
echo "   文件名: " . $path->getFilename() . "\n";
echo "   扩展名: " . $path->getExtension() . "\n";
echo "   目录路径: " . $path->getPath() . "\n\n";

// 示例2: 修改路径
echo "2. 修改路径:\n";
$newPath = $path->withPath('2024/11')->withFilename('newphoto');
echo "   修改后: " . (string)$newPath . "\n\n";

// 示例3: URL路径
echo "3. URL路径处理:\n";
$urlPath = new Path('https://example.com/files/document.pdf?download=1');
echo "   完整URL: " . $urlPath->getFull() . "\n";
echo "   协议: " . $urlPath->getScheme() . "\n";
echo "   主机: " . $urlPath->getHost() . "\n";
echo "   查询参数: " . $urlPath->getQuery() . "\n\n";

// 示例4: 链式调用
echo "4. 链式调用:\n";
$chainPath = (new Path('temp/file.txt'))
    ->withPathAll('storage')
    ->withPath(date('Y/m'))
    ->withFilename(uniqid())
    ->withExtension('jpg');
echo "   链式结果: " . (string)$chainPath . "\n\n";

// 示例5: 目录分隔符
echo "5. 目录分隔符:\n";
$unixPath = $path->withDirSeparator('/');
$winPath = $path->withDirSeparator('\\');
echo "   Unix风格: " . (string)$unixPath . "\n";
echo "   Windows风格: " . (string)$winPath . "\n\n";

echo "=== 示例完成 ===\n";

