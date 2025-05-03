<?php

function get($route,$callback){
    Application::register($route,$callback,"GET");
}

function post($route,$callback){
    Application::register($route,$callback,"POST");
}

function put($route,$callback){
    Application::register($route,$callback,"PUT");
}

function delete($route,$callback){
    Application::register($route,$callback,"DELETE");
}

function resolve(){
	Application::resolve();
}

class Application{
    private static $instance;
    private static $route_found = false;
    private $route = "";

    private $messages = array();
    private $method = "";

    public static function get_instance(){
        if(!isset(static::$instance)){
            static::$instance = new Application();
        }
        return static::$instance;
    }
    
    protected function __construct(){
        $this->route = $this->get_route();
        $this->method = $this->get_method();
    }
    
    public function get_route(){
      return $_SERVER['REQUEST_URI'];  
    }
    
    public static function register($route,$callback, $method){
        $application = static::get_instance();
        if($route == $application->route && !static::$route_found && $application->method == $method){
            static::$route_found = true;
            echo $callback($application);
        }
        else{
            return false;
        }
    }  

    public function render($layout, $content){

        foreach($this->messages As $key => $val){
             $$key = $val;
        }
        
 
        $flash = $this->get_flash();
 
        $content = VIEWS."/{$content}.php";
 
        if(!empty($layout)){
           require VIEWS."/{$layout}.layout.php";
        }
        else{
           // What is this part for? When would we not need a layout? Think about it.
        }
        exit();
     } 

     public function get_request(){
        return $_SERVER['REQUEST_URI'];
      }
  
      public function force_to_https($path="/"){
          //ignore as we are not using SSL
      }
  
      public function force_to_http($path="/"){
          if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on'){
             $host = $_SERVER['HTTP_HOST'];
             $redirect_to_path = "http://".$host.$path;
             $this->redirect_to($redirect_to_path);
             exit();
          }
      }
  
      public function get_method(){
         if(strtoupper($this->form("_method")) === "POST"){
            return "POST";
         }
         if(strtoupper($this->form("_method")) === "PUT"){
            return "PUT";
         }
         if(strtoupper($this->form("_method")) === "DELETE"){
            return "DELETE";
         }
         return "GET";
      }
  
      public function form($key){
         if(!empty($_POST[$key])){
            return $_POST[$key];
         }
         return false;
      }
  
      public function redirect_to($path="/"){
         header("Location: {$path}");
         exit();
      }
  
      public function set_session_message($key,$message){
         if(!empty($key) && !empty($message)){
            session_start();
            $_SESSION[$key] = $message;
            session_write_close();
         }
      }
  
      public function get_session_message($key){
         $msg = "";
         if(!empty($key) && is_string($key)){
            session_start();
            if(!empty($_SESSION[$key])){
               $msg = $_SESSION[$key];
               unset($_SESSION[$key]);
            }
            session_write_close();
         }
         return $msg;
      }
  
      public function set_flash($msg){
            $this->set_session_message("flash",$msg);
      }
  
      public function get_flash(){
            return $this->get_session_message("flash");   
      }
      
      public function set_message($key,$value){
        $this->messages[$key] = $value;
      }
  
      public static function resolve(){
        if(!static::$route_found){
          $application = static::get_instance();
          header("HTTP/1.0 404 Not Found");
          $application->render("standard","404");	
        }
      }
}