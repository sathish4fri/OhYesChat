<?php
/**
 * Ohyes Theme
 * @website Link: https://github.com/lianglee/OhYesTheme
 * @Package Ohyes
 * @subpackage Theme
 * @author Liang Lee
 * @copyright All right reserved Liang Lee 2014.
 * @ide The Code is Generated by Liang Lee php IDE.
*/ 

$user = elgg_get_logged_in_user_entity()->guid;
$get = "SELECT * FROM elgg_ohyes_chat WHERE(reciever='$user' AND view='0')";
$messages = OhYesChat::Data($get, 'get');
foreach ($messages  as $friend){
$friend = get_user($friend->sender);
$icon = elgg_view("icon/default", array(
								   'entity' => $friend, 
									'size' => 'tiny',
									));  
				?>
      <div class="friends-list-item" style="margin: 5px 1px 0px 0px;background: #EEE;padding: 9px;" onClick="OhYesChat.newTab(<?php echo $friend->guid;?>);"> 
                  <div class="icon" style="display: inline-table;"> <?php echo $icon;?></div>
                  <div class="name" style="display: inline-table;margin-top: -10px;"><?php echo OhYesChat::sttl($friend->name, 23);?></div>  
                </div>
                
                <?Php
			} 

