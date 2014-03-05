<?php
/**
 * OhYesChat
 * @website Link: https://github.com/lianglee/OhYesChat
 * @Package Ohyes
 * @subpackage Chat
 * @author Liang Lee
 * @copyright All right reserved Liang Lee 2014.
 * @ide The Code is Generated by Liang Lee php IDE.
 */ 

class OhYesChat {
/**
* LoadJs OhYesChat JS;
*
* @access system
* @return return;
*/
public static function loadJs(){
   elgg_load_js('ohyeschat.js');
}
/**
* LoadCss OhYesChat CSS;
*
* @access system
* @return return;
*/
public static function loadCSS(){
   elgg_load_css('ohyeschat.css');		
}
/**
* Get entity by id
*
* @access system
* @return return;
*/
public function getEntity($guid){
if(isset($guid)){
	 return get_entity($guid);
}
 return false;
}
/**
* Register chat actions
*
* @access system
* @return return;
*/
public static function actions(){
         return array(
					  'send', 'refresh', 'removetab'
					  );	
}
/**
* Data Add
*
* @access system
* @return return;
*/
public static function Data($query, $type){
    global $CONFIG;	
	$query = str_replace('prefixes_', $CONFIG->dbprefix, $query);
	if(!empty($query) && $type == 'get'){
	  return get_data($query);	
	}
	if(!empty($query) && $type == 'add'){
	  return insert_data($query);	
	}
	if(!empty($query) && $type == 'run'){
	  return run_sql_script($query);	
	}
}
/**
* Countnew messages
*
* @access system
* @return return;
*/
public static function countNew(){
		global $CONFIG;	
	 	$user = elgg_get_logged_in_user_entity()->guid;
		$count = "SELECT * FROM {$CONFIG->dbprefix}ohyes_chat 
		              WHERE(reciever='$user' AND view='0')";
        return count(OhYesChat::Data($count, 'get'));
}
/**
* Countnew messages by id
*
* @access system
* @return return;
*/
public static function countNewById($friend){
		global $CONFIG;	
	 	$user = elgg_get_logged_in_user_entity()->guid;
		$count = "SELECT * FROM `{$CONFIG->dbprefix}ohyes_chat` 
		          WHERE(reciever='{$user}' AND sender='{$friend}' AND view='0');";
        return count(OhYesChat::Data($count, 'get'));
}
/**
* Get new message {Object}
*
* @access system
* @return {Object};
*/
public static function getNew($friend){
		global $CONFIG;	
	 	$user = elgg_get_logged_in_user_entity()->guid;
		$count = "SELECT * FROM `{$CONFIG->dbprefix}ohyes_chat` 
		          WHERE(reciever='{$user}' AND sender='{$friend}' AND view='0');";
        return OhYesChat::Data($count, 'get');
}
/**
* Get new message {Object}
*
* @access system
* @return {Object};
*/
public static function getNewAll($params = array()){
		global $CONFIG;	
		if(empty($params)){
		 $params = array('sender', 'message');	
		}
		$params = implode(',' , $params);
	 	$user = elgg_get_logged_in_user_entity()->guid;
		$count = "SELECT $params FROM `{$CONFIG->dbprefix}ohyes_chat` 
		          WHERE(reciever='{$user}' AND view='0');";
        return OhYesChat::Data($count, 'get');
}
/**
* GeMessage
*
* @access system
* @return return;
*/
public static function getMessages($sender, $friend, $limit = 'LIMIT 20'){
		global $CONFIG;	
        $get = "SELECT * FROM {$CONFIG->dbprefix}ohyes_chat 
		WHERE(sender='{$sender}' AND reciever='{$friend}' 
		OR sender='{$friend}' AND reciever='$sender') ORDER BY mid DESC {$limit};";
        return OhYesChat::Data($get, 'get');
}
/**
* Count Online Friends
*
* @access system
* @return return;
*/
public static function countOnline($entity){
$friends = $entity->getFriends();
$online = 0;
foreach ($friends as $friend){
		if ($friend->last_action > time() - 10) {		
				     $online++;
			} 
}
return $online;
}
/**
* Get friend status
*
* @access system
* @return return;
*/
public static function userStatus($user){
$friend = get_user($user); 
   if($friend->last_action > time() - 10){
		 return 'online';  
	  } 
	  else {
		return  'offline';  
	  }	
}
/**
* Return status icon
*
* @access system
* @return return;
*/
public static function getStatusClass($friend){
if(OhYesChat::userStatus($friend) == 'online'){
  return 'OhYesChat-Icon-Onine';	
} 
else {
  return 'OhYesChat-Icon-Offline';
}	
}
/**
* Send a message to user;
*
* @access system
* @return return;
*/
public function SendMessage($reciever , $message){
	  global $CONFIG;	
	  $this->sender = elgg_get_logged_in_user_entity()->guid;
	  $this->time = time();	  
		            $this->reciever = (int)$reciever;
	  if(!empty($this->reciever) && !empty($message)){			
			    	if(OhYesChat::Data("INSERT INTO {$CONFIG->dbprefix}ohyes_chat 
								   (`reciever`, `sender`, `message`, `view`, `time`) VALUES
								   ('$this->reciever', '$this->sender', '$message' ,'0', '$this->time')", 'add')){
					     return true;	
					}
	  }
    return false;	  
}
/**
* String limit srlen
*
* @access system
* @return return;
*/
public static function sttl($str, $limit = NULL, $dots = true){
if(isset($str) 
		 && isset($limit)){
                 if(strlen($str) > $limit){
	               if($dots == true){
                        return substr($str, 0, $limit).'...';		
	                      } elseif($dots == false){
		                     return substr($str, 0, $limit);
	                      }
                      }
                   elseif(strlen($str) < $limit){
	           return $str;   	   
        }
}
  return false; 
}
/**
* Setup OhYesChat
*
* @access system
* @return return;
*/
public function Setup(){
	global $CONFIG;	
	$plugin = elgg_get_plugins_path().'OhYesChat/setup/';
	try {
	OhYesChat::Data("{$plugin}ohyeschat.sql", 'run');
	} catch (Exception $e) {
			$msg = $e->getMessage();
			if (strpos($msg, 'already exists')) {
				$msg = elgg_echo('install:error:tables_exist');
			}
			register_error($msg);
			return FALSE;
    }	
}
/**
* Replace icons
*
* @access system
* @return message;
*/
public static function replaceIcon($message){
	$icon = elgg_get_site_url().'mod/OhYesChat/images/emoticons/';
 	$icons = str_replace(array(
						  ':(', 
						  ':)', 
						  '=D',
						  ';)'), array(
						  "<img src='{$icon}ohyeschat-sad.gif'/>",
						  "<img src='{$icon}ohyeschat-smile.gif '/>",
						  "<img src='{$icon}ohyeschat-happy.gif '/>",
						  "<img src='{$icon}ohyeschat-wink.gif '/>",
								 ),
	                            $message
	                             );
	return $icons;							 
	
}
/**
* Disable XSS attack
*
* @access system
* @return message;
*/
public static function messageValidate($message){
  return htmlentities($message,  ENT_QUOTES);	
}

}//class