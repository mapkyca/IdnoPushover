<?php

$session = \Idno\Core\site()->session(); 
$user = $session->currentUser();
                            
?>
<div class="row">

    <div class="span10 offset1">
        <h1>Pushover Notifications</h1>
        <?=$this->draw('account/menu')?>
    </div>

</div>
<div class="row">
    <div class="span10 offset1">
        <form action="/account/pushover/" class="form-horizontal" method="post">
            
            <div class="control-group">
                <div class="controls">
                    <p><a href="https://pushover.net/apps/build" target="_blank">Set up an application for your idno site</a> at pushover.net, and then enter the details below:
                    </p>
                    
                    <div class="control-group">
                        <label class="control-label" for="user_token">User Token</label>
                        <div class="controls">
                            <input type="text" id="user_token" placeholder="Your user token" class="span4" name="user_token" value="<?=htmlspecialchars($user->pushover_user_token)?>" >
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="app_token">Application Token</label>
                        <div class="controls">
                            <input type="text" id="app_token" placeholder="Your application token" class="span4" name="app_token" value="<?=htmlspecialchars($user->pushover_app_token)?>" >
                        </div>
                    </div>
                    
                   <div class="control-group">
		    <div class="controls">
			<button type="submit" class="btn btn-primary">Save</button>
		    </div>
		</div>
                </div>
            </div>
            
            
            <?= \Idno\Core\site()->actions()->signForm('/account/pushover/')?>
        </form>
    </div>
</div>