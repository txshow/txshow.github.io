<?php
/**
 * 程序名称: PHP探针
 * 程序功能: 探测系统的Web服务器运行环境
 * 程序开发: 浪子不归(fbcha)
 * 联系方式: fbcha@163.com
 * 博    客: https://my.oschina.net/fbcha/blog
 * Date: 2016-09-18
 */
error_reporting(0);
$title = "PHP探针 ";
$version = "v1.0 Beta";

if($_GET['act'] == 'phpinfo')
{
    phpinfo();
    exit();
}

$getServerHosts = get_current_user() . '/' . $_SERVER['SERVER_NAME'] . '(' . gethostbyname($_SERVER["SERVER_NAME"]) . ')'; // 获取服务器域名/ip
$getServerOS = PHP_OS . ' ' . php_uname('r'); // 获取服务器操作系统
$getServerSoftWare = $_SERVER["SERVER_SOFTWARE"]; // 获取服务器类型和版本
$getServerLang = getenv("HTTP_ACCEPT_LANGUAGE"); // 获取服务器语言
$getServerPort = $_SERVER['SERVER_PORT']; // 获取服务器端口
$getServerHostName = php_uname('n'); // 获取服务器主机名
$getServerAdminMail = $_SERVER['SERVER_ADMIN']; // 获取服务器管理员邮箱
$getServerTzPath = __FILE__; // 获取探针路径
// 检查true or false
function checkstatus($status)
{
    if (false == $status)
    {
        $out = '<i class="sui-icon icon-pc-error sui-text-danger"></i>';
    } else
    {
        $out = '<i class="sui-icon icon-pc-right sui-text-success"></i>';
    }
    return $out;
}

// 判断php参数
function isinit($var)
{
    switch ($var)
    {
        case 'version':
            $out = PHP_VERSION;
            break;
        case 'sapi':
            $out = php_sapi_name();
            break;
        case 'cookie':
            $out = checkstatus(isset($_COOKIE));
            break;
        case 'issmtp':
            $out = checkstatus(get_cfg_var("SMTP"));
            break;
        case 'SMTP':
            $out = get_cfg_var("SMTP");
            break;
        default:
            $out = getini($var);
            break;
    }
    return $out;
}

// 获取php参数信息
function getini($var)
{
    $conf = get_cfg_var($var);
    switch ($conf)
    {
        case 0:
            $out = checkstatus(0);
            break;
        case 1:
            $out = checkstatus(1);
            break;
        default :
            $out = $conf;
            break;
    }

    return $out;
}

// 检测函数支持
function isfunction($funname = '')
{
    if (!checkFunction($funname))
        return "函数错误！";
    return checkstatus(function_exists($funname));
}

// 检测函数规范
function checkFunction($funname = '')
{
    return ($funname == '') ? false : true;
}

// 禁用的函数
function disableFunction()
{
    $fun = get_cfg_var("disable_functions");

    if (empty($fun))
    {
        $out = checkstatus($fun);
    } else
    {
        $funs = explode(',', $fun);

        $tag = '<ul class="sui-tag ext-tag-font">';
        foreach ($funs as $k => $v)
        {
            $tag .= '<li>' . $v . '</li>';
        }
        $out = $tag . '</ul>';
    }
    return $out;
}

// php扩展
function isExt($ext)
{
    switch ($ext)
    {
        case 'gd_info':
            $is_gd = extension_loaded("gd");
            if($is_gd)
            {
                $gd = gd_info();
                $out = $gd["GD Version"];
            }else{
                $out = checkstatus($is_gd);
            }
            break;
        case 'sqlite3':
            $is_sqlite3 = extension_loaded("sqlite3");
            if($is_sqlite3)
            {
                $sqlite3 = SQLite3::version();
                $out = $sqlite3['versionString'];
            }else{
                $out = checkstatus($is_sqlite3);
            }
            break;
            
    }
    return $out;
}

// php已编译模块
function loadExt()
{
    $exts = get_loaded_extensions();
    if ($exts)
    {
        $tag = '<ul class="sui-tag ext-tag-font">';
        foreach ($exts as $k => $v)
        {
            $tag .= '<li>' . $v . '</li>';
        }
        $out = $tag . '</ul>';
    }else{
        $out = checkstatus($exts);
    }
    return $out;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title . $version; ?></title>
        <link href="http://g.alicdn.com/sj/dpl/1.5.1/css/sui.min.css" rel="stylesheet" />
        <script type="text/javascript" src="http://g.alicdn.com/sj/lib/jquery.min.js"></script>
        <script type="text/javascript" src="http://g.alicdn.com/sj/dpl/1.5.1/js/sui.min.js"></script>
        <style>
            body{font-size: 1.25vw;}
            .stxt{font-size: 1vw;color: #666;}
            .footer{margin-top: 20px;border-top: 3px #ccc solid;padding: 20px; text-align: center;}
            .ext-tag-font li{font-size: 1.1vw;}
            .beta{font-size: 1vw;color: #ccc}
        </style>
    </head>
    <body>
        <div class="sui-container">
            <div class="sui-navbar">
                <div class="navbar-inner">
                    <a href="" class="sui-brand">PHP探针</a>
                    <span class="beta">Beta</span>

                    <ul class="sui-nav pull-right">
                        <li><a href="https://my.oschina.net/fbcha/blog/748251" target="_blank">新版下载</a></li>
                    </ul>
                </div>
            </div>
            <div class="sui-content">
                <table class="sui-table table-bordered table-primary">
                    <thead>
                        <tr>
                            <th colspan="4">
                                <span class="">
                                    服务器基本主息
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="20%">
                                服务器域名/IP地址
                            </td>
                            <td>
                                <?php echo $getServerHosts; ?>
                            </td>
                            <td>
                                服务器操作系统
                            </td>
                            <td>
                                <?php echo $getServerOS; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                服务器解译引擎
                            </td>
                            <td>
                                <?php echo $getServerSoftWare; ?>
                            </td>
                            <td width="20%">
                                服务器语言
                            </td>
                            <td>
                                <?php echo $getServerLang; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                服务器端口
                            </td>
                            <td>
                                <?php echo $getServerPort; ?>
                            </td>
                            <td>
                                服务器主机名
                            </td>
                            <td>
                                <?php echo $getServerHostName; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                管理员邮箱
                            </td>
                            <td>
                                <?php echo $getServerAdminMail; ?>
                            </td>
                            <td>
                                探针路径
                            </td>
                            <td>
                                <?php echo $getServerTzPath; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="sui-table table-bordered table-primary">
                    <thead>
                        <tr>
                            <th colspan="4">
                                PHP基本参数
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="30%">
                                PHP版本 <span class="stxt">php_version</span>
                            </td>
                            <td width="20%">
                                <?php echo isinit("version"); ?>
                            </td>
                            <td width="30%">
                                PHP运行方式
                            </td>
                            <td width="20%">
                                <?php echo isinit('sapi'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                脚本占用最大内存 <span class="stxt">memory_limit</span>
                            </td>
                            <td>
                                <?php echo isinit('memory_limit'); ?>
                            </td>
                            <td>
                                PHP安全模式 <span class="stxt">safe_mode</span>
                            </td>
                            <td>
                                <?php echo isinit('safe_mode'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                POST方法提交最大限制 <span class="stxt">post_max_size</span>
                            </td>
                            <td>
                                <?php echo isinit('post_max_size'); ?>
                            </td>
                            <td>
                                上传文件最大限制 <span class="stxt">upload_max_filesize</span>
                            </td>
                            <td>
                                <?php echo isinit('upload_max_filesize'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                浮点型数据显示的有效位数 <span class="stxt">precision</span>
                            </td>
                            <td>
                                <?php echo isinit('precision'); ?>
                            </td>
                            <td>
                                脚本超时时间 <span class="stxt">max_execution_time</span>
                            </td>
                            <td>
                                <?php echo isinit('max_execution_time'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                socket超时时间 <span class="stxt">default_socket_timeout</span>
                            </td>
                            <td>
                                <?php echo isinit('default_socket_timeout'); ?>
                            </td>
                            <td>
                                PHP页面根目录 <span class="stxt">doc_root</span>
                            </td>
                            <td>
                                <?php echo isinit('doc_root'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                用户根目录 <span class="stxt">user_dir</span>
                            </td>
                            <td>
                                <?php echo isinit('user_dir'); ?>
                            </td>
                            <td>
                                dl()函数 <span class="stxt">enable_dl</span>
                            </td>
                            <td>
                                <?php echo isinit('enable_dl'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                指定包含文件目录 <span class="stxt">include_path</span>
                            </td>
                            <td>
                                <?php echo isinit('include_path'); ?>
                            </td>
                            <td>
                                显示错误信息 <span class="stxt">display_errors</span>
                            </td>
                            <td>
                                <?php echo isinit('display_errors'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                自定义全局变量 <span class="stxt">register_globals</span>
                            </td>
                            <td>
                                <?php echo isinit('register_globals'); ?>
                            </td>
                            <td>
                                数据反斜杠转义 <span class="stxt">magic_quotes_gpc</span>
                            </td>
                            <td>
                                <?php echo isinit('magic_quotes_gpc'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                "&lt;?...?&gt;"短标签 <span class="stxt">short_open_tag</span>
                            </td>
                            <td>
                                <?php echo isinit('short_open_tag'); ?>
                            </td>
                            <td>
                                "&lt;%...%&gt;"ASP风格标记 <span class="stxt">asp_tags</span>
                            </td>
                            <td>
                                <?php echo isinit('asp_tags'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                忽略重复错误信息 <span class="stxt">ignore_repeated_errors</span>
                            </td>
                            <td>
                                <?php echo isinit('ignore_repeated_errors'); ?>
                            </td>
                            <td>
                                忽略重复的错误源 <span class="stxt">ignore_repeated_source</span>
                            </td>
                            <td>
                                <?php echo isinit('ignore_repeated_source'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                报告内存泄漏 <span class="stxt">report_memleaks</span>
                            </td>
                            <td>
                                <?php echo isinit('report_memleaks'); ?>
                            </td>
                            <td>
                                自动字符串转义 <span class="stxt">magic_quotes_gpc</span>
                            </td>
                            <td>
                                <?php echo isinit('magic_quotes_gpc'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                外部字符串自动转义 <span class="stxt">magic_quotes_runtime</span>
                            </td>
                            <td>
                                <?php echo isinit('magic_quotes_runtime'); ?>
                            </td>
                            <td>
                                打开远程文件 <span class="stxt">allow_url_fopen</span>
                            </td>
                            <td>
                                <?php echo isinit('allow_url_fopen'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                声明argv和argc变量 <span class="stxt">register_argc_argv</span>
                            </td>
                            <td>
                                <?php echo isinit('register_argc_argv'); ?>
                            </td>
                            <td>
                                Cookie 支持
                            </td>
                            <td>
                                <?php echo isinit('cookie'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                拼写检查 <span class="stxt">ASpell Library</span>
                            </td>
                            <td>
                                <?php echo isfunction("aspell_check_raw"); ?>
                            </td>
                            <td>
                                高精度数学运算 <span class="stxt">BCMath</span>
                            </td>
                            <td>
                                <?php echo isfunction("bcadd"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                PREL相容语法 <span class="stxt">PCRE</span>
                            </td>
                            <td>
                                <?php echo isfunction("preg_match"); ?>
                            </td>
                            <td>
                                PDF文档支持
                            </td>
                            <td>
                                <?php echo isfunction("pdf_close"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                SNMP网络管理协议
                            </td>
                            <td>
                                <?php echo isfunction("snmpget"); ?>
                            </td>
                            <td>
                                Curl支持
                            </td>
                            <td>
                                <?php echo isfunction("curl_init"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                SMTP支持
                            </td>
                            <td>
                                <?php echo isinit("issmtp"); ?>
                            </td>
                            <td>
                                SMTP地址
                            </td>
                            <td>
                                <?php echo isinit('SMTP'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                被禁用的函数
                            </td>
                            <td colspan="3">
                                <?php echo disableFunction(); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                PHP信息
                            </td>
                            <td colspan="3">
                                <a href="?act=phpinfo" target="_blank" class="sui-btn btn-xlarge btn-primary">PHPINFO</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="sui-table table-bordered table-primary">
                    <thead>
                        <tr>
                            <th>
                                PHP已编译模块
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo loadExt(); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="sui-table table-bordered table-primary">
                    <thead>
                        <tr>
                            <th colspan="4">组件支持</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="30%">
                                FTP支持
                            </td>
                            <td width="20%">
                                <?php echo isfunction("ftp_login"); ?>
                            </td>
                            <td width="30%">
                                XML解析支持
                            </td>
                            <td width="20%">
                                <?php echo isfunction("xml_set_object"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Session支持
                            </td>
                            <td>
                                <?php echo isfunction("session_start"); ?>
                            </td>
                            <td>
                                Socket支持
                            </td>
                            <td>
                                <?php echo isfunction("socket_accept"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Calendar支持
                            </td>
                            <td>
                                <?php echo isfunction("cal_days_in_month"); ?>
                            </td>
                            <td>
                                允许URL打开文件
                            </td>
                            <td>
                                <?php echo isinit("allow_url_fopen"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                GD库支持
                            </td>
                            <td>
                                <?php echo isExt("gd_info"); ?>
                            </td>
                            <td>
                                压缩文件支持(Zlib)
                            </td>
                            <td>
                                <?php echo isfunction("gzclose"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                IMAP电子邮件系统函数库
                            </td>
                            <td>
                                <?php echo isfunction("imap_close"); ?>
                            </td>
                            <td>
                                历法运算函数库
                            </td>
                            <td>
                                <?php echo isfunction("JDToGregorian"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                正则表达式函数库
                            </td>
                            <td>
                                <?php echo isfunction("preg_match"); ?>
                            </td>
                            <td>
                                WDDX支持
                            </td>
                            <td>
                                <?php echo isfunction("wddx_add_vars"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Iconv编码转换
                            </td>
                            <td>
                                <?php echo isfunction("iconv"); ?>
                            </td>
                            <td>
                                mbstring
                            </td>
                            <td>
                                <?php echo isfunction("mb_eregi"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                高精度数学运算
                            </td>
                            <td>
                                <?php echo isfunction("bcadd"); ?>
                            </td>
                            <td>
                                LDAP目录协议
                            </td>
                            <td>
                                <?php echo isfunction("ldap_close"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                MCrypt加密处理
                            </td>
                            <td>
                                <?php echo isfunction("mcrypt_cbc"); ?>
                            </td>
                            <td>
                                哈稀计算
                            </td>
                            <td>
                                <?php echo isfunction("mhash_count"); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="sui-table table-bordered table-primary">
                    <thead>
                        <tr>
                            <th colspan="4">数据库支持</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="30%">MySQL 数据库</td>
                            <td width="20%"><?php echo isfunction("mysql_close"); ?></td>
                            <td width="30%">ODBC 数据库</td>
                            <td width="20%"><?php echo isfunction("odbc_close"); ?></td>
                        </tr>
                        <tr>
                            <td>Oracle 数据库</td>
                            <td><?php echo isfunction("ora_close"); ?></td>
                            <td>SQL Server 数据库</td>
                            <td><?php echo isfunction("mssql_close"); ?></td>
                        </tr>
                        <tr>
                            <td>dBASE 数据库</td>
                            <td><?php echo isfunction("dbase_close"); ?></td>
                            <td>mSQL 数据库</td>
                            <td><?php echo isfunction("msql_close"); ?></td>
                        </tr>
                        <tr>
                            <td>SQLite 数据库</td>
                            <td><?php echo isExt("sqlite3"); ?></td>
                            <td>Hyperwave 数据库</td>
                            <td><?php echo isfunction("hw_close"); ?></td>
                        </tr>
                        <tr>
                            <td>Postgre SQL 数据库</td>
                            <td><?php echo isfunction("pg_close"); ?></td>
                            <td>Informix 数据库</td>
                            <td><?php echo isfunction("ifx_close"); ?></td>
                        </tr>
                        <tr>
                            <td>DBA 数据库</td>
                            <td><?php echo isfunction("dba_close"); ?></td>
                            <td>DBM 数据库</td>
                            <td><?php echo isfunction("dbmclose"); ?></td>
                        </tr>
                        <tr>
                            <td>FilePro 数据库</td>
                            <td><?php echo isfunction("filepro_fieldcount"); ?></td>
                            <td>SyBase 数据库</td>
                            <td><?php echo isfunction("sybase_close"); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="footer">
                <a href="https://my.oschina.net/fbcha/blog/748251" target="_blank">PHP探针v1.0 测试版</a>
            </div>
        </div>
    </body>
</html>
