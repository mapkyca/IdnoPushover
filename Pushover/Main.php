<?php

namespace IdnoPlugins\Pushover {

    class Main extends \Idno\Common\Plugin {

        function registerPages() {

            // Register an account menu
            \Idno\Core\site()->template()->extendTemplate('account/menu/items', 'account/pushover/menu');

            // Register the callback URL
            \Idno\Core\site()->addPageHandler('account/pushover', '\IdnoPlugins\Pushover\Pages\Account');
        }
        
        function registerEventHooks() {
            parent::registerEventHooks();
            
            \Idno\Core\site()->addEventHook('annotation/add/reply', '\IdnoPlugins\Pushover\Main::annotate');
        }
        
        static function annotate(\Idno\Core\Event $event) {
            $object = $event->data()['object'];
            $annotation = $event->data()['annotation'];
            
            if ($user = $object->getOwner() )
            {
                $session = \Idno\Core\site()->session(); 
                $logged_in_user = $session->currentUser(); 
                
                if ((empty($logged_in_user)) || ($user->getUUID() != $logged_in_user->getUUID())) {
                    
                    $user_token = $user->pushover_user_token;
                    $token = $user->pushover_app_token;
                    $title = $object->getTitle();
                    $url = $object->getUrl();
                    
                    if (!empty($user_token) && !empty($token)) {

                        // Not replying to my own stuff, so we can send a pushover message
                        switch ($event->getName()) {

                            case 'annotation/add/reply' :
                                    self::send($token, $user_token, $annotation[owner_name] . " has left you a comment!", ['title' => $title, 'url' => $url]);
                                break;
                        }
                        
                    }
                }
                
                
            }
            
        }

        static function send($token, $user_token, $message, array $params = null) {
            
            $req = 'token=' . urlencode($token) . '&user=' . urlencode($user_token) . '&message=' . urlencode($message);
            if ($params) {
                foreach ($params as $key => $value)
                    $req .= "&$key=" . urlencode($value);
            }
            

            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, 'https://api.pushover.net/1/messages.json');
            curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_handle, CURLOPT_USERAGENT, "idno pingback client");
            curl_setopt($curl_handle, CURLOPT_POST, 1);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $req);
            curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 2);

            $buffer = curl_exec($curl_handle);
            $http_status = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);

            curl_close($curl_handle);
            
            return ['content' => json_decode($buffer), 'status' => $http_status];
        }

    }

}
