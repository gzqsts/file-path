<?php
/**
 * Copyright (C) gzqsts.com
 *
 * 开发者邮箱：11977315@qq.com
 * @Time: 2024/11/11 21:42
 * @Notes: file-path
 */

namespace Gzqsts\FilePath;

class Path
{
    private array $pathInfo;

    //分隔符
    private string $dirSeparator = DIRECTORY_SEPARATOR;

    public function __construct(string $uri)
    {
        //统一用正斜杠
        $uri = trim(str_replace('\\', '/', $uri), '\\/');
        $uris = parse_url($uri);
        $info = pathinfo(trim($uris['path']??'', '/'));
        if(!isset($info['dirname'])){
            $info['dirname'] = '';
        }

        $info['scheme'] = $uris['scheme']??'';
        $info['host'] = $uris['host']??'';
        $info['port'] = $uris['port']??'';
        $info['query'] = $uris['query']??'';
        $info['dirname'] = $info['dirname'] == '.' ? [] : explode('/', $info['dirname']);
        $info['extension'] = $info['extension']??'';
        $info['basename'] = $info['basename']??'';
        $info['filename'] = $info['filename']??'';
        $this->pathInfo = $info;
    }

    /**
     * @Notes: 获取协议
     *
     * @Time: 2025/11/12 14:23
     * @return string
     */
    public function getScheme(): string
    {
        return $this->pathInfo['scheme']??'';
    }

    /**
     * @Notes: 获取主机
     *
     * @Time: 2025/11/12 14:22
     * @return string
     */
    public function getHost(): string
    {
        return $this->pathInfo['host']??'';
    }

    /**
     * @Notes: 获取端口
     *
     * @Time: 2025/11/12 14:22
     * @return string
     */
    public function getPort(): string
    {
        return $this->pathInfo['port']??'';
    }

    /**
     * @Notes: 获取查询参数
     *
     * @Time: 2025/11/12 14:22
     * @return string
     */
    public function getQuery(): string
    {
        return $this->pathInfo['query']??'';
    }

    /**
     * @Notes: 修改协议
     *
     * @Time: 2025/11/12 14:23
     * @param string $scheme
     * @return $this
     */
    public function withScheme(string $scheme): Path
    {
        if($scheme == $this->pathInfo['scheme']){
            return $this;
        }
        $new = clone $this;
        $new->pathInfo['scheme'] = $scheme;
        return $new;
    }

    /**
     * @Notes: 修改主机
     *
     * @Time: 2025/11/12 14:23
     * @param string $host
     * @return $this
     */
    public function withHost(string $host): Path
    {
        if($host == $this->pathInfo['host']){
            return $this;
        }
        $new = clone $this;
        $new->pathInfo['host'] = $host;
        return $new;
    }

    /**
     * @Notes: 修改端口
     *
     * @Time: 2025/11/12 14:23
     * @param string $port
     * @return $this
     */
    public function withPort(string $port): Path
    {
        if($port == $this->pathInfo['port']){
            return $this;
        }
        $new = clone $this;
        $new->pathInfo['port'] = $port;
        return $new;
    }

    /**
     * @Notes: 修改查询参数
     *
     * @Time: 2025/11/12 14:24
     * @param string $query
     * @return $this
     */
    public function withQuery(string $query): Path
    {
        if($query == $this->pathInfo['query']){
            return $this;
        }
        $new = clone $this;
        $new->pathInfo['query'] = !empty($new->pathInfo['query']) ? $new->pathInfo['query'] .'&'. $query : $query;
        return $new;
    }

    /**
     * @Notes: 获取完整路径，如已被修改返回修改后数据
     *
     * @Time: 2024/11/27 00:11
     * @return string
     */
    public function getFull(): string
    {
        $uri = '';
        if(!empty($this->pathInfo['scheme'])){
            $uri .= $this->pathInfo['scheme'];
        }
        if(!empty($this->pathInfo['host'])){
            $uri .= '://' . $this->pathInfo['host'];
        }
        if(!empty($this->pathInfo['port'])){
            $uri .= ':' . $this->pathInfo['port'];
        }
        if(!empty($uri)){
            $uri .= $this->dirSeparator;
        }
        $uri .= self::composeComponents($this->pathInfo, $this->dirSeparator);
        if(!empty($this->pathInfo['query'])){
            $uri .= '?' . $this->pathInfo['query'];
        }
        return $uri;
    }

    public function __toString(): string
    {
        return self::composeComponents($this->pathInfo, $this->dirSeparator);
    }

    public function toString(string $addPath = ''): string
    {
        return !empty($addPath) ? trim($addPath, '/') .$this->dirSeparator. $this->__toString() : $this->__toString();
    }

    public static function composeComponents(array $pathInfo, string $dirSeparator): string
    {
        $path = '';
        if(!empty($pathInfo['dirname'])){
            $path = implode($dirSeparator, $pathInfo['dirname']);
        }
        if(!empty($pathInfo['basename'])){
            if(!empty($path)){
                $path .= $dirSeparator . $pathInfo['basename'];
            }else{
                $path .= $pathInfo['basename'];
            }
        }
        return $path;
    }

    /**
     * @Notes: 创建目录
     *
     * @Time: 2024/2/6 16:22
     * @param string $filePath
     * @return void
     */
    public static function createDirs(string $filePath): void
    {
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
    }

    /**
     * @Notes: 获取文件名+扩展名
     *
     * @Time: 2024/11/11 22:11
     * @return string|null
     */
    public function getBasename(): ?string
    {
        return $this->pathInfo['basename'];
    }

    /**
     * @Notes: 获取文件扩展名
     *
     * @Time: 2024/11/11 22:12
     * @return string|null
     */
    public function getExtension(): ?string
    {
        return $this->pathInfo['extension'];
    }

    /**
     * @Notes: 获取文件名
     *
     * @Time: 2024/11/11 22:14
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->pathInfo['filename'];
    }

    /**
     * @Notes: 获取路径目录部分
     *
     * @Time: 2024/11/11 22:11
     * @return string
     */
    public function getPath(): string
    {
        return implode($this->dirSeparator, $this->pathInfo['dirname']);
    }

    /**
     * @Notes: 设置分隔符
     *
     * @Time: 2024/11/12 20:18
     * @param string $dirSeparator
     * @return $this
     */
    public function withDirSeparator(string $dirSeparator): Path
    {
        if ($this->dirSeparator == $dirSeparator) {
            return $this;
        }
        $new = clone $this;
        $new->dirSeparator = $dirSeparator;
        return $new;
    }

    /**
     * @Notes: 设置path追加目录，已存在目录进行替换
     *
     * @Time: 2024/11/11 23:04
     * @param string $directory
     * @return $this
     */
    public function withPath(string $directory): Path
    {
        $directory = trim(str_replace('\\', '/', $directory), '\\/');
        $directory = explode('/', $directory);
        $new = clone $this;
        foreach ($directory as $value){
            $new->pathInfo['dirname'][] = $value;
        }
        return $new;
    }

    /**
     * @Notes: 修改path部分
     *
     * @Time: 2024/11/11 22:37
     * @param string $path
     * @return $this
     */
    public function withPathAll(string $path): Path
    {
        $path = trim(str_replace('\\', '/', $path), '\\/');
        if (implode('/', $this->pathInfo['dirname']) == $path) {
            return $this;
        }
        $new = clone $this;
        $new->pathInfo['dirname'] = explode('/', $path);
        return $new;
    }

    /**
     * @Notes: 设置文件名+扩展名
     *
     * @Time: 2024/11/11 22:11
     * @param string $basename
     * @return Path
     */
    public function withBasename(string $basename): Path
    {
        $arr = explode('.', $basename);
        if($basename == $this->pathInfo['basename'] || empty($arr[1])){
            return $this;
        }
        $new = clone $this;
        $new->pathInfo['basename'] = $basename;
        $new->pathInfo['extension'] = $arr[1];
        return $new;
    }

    /**
     * @Notes: 设置文件扩展名
     *
     * @Time: 2024/11/11 22:12
     * @param string $extension
     * @return Path
     */
    public function withExtension(string $extension): Path
    {
        if($extension == $this->pathInfo['extension']){
            return $this;
        }
        $new = clone $this;
        $new->pathInfo['extension'] = $extension;
        $new->pathInfo['basename'] = $new->pathInfo['filename'] .'.'.$extension;
        return $new;
    }

    /**
     * @Notes: 设置文件名
     *
     * @Time: 2024/11/11 22:14
     * @param string $filename
     * @return Path
     */
    public function withFilename(string $filename): Path
    {
        if($filename == $this->pathInfo['filename']){
            return $this;
        }
        $new = clone $this;
        $new->pathInfo['filename'] = $filename;
        $new->pathInfo['basename'] = $filename .'.'. $new->pathInfo['extension'];
        return $new;
    }
}

