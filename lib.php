<?php
#基础
function prt($wb){ //打印函数 prt（要打印的内容）
    echo $wb;
}
function iff($condition, $ifTrue, $ifFalse = null) {   //判断控制流
    if ($condition) {  
        if (is_callable($ifTrue)) {  
            call_user_func($ifTrue);  
        } else {  
            throw new InvalidArgumentException('$ifTrue must be callable');  
        }  
    } elseif ($ifFalse !== null) {  
        if (is_callable($ifFalse)) {  
            call_user_func($ifFalse);  
        } else {  
            throw new InvalidArgumentException('$ifFalse must be callable');  
        }  
    }  
}  
function zurl($url){  //重定向
    header("Location:".$url);  
    exit;
}
function base64a($zzzfc){   //字符串转base64编码
    $zzzfc2 = base64_encode($zzzfc);
    return $zzzfc2;
}
function base64b($zzzfc2){   //base64转字符串
    $zzzfc23 = base64_decode($zzzfc2);
    return $zzzfc23;
}
#扩展
function wynote($websitelink2){ //获取微云笔记内容 参数：微云笔记链接，不要带密码
    $url = $websitelink2;  
    $ch = curl_init($url);   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'); 
    $html = curl_exec($ch);   
    if (curl_errno($ch)) {  
        $error_msg = curl_error($ch);  
        echo "cURL Error: " . $error_msg;  
    } else {   
        curl_close($ch);   
        header('Content-Type: text/html; charset=utf-8');    
        $pattern = '/\"note_title\":\"(-?[\d\|]+)\"/'; 
        if (preg_match($pattern, $html, $matches)) {  
            $noteTitle = $matches[1]; 
            return $noteTitle;  
        } else {  
            echo "错误代码：0394-B";  
        }  
    }  
}
function wz_pc($websitelink){ //爬网站源代码 参数：网站url
        $url = $websitelink;  
        $ch = curl_init($url);   
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'); 
        $html = curl_exec($ch);   
        if (curl_errno($ch)) {  
            $error_msg = curl_error($ch);  
            echo "cURL Error: " . $error_msg;  
        } else {   
            curl_close($ch);   
            header('Content-Type: text/html; charset=utf-8');    
            return htmlspecialchars($html);
        }  
}
function wb_zc($code){ //去除代码中的注释 参数：your code
    $code = preg_replace('/\/\/.*$/m', '', $code);  
    return htmlspecialchars($code); 
}
function get_ip() { //获取IP地址
    $ip=$_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}#s',$_SERVER['HTTP_X_FORWARDED_FOR'],$matches)) {
        foreach($matches[0] as $xip) {
            if (!preg_match('#^(10|172\\.16|192\\.168)\\.#',$xip)) {
                $ip=$xip;
            } else {
                continue;
            }
        }
    } else {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\\.){3}[0-9]{1,3}$/',$_SERVER['HTTP_CLIENT_IP'])) {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (isset($_SERVER['HTTP_CF_connbECTING_IP']) && preg_match('/^([0-9]{1,3}\\.){3}[0-9]{1,3}$/',$_SERVER['HTTP_CF_connbECTING_IP'])) {
                $ip=$_SERVER['HTTP_CF_connbECTING_IP'];
            } else {
                if ((isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\\.){3}[0-9]{1,3}$/',$_SERVER['HTTP_X_REAL_IP']))) {
                    $ip=$_SERVER['HTTP_X_REAL_IP'];
                }
            }
        }
    }
    return $ip;
}
function get_wz(){ //获取当前网站URL
    $fullUrl = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
    $fullUrl .= $_SERVER['HTTP_HOST'];  
    $fullUrl .= $_SERVER['REQUEST_URI'];  
    $protocolDomain = parse_url($fullUrl, PHP_URL_SCHEME) . '://' . parse_url($fullUrl, PHP_URL_HOST) . '/';  
    return $protocolDomain;
} 
function wz_xn($url,$csnumi){ //网站模拟访问1代（两个参数，网址|模拟访问次数）
    $concurrentUsers = $csnumi;  
    $multiHandle = curl_multi_init();  
    $curlHandles = [];  
    for ($i = 0; $i < $concurrentUsers; $i++) {  
        $ch = curl_init($url);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        curl_multi_add_handle($multiHandle, $ch);  
        $curlHandles[] = $ch;  
    }  
    $running = null;  
    do {  
        curl_multi_exec($multiHandle, $running);  
        curl_multi_select($multiHandle);  
    } while ($running > 0);   
    $responses = [];  
    foreach ($curlHandles as $ch) {  
        $responses[] = curl_multi_getcontent($ch);  
        curl_multi_remove_handle($multiHandle, $ch);  
        curl_close($ch);  
        echo "请求至{$url}成功\n";  
    }
    curl_multi_close($multiHandle);
}
function wz_xn2($url, $concurrentUsers, $requestsPerUser, $thinkTime = 1000) {  //网站模拟访问二代（三个参数，网址|模拟用户数|每个用户请求数）
    $multiHandle = curl_multi_init();  
    $curlHandles = [];  
    $running = 0;  
    $totalRequests = 0;  
    while ($totalRequests < $concurrentUsers * $requestsPerUser) {   
        while (($execrun = curl_multi_exec($multiHandle, $running)) == CURLM_CALL_MULTI_PERFORM);  
        if ($execrun != CURLM_OK) {  
            break;  
        }  
        if ($running > 0) {  
            curl_multi_select($multiHandle);  
        }   
        usleep($thinkTime);    
        while (($running < $concurrentUsers) && ($totalRequests < $concurrentUsers * $requestsPerUser)) {  
            $ch = curl_init($url);  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  
            curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
  
            curl_multi_add_handle($multiHandle, $ch);  
            $curlHandles[] = $ch;  
            $running++;  
            $totalRequests++;  
        }  
        do {  
            $status = curl_multi_info_read($multiHandle);  
            if ($status !== false) {  
                $ch = $status['handle'];  
                $content = curl_multi_getcontent($ch);  
                curl_multi_remove_handle($multiHandle, $ch);  
                curl_close($ch);  
                echo "请求至{$url}成功\n";  
            }  
        } while ($status !== false);  
    }  
    curl_multi_close($multiHandle);  
}  
function wz_ys($wzcesf){ //检测网站延时（访问速度）参数：网站url
    $ch = curl_init();  
    $url = $wzcesf; 
    curl_setopt($ch, CURLOPT_URL, $url);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
    curl_setopt($ch, CURLOPT_HEADER, true);   
    curl_setopt($ch, CURLOPT_NOBODY, true); 
    $startTime = microtime(true);  
    curl_exec($ch);  
    $endTime = microtime(true);  
    $timeTaken = $endTime - $startTime;  
    curl_close($ch);  
    return $timeTaken;
}
?>