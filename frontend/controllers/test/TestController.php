<?php
namespace frontend\controllers\test;

use common\controllers\BasicController;
use CliGuy\GeneratorSteps;

/**
 * 
 * @author wei.w.zhou@integle.com
 *
 * 2017年12月10日上午9:58:17
 */
class TestController extends BasicController{
    
    public $enableCsrfValidation = false;
    
    public function actionIndex() {
        $file = 'D:\tools\1.txt';
        $handle1 = fopen($file, 'wb');
        if (flock($handle1, LOCK_EX)) {
            echo 'lock success1';
        }
        
//         $handle2 = fopen($file, 'wb');
//         if (flock($handle2, LOCK_EX)) {
//             echo 'lock success2';
//         }
        
        fwrite($handle1, "cacasdsds");
//         fwrite($handle2, "23434234");
        
        var_dump($handle1, $handle2);
        die;
        $person = new Person();
        
        $person['name'] = 'caca';
        
        var_dump(isset($person['name']));
        
        var_dump(isset($person->gogo));
        
        unset($person['name']);
        
        var_dump($person['name']);
    }
    
    //php序列化
    public function actionSeri() {
        $person = new Person('person', 'man', 'hello world');
        
        $str = serialize($person);
        
        \Yii::info('serialized str:'.$str, __METHOD__);
        
        $p = unserialize($str);
        
//         unset($p);
//         unset($person);
        var_dump($p);
        die;
        
    }
    
    //php安全措施
    public function actionSecuty() {
        $base = 'd:/home/wwwroot/';
        $file = '../../etc/passwd';
        $f = $base.$file;
        
        var_dump(realpath($f));
        
        $file = "../../etc/passwd\0";
        
        $csr = 'abcd sdf';
        echo urlencode($csr), '<br/>';
        echo rawurlencode($csr);
        
        $str = "abcdef.txt\0jgk".$file.".php";
        $str = str_replace(chr(0), '', $str);
        
        include($str);
        
//         var_dump($str);die;
    }
    
    //php魔术引号和转义
    public function actionMagic() {
        $sql = "select * \"from \0 ' \\integle_e_order'";
        
        $sql1 = addslashes($sql);
        $sql2 = stripslashes($sql1);
        var_dump($sql);
        var_dump($sql1);
        var_dump($sql);
    }
    
    //php认证
    public function actionAuth() {
//         header('HTTP/1.0 401 Unauthorized');
//         echo 'caca';die;
var_dump($_SERVER);die;
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Text to send if user hits Cancel button';
            exit;
        } else {
            echo '<p>user：'.$_SERVER['PHP_AUTH_USER'].'</p>';
            echo '<p>password:'.$_SERVER['PHP_AUTH_PW'].'</p>';
        }
    }
    
    public function actionUpload() {
        //正对put的文件上传
        /* $come = fopen('php://input', 'rb');
        $out = fopen('D:\tools\111.jpg', 'wb');
        $index = stream_copy_to_stream($come, $out);
        fclose($come);
        fclose($out);
        var_dump($index);die; */
        
        if (empty($_FILES)) {
            echo <<<EOF
    <form action="/test/test/upload" method="post" enctype="multipart/form-data">
        <input type="file" name="f" />
        <button type="submit">提交</button>
    </form>
EOF;
        } else {
            var_dump($_FILES);
            var_dump($_POST);
            copy($_FILES['f']['tmp_name'], 'D:\tools\112.jpg');
            if ($_FILES['f']['error'] == UPLOAD_ERR_OK) {
                echo '文件上传成功';
            } elseif ($_FILES['f']['error'] == UPLOAD_ERR_FORM_SIZE) {
                echo '文件超过了MAX_FILE_SIZE指定大小';
            } elseif ($_FILES['f']['error'] == UPLOAD_ERR_NO_FILE) {
                echo '没有文件上传';
            }
        }
    }
    
    //连接处理
    public function actionConnection() {
        set_time_limit(5);
        register_shutdown_function(function(){
            echo '麻痹的中断了:'.connection_status();
            \Yii::info('麻痹的中断了:'.connection_status(), __METHOD__);
        });
        
//         ignore_user_abort(true);
        
        try {
            for($i=1;$i<30;$i++){
                \Yii::info('caca:'.$i, __METHOD__);
                sleep(1);
                echo $i.',';
            }
        } catch (\Exception $e) {
            return;
        }
    }
    
    public function actionCounter() {
    }
}

