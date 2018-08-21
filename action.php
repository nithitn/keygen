<?php
/**
* This script used as a parent for all the request
* from the various pages.
* */
use action\model\useradd;
use action\model\keyadd;
use action\model\logadd;
use action\model\loginprocess;

class action {
    public $nn_action;
    public $nn_new=false;
    public $userAdd;
    public $keyAdd;
    public $logAdd;
    public $logIn;
    public function __construct() {
        
        $this->userAdd = new useradd();
        $this->keyAdd = new keyadd();
        $this->logAdd = new logadd();
        $this->logIn = new loginprocess();
        if (isset($_POST['nn_action'])) 
            $this->nn_action = $_POST['nn_action'];
        if (isset($_GET['nn_new'])) 
            $this->nn_new = $_GET['nn_new'];
        if ($this->nn_new) {
            $this->nn_action = base64_decode($_GET['nn_action']);
        }
        if ($this->nn_action == 'nn_login') {
            $this->loginprocess();
        } else if ($this->nn_action == 'logout') {
            $this->logoutprocess();
        } else if ($this->nn_action == 'user_add') {
            $this->useradds();
        } else if ($this->nn_action == 'user_edit') {
            $this->userupdates();
        } else if ($this->nn_action == 'key_add') {
            $this->keyadds();
        } else if ($this->nn_action == 'key_edit') {
            $this->keyupdates();
        } else if ($this->nn_action == 'del') {
            $id = base64_decode($_GET['askpys']);
            $this->userdeletes($id);
        } else if ($this->nn_action == 'del_key') {
            $id = base64_decode($_GET['askpys']);
            $this->keydeletes($id);
        } else if ($this->nn_action == 'del_log') {
            $id = base64_decode($_GET['askpys']);
            $this->logdeletes($id);
        } 
    }
    public static function my_autoloader($class) {
        $get_file = dirname(__DIR__).'/'.str_replace("\\",DIRECTORY_SEPARATOR, $class).'.php';
        if (file_exists($get_file)) {
            include $get_file;
        }
    }
    
    public function useradds() {
        $result = $this->userAdd ->sAdd();
        if ($result ) {
            header("location:../views/users.php?rs=succ&ac=ad");
        } else {
            header("location:../views/users.php?rs=fail&ac=ad");
        }
    }

    public function keyadds() {
        $result = $this->keyAdd ->sAdd();
        if ($result ) {
            header("location:../views/keys.php?rs=succ&ac=ad");
        } else {
            header("location:../views/keys.php?rs=fail&ac=ad");
        }
    }

    public function userupdates() {
        $result = $this->userAdd ->sUpdates();
        if ($result) {
            header("location:../views/users.php?rs=succ&ac=up");
        } else {
            header("location:../views/users.php?rs=fail&ac=up");
        }
    }

    public function keyupdates() {
        $result = $this->keyAdd ->sUpdates();
        if ($result) {
            header("location:../views/keys.php?rs=succ&ac=up");
        } else {
            header("location:../views/keys.php?rs=fail&ac=up");
        }
    }

    public function userdeletes($id) {
        $result = $this->userAdd ->sDeletes($id);
        if ($result) {
            header("location:../views/users.php?rs=succ&ac=del");
        } else {
            header("location:../views/users.php?rs=fail&ac=up");
        }
    }

    public function keydeletes($id) {
        $result = $this->keyAdd ->sDeletes($id);
        if ($result) {
            header("location:../views/keys.php?rs=succ&ac=del");
        } else {
            header("location:../views/keys.php?rs=fail&ac=up");
        }
    }

    public function logdeletes($id) {
        $result = $this->logAdd ->sDeletes($id);
        if ($result) {
            header("location:../views/logs.php?rs=succ&ac=del");
        } else {
            header("location:../views/logs.php?rs=fail&ac=up");
        }
    }

    public function getUsers() {
        $result = $this->userAdd->sGet();
        return $result;
    }

    public function getKeys() {
        $result = $this->keyAdd->sGet();
        return $result;
    }

    public function getLogs() {
        
        $result = $this->logAdd->sGet();
        return $result;
    }
    
    public function getUser($id) {
        $result = $this->userAdd ->sGetOne($id);
        return $result;
    }

    public function getKey($id) {
        $result = $this->keyAdd ->sGetOne($id);
        return $result;
    }

    public function loginprocess() {
        $result = $this->logIn ->slogin();
        if ($result) {
            header("location:../views/keys.php");
        } else {
            header("location:../index.php?rs=fail");
        }
    }

    public function logoutprocess() {
        session_start();
        unset($_SESSION['user_id']);
        session_destroy();
        header("location:../index.php");
    }

    public function chkses() {
        session_start();
        if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] == '') {
            header("location:../index.php");
        }
    }
}
spl_autoload_register(array('action', 'my_autoloader'));
new action();

//New line

//latest change

?>
