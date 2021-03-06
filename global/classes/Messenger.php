<?php

class Messenger
{
	function __construct($saveToDb=false,$sessionId="")
	{
		if (!$_SESSION) session_start();
		$this->MessagesPresent	=(is_array($_SESSION['_msg']['message']))  ? true : false;
		$this->CationsPresent	=	false;
		$this->ErrorsPresent	=(is_array($_SESSION['_msg']['error']))  ? true : false;
		$this->InfosPresentt    =   false;
		$this->MessageType=		"";		
		$this->color=			"";
		if ($saveToDb && $sessionId!=""){
			$this->SessionId=$sessionId;			
		}
		$this->GetMessages(false);
		$this->div['id'] 		= '__messages';
		$this->div['class'] 	= '__message';
		$this->div['bgcolor'] 	= '';
		$this->div['display'] 	= 'inline';
		$this->css				= "";
		 
	}
	

	function Info($msg)
	{
		$this->InfosPresent = true;
		$this->color = 'color:#0000FF';
		$_SESSION['_msg']['info'][] = $msg;
		$_SESSION['_msg']['type'] = 'details-info';
		$this->Save($msg,'info');
		$this->GetMessages(false);
		
	}
	function Message($msg  =  "")
	{
		$this->MessagesPresent = true;
		$this->color = 'color:#009900'; 
		$_SESSION['_msg']['message'][] = $msg;
		$_SESSION['_msg']['type'] = 'details-message';
		$this->Save($msg,'message');
		$this->GetMessages(false);
	}
	function Caution($msg)
	{
		$this->CautionsPresent = true;
		$this->color = 'color:#FF6600'; 
		$_SESSION['_msg']['caution'][] = $msg;
		$_SESSION['_msg']['type'] = 'details-caution';
		$this->Save($msg,'caution');
		$this->GetMessages(false);
	}	

	function Error($msg)
	{
		$this->ErrorsPresent = true;
		$this->color = 'color:#FF0000';
		$_SESSION['_msg']['error'][] = $msg;
		$_SESSION['_msg']['type'] = 'details-error';
		$this->Save($msg,'error');
		$this->GetMessages(false);
	}
	
	private function Save($msg = "",$type = "")
	{
		return;
	}
	function Display($flush = true)
	{
		if (isset($_SESSION['_msg']['info'])) {
			$this->messages = $_SESSION['_msg']['info'];
			$this->MessageType = 'details-info';
			$this->css = 'message info';
			
		}

		if (isset($_SESSION['_msg']['message'])) {
			$this->messages = $_SESSION['_msg']['message'];
			$this->MessageType = 'details-message';
			$this->css = 'message success';			
		}
		
		if (isset($_SESSION['_msg']['caution'])) {
			$this->messages = $_SESSION['_msg']['caution'];
			$this->MessageType = 'details-caution';
			$this->css = 'message caution';
			
		}
		if (isset($_SESSION['_msg']['error'])) {
			$this->messages = $_SESSION['_msg']['error'];
			$this->MessageType = 'details-error';
			$this->css = 'message error';
			
		}
		
		if ($this->SessionId != ""){
			$this->displaySsql = "SELECT `message`,`messageType` FROM `messages` WHERE `sessionId` = '$this->SessionId'";
			$rs = $this->db->Query($this->displaySql);
				while ($row = $this->db->GetRow($rs)){
					echo $row['message'].'<br>';
				}
		} else {
			echo join('<br>',(array)$this->messages);
		}
		if ($flush) $this->Flush();
		
	}
	function Flush()
	{
		$_SESSION['_msg'] = array();

	}
	
	function Clear(){
		$this->MessagesPresent	 = false;
		$this->CationsPresent	 = false;
		$this->ErrorsPresent	 = false;
		$this->InfosPresentt     =   false;
		$this->MessageType = 		"";		
		$this->color = 			"";	
		$this->messages = 		"";			
	}
	
	function SetMessageType($type){
		$_SESSION['_msg']['type'] = $type;
		$this->MessageType = $type;
	}
	
	function GetMessages($flush = true){
				if (isset($_SESSION['_msg']['info'])) {
			$this->messages = $_SESSION['_msg']['info'];
			$this->MessageType = 'details-info';
			$this->div['bgcolor'] = '#0080FF';
			$this->css = 'message info';
			
		}

		if (isset($_SESSION['_msg']['message'])) {
			$this->messages = $_SESSION['_msg']['message'];
			$this->MessageType = 'details-message';
			$this->div['bgcolor'] = '#33FF66';
			$this->css = 'message success';
		}
		
		if (isset($_SESSION['_msg']['caution'])) {
			$this->messages = $_SESSION['_msg']['caution'];
			$this->MessageType = 'details-caution';
			$this->div['bgcolor'] = '#FF6600';
			$this->css = 'message caution';
		}
		if (isset($_SESSION['_msg']['error'])) {
			$this->messages = $_SESSION['_msg']['error'];
			$this->MessageType = 'details-error';
			$this->div['bgcolor'] = '#FF80C0';
			$this->css = 'message error';
		}
		$this->html = AR::join($this->messages, BR);
		if ($flush) $this->Flush();
		return $this->html;
	}

	function __toString(){		
		if($this->MessageType != ""){
			$this->GetMessages(true);
			//$this->div['style'] =  "background-color:{$this->div['bgcolor']};";
			$this->div['class'] = $this->css;
			$close = '<div class="message_close" style="display:inline">X</div>';
			$closejs = '$("div.message_close").live("click",
        function(){
                $(this).parent("div").css("display","none");
        });';
			return Html::tag('div', $this->html . $close, AR::bind($this->div) ) . Javascript::script($closejs);
		} else {
			return '';	
		}	
				
	}

}

