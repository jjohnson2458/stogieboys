<?php

class CURL
{
	function __construct($url = "")
	{
		if ($url != "") {
			$this->url = $url;
		}
$this->options = array('CURLOPT_VERBOSE', 
						'CURLOPT_HEADER', 
						'CURLOPT_NOPROGRESS', 
						'CURLOPT_NOSIGNAL', 
						'CURLOPT_WILDCARDMATCH', 
						'CURLOPT_WRITEFUNCTION', 
						'CURLOPT_WRITEDATA', 
						'CURLOPT_READFUNCTION', 
						'CURLOPT_READDATA', 
						'CURLOPT_IOCTLFUNCTION', 
						'CURLOPT_IOCTLDATA', 
						'CURLOPT_SEEKFUNCTION', 
						'CURLOPT_SEEKDATA', 
						'CURLOPT_SOCKOPTFUNCTION', 
						'CURLOPT_SOCKOPTDATA', 
						'CURLOPT_OPENSOCKETFUNCTION', 
						'CURLOPT_OPENSOCKETDATA', 
						'CURLOPT_CLOSESOCKETFUNCTION', 
						'CURLOPT_CLOSESOCKETDATA', 
						'CURLOPT_PROGRESSFUNCTION', 
						'CURLOPT_PROGRESSDATA', 
						'CURLOPT_HEADERFUNCTION', 
						'CURLOPT_WRITEHEADER', 
						'CURLOPT_DEBUGFUNCTION', 
						'CURLOPT_DEBUGDATA', 
						'CURLOPT_SSL_CTX_FUNCTION', 
						'CURLOPT_SSL_CTX_DATA', 
						'CURLOPT_CONV_TO_NETWORK_FUNCTION', 
						'CURLOPT_CONV_FROM_NETWORK_FUNCTION', 
						'CURLOPT_CONV_FROM_UTF8_FUNCTION', 
						'CURLOPT_INTERLEAVEFUNCTION', 
						'CURLOPT_INTERLEAVEDATA', 
						'CURLOPT_CHUNK_BGN_FUNCTION', 
						'CURLOPT_CHUNK_END_FUNCTION', 
						'CURLOPT_CHUNK_DATA', 
						'CURLOPT_FNMATCH_FUNCTION', 
						'CURLOPT_FNMATCH_DATA', 
						'CURLOPT_ERRORBUFFER', 
						'CURLOPT_STDERR', 
						'CURLOPT_FAILONERROR', 
						'CURLOPT_URL', 
						'CURLOPT_PROTOCOLS', 
						'CURLOPT_REDIR_PROTOCOLS', 
						'CURLOPT_PROXY', 
						'CURLOPT_PROXYPORT', 
						'CURLOPT_PROXYTYPE', 
						'CURLOPT_NOPROXY', 
						'CURLOPT_HTTPPROXYTUNNEL', 
						'CURLOPT_SOCKS5_GSSAPI_SERVICE', 
						'CURLOPT_SOCKS5_GSSAPI_NEC', 
						'CURLOPT_INTERFACE', 
						'CURLOPT_LOCALPORT', 
						'CURLOPT_LOCALPORTRANGE', 
						'CURLOPT_DNS_CACHE_TIMEOUT', 
						'CURLOPT_DNS_USE_GLOBAL_CACHE', 
						'CURLOPT_BUFFERSIZE', 
						'CURLOPT_PORT', 
						'CURLOPT_TCP_NODELAY', 
						'CURLOPT_ADDRESS_SCOPE', 
						'CURLOPT_TCP_KEEPALIVE', 
						'CURLOPT_TCP_KEEPIDLE', 
						'CURLOPT_TCP_KEEPINTVL', 
						'CURLOPT_NETRC', 
						'CURLOPT_NETRC_FILE', 
						'CURLOPT_USERPWD', 
						'CURLOPT_PROXYUSERPWD', 
						'CURLOPT_USERNAME', 
						'CURLOPT_PASSWORD', 
						'CURLOPT_PROXYUSERNAME', 
						'CURLOPT_PROXYPASSWORD', 
						'CURLOPT_HTTPAUTH', 
						'CURLOPT_TLSAUTH_TYPE', 
						'CURLOPT_TLSAUTH_SRP', 
						'CURLOPT_TLSAUTH_USERNAME', 
						'CURLOPT_TLSAUTH_PASSWORD', 
						'CURLOPT_PROXYAUTH', 
						'CURLOPT_AUTOREFERER', 
						'CURLOPT_ACCEPT_ENCODING', 
						'CURLOPT_TRANSFER_ENCODING', 
						'CURLOPT_FOLLOWLOCATION', 
						'CURLOPT_UNRESTRICTED_AUTH', 
						'CURLOPT_MAXREDIRS', 
						'CURLOPT_POSTREDIR', 
						'CURLOPT_PUT', 
						'CURLOPT_POST', 
						'CURLOPT_POSTFIELDS', 
						'CURLOPT_POSTFIELDSIZE', 
						'CURLOPT_POSTFIELDSIZE_LARGE', 
						'CURLOPT_COPYPOSTFIELDS', 
						'CURLOPT_HTTPPOST', 
						'CURLOPT_REFERER', 
						'CURLOPT_USERAGENT', 
						'CURLOPT_HTTPHEADER', 
						'CURLOPT_HTTP200ALIASES', 
						'CURLOPT_COOKIE', 
						'CURLOPT_COOKIEFILE', 
						'CURLOPT_COOKIEJAR', 
						'CURLOPT_COOKIESESSION', 
						'CURLOPT_COOKIELIST', 
						'CURLOPT_HTTPGET', 
						'CURLOPT_HTTP_VERSION', 
						'CURLOPT_IGNORE_CONTENT_LENGTH', 
						'CURLOPT_HTTP_CONTENT_DECODING', 
						'CURLOPT_HTTP_TRANSFER_DECODING', 
						'CURLOPT_MAIL_FROM', 
						'CURLOPT_MAIL_RCPT', 
						'CURLOPT_MAIL_AUTH', 
						'CURLOPT_TFTP_BLKSIZE', 
						'CURLOPT_FTPPORT', 
						'CURLOPT_QUOTE', 
						'CURLOPT_POSTQUOTE', 
						'CURLOPT_PREQUOTE', 
						'CURLOPT_DIRLISTONLY', 
						'CURLOPT_APPEND', 
						'CURLOPT_FTP_USE_EPRT', 
						'CURLOPT_FTP_USE_EPSV', 
						'CURLOPT_FTP_USE_PRET', 
						'CURLOPT_FTP_CREATE_MISSING_DIRS', 
						'CURLOPT_FTP_RESPONSE_TIMEOUT', 
						'CURLOPT_FTP_ALTERNATIVE_TO_USER', 
						'CURLOPT_FTP_SKIP_PASV_IP', 
						'CURLOPT_FTPSSLAUTH', 
						'CURLOPT_FTP_SSL_CCC', 
						'CURLOPT_FTP_ACCOUNT', 
						'CURLOPT_FTP_FILEMETHOD', 
						'CURLOPT_RTSP_REQUEST', 
						'CURLOPT_RTSP_SESSION_ID', 
						'CURLOPT_RTSP_STREAM_URI', 
						'CURLOPT_RTSP_TRANSPORT', 
						'CURLOPT_RTSP_HEADER', 
						'CURLOPT_RTSP_CLIENT_CSEQ', 
						'CURLOPT_RTSP_SERVER_CSEQ', 
						'CURLOPT_TRANSFERTEXT', 
						'CURLOPT_PROXY_TRANSFER_MODE', 
						'CURLOPT_CRLF', 
						'CURLOPT_RANGE', 
						'CURLOPT_RESUME_FROM', 
						'CURLOPT_RESUME_FROM_LARGE', 
						'CURLOPT_CUSTOMREQUEST', 
						'CURLOPT_FILETIME', 
						'CURLOPT_NOBODY', 
						'CURLOPT_INFILESIZE', 
						'CURLOPT_INFILESIZE_LARGE', 
						'CURLOPT_UPLOAD', 
						'CURLOPT_MAXFILESIZE', 
						'CURLOPT_MAXFILESIZE_LARGE', 
						'CURLOPT_TIMECONDITION', 
						'CURLOPT_TIMEVALUE', 
						'CURLOPT_TIMEOUT', 
						'CURLOPT_TIMEOUT_MS', 
						'CURLOPT_LOW_SPEED_LIMIT', 
						'CURLOPT_LOW_SPEED_TIME', 
						'CURLOPT_MAX_SEND_SPEED_LARGE', 
						'CURLOPT_MAX_RECV_SPEED_LARGE', 
						'CURLOPT_MAXCONNECTS', 
						'CURLOPT_CLOSEPOLICY', 
						'CURLOPT_FRESH_CONNECT', 
						'CURLOPT_FORBID_REUSE', 
						'CURLOPT_CONNECTTIMEOUT', 
						'CURLOPT_CONNECTTIMEOUT_MS', 
						'CURLOPT_IPRESOLVE', 
						'CURLOPT_CONNECT_ONLY', 
						'CURLOPT_USE_SSL', 
						'CURLOPT_RESOLVE', 
						'CURLOPT_DNS_SERVERS', 
						'CURLOPT_ACCEPTTIMEOUT_MS', 
						'CURLOPT_SSLCERT', 
						'CURLOPT_SSLCERTTYPE', 
						'CURLOPT_SSLKEY', 
						'CURLOPT_SSLKEYTYPE', 
						'CURLOPT_KEYPASSWD', 
						'CURLOPT_SSLENGINE', 
						'CURLOPT_SSLENGINE_DEFAULT', 
						'CURLOPT_SSLVERSION', 
						'CURLOPT_SSL_VERIFYPEER', 
						'CURLOPT_CAINFO', 
						'CURLOPT_ISSUERCERT', 
						'CURLOPT_CAPATH', 
						'CURLOPT_CRLFILE', 
						'CURLOPT_SSL_VERIFYHOST', 
						'CURLOPT_CERTINFO', 
						'CURLOPT_RANDOM_FILE', 
						'CURLOPT_EGDSOCKET', 
						'CURLOPT_SSL_CIPHER_LIST', 
						'CURLOPT_SSL_SESSIONID_CACHE', 
						'CURLOPT_SSL_OPTIONS', 
						'CURLOPT_KRBLEVEL', 
						'CURLOPT_GSSAPI_DELEGATION', 
						'CURLOPT_SSH_AUTH_TYPES', 
						'CURLOPT_SSH_HOST_PUBLIC_KEY_MD5', 
						'CURLOPT_SSH_PUBLIC_KEYFILE', 
						'CURLOPT_SSH_PRIVATE_KEYFILE', 
						'CURLOPT_SSH_KNOWNHOSTS', 
						'CURLOPT_SSH_KEYFUNCTION', 
						'CURLOPT_SSH_KEYDATA', 
						'CURLOPT_PRIVATE', 
						'CURLOPT_SHARE', 
						'CURLOPT_NEW_FILE_PERMS', 
						'CURLOPT_NEW_DIRECTORY_PERMS', 
						'CURLOPT_TELNETOPTIONS'
						);

$this->curl_options = array(
							'CURLOPT_DEBUGFUNCTION' => array(
								'CURLINFO_TEXT',
								'CURLINFO_HEADER_IN',
								'CURLINFO_HEADER_OUT',
								'CURLINFO_DATA_IN',
								'CURLINFO_DATA_OUT'
							), 
							'CURLOPT_NETRC' => array(
								'CURL_NETRC_OPTIONAL',
								'CURL_NETRC_IGNORED',
								'CURL_NETRC_REQUIRED'
							), 
							'CURLOPT_HTTPAUTH' => array(
								'CURLAUTH_BASIC',
								'CURLAUTH_DIGEST',
								'CURLAUTH_DIGEST_IE',
								'CURLAUTH_GSSNEGOTIATE',
								'CURLAUTH_NTLM',
								'CURLAUTH_NTLM_WB',
								'CURLAUTH_ANY',
								'CURLAUTH_ANYSAFE',
								'CURLAUTH_ONLY'
							),
							'CURLOPT_HTTP_VERSION' => array(
								'CURL_HTTP_VERSION_NONE',
								'CURL_HTTP_VERSION_1_0',
								'CURL_HTTP_VERSION_1_1'), 
							'CURLOPT_FTPSSLAUTH' => array(
								'CURLFTPAUTH_DEFAULT',
								'CURLFTPAUTH_SSL',
								'CURLFTPAUTH_TLS'), 
							'CURLOPT_FTP_SSL_CCC' => array(
								'CURLFTPSSL_CCC_NONE',
								'CURLFTPSSL_CCC_PASSIVE',
								'CURLFTPSSL_CCC_ACTIVE'), 
							'CURLOPT_FTP_FILEMETHOD' => array(
								'CURLFTPMETHOD_MULTICWD',
								'CURLFTPMETHOD_NOCWD',
								'CURLFTPMETHOD_SINGLECWD'), 
							'CURLOPT_RTSP_REQUEST' => array(
								'CURL_RTSPREQ_OPTIONS',
								'CURL_RTSPREQ_DESCRIBE',
								'CURL_RTSPREQ_ANNOUNCE',
								'CURL_RTSPREQ_SETUP',
								'CURL_RTSPREQ_PLAY',
								'CURL_RTSPREQ_PAUSE',
								'CURL_RTSPREQ_TEARDOWN',
								'CURL_RTSPREQ_GET_PARAMETER',
								'CURL_RTSPREQ_SET_PARAMETER',
								'CURL_RTSPREQ_RECORD',
								'CURL_RTSPREQ_RECEIVE'
								),
							'CURLOPT_IPRESOLVE' => array(
								'CURL_IPRESOLVE_WHATEVER',
								'CURL_IPRESOLVE_V4',
								'CURL_IPRESOLVE_V6'
								), 
							'CURLOPT_USE_SSL' => array(
								'CURLUSESSL_NONE',
								'CURLUSESSL_TRY',
								'CURLUSESSL_CONTROL',
								'CURLUSESSL_ALL'
								), 
							'CURLOPT_SSLVERSION' => array(
								'CURL_SSLVERSION_DEFAULT',
								'CURL_SSLVERSION_TLSv1',
								'CURL_SSLVERSION_SSLv2',
								'CURL_SSLVERSION_SSLv3'), 
							'CURLOPT_SSH_KEYFUNCTION' => array(
								'CURLKHSTAT_FINE_ADD_TO_FILE',
								'CURLKHSTAT_FINE',
								'CURLKHSTAT_REJECT',
								'CURLKHSTAT_DEFER')
							);
		
	}

	function init()
	{
		if(!$this->ch){
			curl_init($this->url);
		}
	}

	function url($url = "")
	{
		if ($url != "") {
			$this->url = $url
		}
	}

	function getinfo()
	{
		
	}

	function version()
	{
		
	}



	function __call($name, $array)
	{
		$option = trim(strtoupper($name));
		if (self::is_option($option)) {
			curl_setopt($this->ch, $option, self::set_constants($option, $array));
		}
		
	}

	function is_option($option)
	{
		return (is_array($option, $this->options))  ? true : false ;
	}

	function set_constants($option, $array)
	{
		$params = array();
		foreach((array)$array as $value){
			if (in_array($value, $this->curl_options[$option])) {
				$params[] = $value;
			} else {
				$params[] = Quote::add($value);
			}
		}
		return AR::join($params);
	}

	function exec()
	{
		$this->init();
		$this->result = curl_exec($this->ch;
		if (!$this->result)) {
			$this->error(curl_error($this->ch));
			$this->errno = curl_errno();
		} else {
			return $this->result;
		}


	}

	function error()
	{
		
	}

	function __destruct()
	{
		if ($this->ch) {
			curl_close($this->ch);
		}
	}
}