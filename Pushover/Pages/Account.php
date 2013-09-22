<?php

    namespace IdnoPlugins\Pushover\Pages {

        /**
         * Default class to serve Facebook-related account settings
         */
        class Account extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->gatekeeper(); // Logged-in users only
                $t = \Idno\Core\site()->template();
                $body = $t->__(['login_url' => $login_url])->draw('account/pushover');
                $t->__(['title' => 'Pushover Notifications', 'body' => $body])->drawPage();
            }

            function postContent() {
                $this->gatekeeper(); // Logged-in users only
                
                
                $session = \Idno\Core\site()->session(); 
                $user = $session->currentUser(); 
                
                $user->pushover_user_token = $this->getInput('user_token');
                $user->pushover_app_token = $this->getInput('app_token');
                
                                
                if ($user->save())
                    \Idno\Core\site()->session()->addMessage('Pushover settings saved.');
                
                
                $this->forward('/account/pushover/');
            }

        }

    }