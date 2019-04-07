<?php
    class Application{
        protected $controller = "HomeController";
        protected $action = "IndexAction";
        protected $paramters = array();

        public function __construct(){
            $this -> ParseURL();
            //Eğer controller dosyası varsa dosyayı dahil et
            if(file_exists(CONTROLLER.$this->controller.".php")){
                require_once (CONTROLLER.$this->controller.".php");
                //Dahil edilen controller sınıfından yeni nesne türetildi.
                $this -> controller = new $this -> controller;

                if(method_exists($this->controller,$this->action)){
                    call_user_func_array([$this->controller,$this->action],$this->paramters);
                }
                else
                    echo "Böyle bir action yok!";
            }
            else
                echo "Böyle bir controller yok!";
        }

        /*
         * ParseURL metodu genel mantığı ile şu işlemleri yapar:
         * $_SERVER['REQUEST_URI'] yardımı ile istemci tarafından gönderilen URL yakalanır.
         *
         * trim() fonksiyonuyla URL sonunda / bulunursa temizlenir.
         *
         * explode() fonksiyonuyla URL "/" karakterine göre dizileştirilebilir.
         *
         * $url değişkeni bir dizi olur. [0] => COntroller adı, [1] Action ADı , [2] ve sonrası => Parametreler
         *
         * unset() fonksiyonu ile $url değişkeninde varsa [0] ve [1] indis numaralı elemanlar temizlenir.
         * Geriye kalan değerler parametrelerdir.
         *
         *
         * */

        protected function ParseURL(){
            $request = trim($_SERVER["REQUEST_URI"],"/");
            if(!empty($request)){
                $url = explode("/",$request);
                $this->controller = isset($url[0]) ? $url[0]."Controller" : "HomeController";
                $this ->action = isset($url[1]) ? $url[1]."Action" : "IndexAction";
                unset($url[0],$url[1]);
                $this -> parameters = !empty($url) ? array_values($url) : array();
            }
            else{
                $this->controller = "HomeController";
                $this->action = "IndexAction";
                $this->paramters = array();
            }
        }
    }
?>