<?php

namespace IdnoPlugins\Pushover {

    class Main extends \Idno\Common\Plugin {

        function registerPages() {

            // Register an account menu
            \Idno\Core\site()->template()->extendTemplate('account/menu/items', 'account/pushover/menu');

            // Register the callback URL
            \Idno\Core\site()->addPageHandler('account/pushover', '\IdnoPlugins\Pushover\Pages\Account');
        }

        static function send($token, $user_token, $message) {
            
            $req = 'token=' . urlencode($token) . '&user=' . urlencode($user_token) . '&message=' . urlencode($message);

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
