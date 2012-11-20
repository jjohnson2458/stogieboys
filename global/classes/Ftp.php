<?php
error_reporting(E_ALL);

class Ftp
{

  /**
   *
   * 
   * @package
   * @subpackage
   * @static
   * @access
   * @author
   * @copyright
   * @deprecated
   * @example
   * @ignore
   * @internal
   * @link
   * @see
   * @since
   * @tutorial
   * @version
   *  inline {@internal}}
   *  inline {@inheritdoc}
   *  inline {@link}
   *
   *
   *
   */

  CONST self=__CLASS__;
  
  /**
   *
   */
   function  Ftp($ftpUser="",$ftpHost="",$ftpPass="",$ftpPasv=true) 
   {
		$this->user=($ftpUser!="")  ? $ftpUser : FTP_USER ;
		$this->pass=($ftpPass!="")  ? $ftpPass : FTP_PASSWORD ;
		$this->host=($ftpHost!="")  ? $ftpHost : FTP_HOST ;
		$this->pasv=($ftpPasv)  	? true : false ;
		$this->reports=				array();
		$this->log=					"";
		$this->errors=				array();
		$this->errorLog=			"";
		if (!$this->connect()) return false;
		
		
   }

  /** 
   *
   */
   function  connect()
   {
		$this->ftp= @ftp_connect($this->host);
		$this->result = @ftp_login($this->ftp,$this->user,$this->pass);
		@ftp_pasv($this->ftp, $this->pasv);
			if ((!$this->ftp) || (!$this->result)) {
				$this->Error('FTP connection has failed!');
				$this->Error("Attempted to connect to {$this->host} for user {$this->user}");
				return false;				
			} else {
				$this->Report("Connected to {$this->host}, for user {$this->user}");
				return true;
			}
   }

  
  /** 
   *
   */
   function  set_path()
   {

   }

  /** 
   *
   */
   function  get($source="",$target="",$ascii=true)
   {
		if ($source!="" && $target!=""){		
			$this->type=($ascii)  ? FTP_ASCII : FTP_BINARY ;
			@ftp_get($this->ftp,$target,$source,$this->type);
			if (file_exists($target)){
				$this->Report("$source downloaded to $target ".filesize($target)." bytes.");				
				return true;
			} else {
				$this->Error("unsuccessful download of the file $target");				
				return false;
			}
		}
   }
    
  /** 
   *
   */
   function  put($source="",$target="",$ascii=true)
   {
		if ($source!="" && $target!=""){		
			$this->type=($ascii)  ? FTP_ASCII : FTP_BINARY ;
			ftp_put($this->ftp,$target,$source,$this->type);			
			$folder=str_replace(basename($target),'',$target);
			$files=$this->listdir($folder);
			if ($this->fileExists($target)){
				$this->Report("$source uploaded to $target ".ftp_size($this->ftp,$target)." bytes.");
				
				return true;
			} else {
				$this->Error("unsuccessful upload of the file $target");				
				return false;
			}
		}
   }
  
  /** 
   *
   */
   function  delete($filepath="",$log=true)
   {
		if ($filepath!="") ftp_delete($this->ftp,$filepath);
		if(!$log)return true;
		if (!$this->fileExists($filepath)){
			$this->Report("$filepath deleted.");
			return true;
		} else {
			$this->Error("could not delete $filepath");
			return false;
		}
   }
   
   function execute($command=""){
		if($this->ftp){
			return (ftp_exec($this->ftp,$command))  ? true : false ;
		}
   }
   
   
   function fileExists($filepath){		
		$folder=str_replace(basename($filepath),'',$filepath);
		$files=array_merge(array(),$this->listdir($folder)); 
		return (in_array(basename($filepath),$files))  ? true : false ; 		
   }
  
  /** 
   *
   */
   function  chdir($dir="")
   {
		if (!$this->ftp) $this->connect();
		if ($dir!=""){
			ftp_chdir($this->ftp,$dir);
			$this->dir=$dir;
		}
   }

  /** 
   *
   */
   function  chmod($file='',$chmod='0775')
   {
		if($ftp_chmod($this->ftp,$chmod,$file) !==false){
			$this->Report(" Permissions changed on $file to $chmod ");
			return true;		
		} else {
			$this->Error("could not chmod $file");
			return false;
		}
   }

  /** 
   *
   */
   function  rename($old,$new)
   {
		if (ftp_rename($this->ftp, $old, $new)) {
			 $this->Report("successfully renamed $old to $new");
			 return true;
		} else {
			 $this->Error("There was a problem while renaming $old to $new");
			 return false;
		}
   }
   
     /** 
   *
   */
   function listdir($dir='.')
   {		
		return ftp_nlist($this->ftp,$dir);
   }
	
     /** 
   *
   */
   function  modtime($file="")
   {
		if($file!=""){
			return @ftp_mdtm($this->ftp,$file);
		}
   }
	
	
     /** 
   *
   */
   function  close()
   {
		ftp_close($this->ftp);
   }
		
  /** 
   *
   */
   function  Report($report="",$status='STATUS')
   {
		if ($report!=""){
			$this->reports[]=date('Y-m-d H:i:s')."-- $status: ".$report;
			$this->log=AR::join($this->reports,CR.BR);
		}
   }
	
  /** 
   *
   */
   function  Error($error="")
   {
		if ($error!=""){
			$this->Report($error,'ERROR');
			$this->errors[]=date('Y-m-d H:i:s')." --  ".$error;
			$this->errorLog=AR::join($this->errors,CR.BR);
			
		}
   }
   
	
  /** 
   *
   */
   function  LastError()
   {
		if ($this->errors){
			return end($this->errors);
		}
   }
    
   function  __destruct()
   {
		#ftp_close($this->ftp);
   }
}