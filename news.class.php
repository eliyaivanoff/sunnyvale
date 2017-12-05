<?php
/**
 * @author Eliya
 * Class for working with NEWS table
 * Insert, Update, Delete and Retrieve data from NEWS table.
 * User's authorization functionality at the end of this module.
 * @copyright 2017
 */

class News
{
	protected $crypt = 'DfR45Yn06jKWksncxb' ;
    protected $conn ;
	protected $parms = array(
		'host'      => 'localhost',
		'user'      => 'root',
		'pass'      => '',
		'db'        => 'news',
		'port'      => '3306',
		'charset'   => 'utf8',
	) ;
/**
 * Connect to database on constractor event
 */    
    public function __construct() {
        $this->dbConnect( $this->$parms ) ;
    }

/**
 * Filling whith data 
 */    
    public function newsInsert( $data = array() ) {
        if ( empty($data) ) return false ;

        $sql = "INSERT INTO `news`(`desc_short`, `desc_full`, `added_time`) 
                VALUES (".$this->dbQuote($data['desc_short']).", ".$this->dbQuote($data['desc_full']).", UNIX_TIMESTAMP())" ;
        $res = $this->dbExecute( $sql ) ;
        
        if (!$res) $this->dbError( "Cannot add record into NEWS table" ) ;
        
        return $res ;
    }
/**
 * Retrieve data from news table
 */    
    public function newsRetrieve( $limit = 10 ) {
        $news = array() ;
        $sql = "SELECT * FROM `news` ORDER BY `added_time` DESC LIMIT $limit" ;
        $res = $this->dbExecute( $sql ) ;
        
        if ( $res === false ) return $this->dbError( "Cannot add record into NEWS table" ) ;
        
        while ( $data = mysql_fetch_assoc($res) ) 
        {
            $news[] = $data ;
        }
        
        return $news ;
    }
/**
 * Update news table
 */    
    public function newsUpdate( $data = array() ) {
        if ( empty($data) ) return false ;
        
        $sql = "UPDATE `news` SET `desc_short` = ".$this->dbQuote($data['desc_short']).
               ", `desc_full` = ".$this->dbQuote($data['desc_full']).
               ", `added_time` = UNIX_TIMESTAMP(), `active` = ".$this->dbQuote($data['active']).
               " WHERE `news_id` = $data[news_id]"  ;
        $res = $this->dbExecute( $sql ) ;
            
        if ( $res === false ) return $this->dbError( "Cannot update record #$data[news_id]" ) ;
        
        return $res ;
    }
/**
 * Delete from news table
 */    
    public function newsDelete( $id = array() ) {
        if ( empty($id) ) return false ;
        
        foreach( $id AS $key )
        {
            $sql = "DELETE FROM `news` WHERE `news_id` = $key" ;
            $res = $this->dbExecute( $sql ) ;
            if ( $res === false ) return $this->dbError( "Cannot delete record #$key from NEWS table" ) ;
        }
        
        return $res ;
    }

/**
* Quote string parameters
*/    
    private function dbQuote( $str ) {
        return	"'".mysql_real_escape_string( $this->conn, $str )."'" ;
    }
/**
* Try to connect to NEWS database
*/    
    private function dbConnect( $parm ) {
        @$this->conn = mysql_connect( $parm['host'], $parm['user'], $parm['pass'], $parm['db'], $parm['port'] ) ;
		
        if ( !$this->conn )
	{
		$this->dbError( "Cannot connect to NEWS database" ) ;
	}
        
        mysql_set_charset($this->conn, $parm['charset']) ;         
    }   
/**
* Tracking mysql errors
*/
    private function dbError( $message ) {
        $error = $message.": ".mysql_error();
        echo "<br /><br />";
        echo "<p><b>Error!</b>&nbsp;$error</p>" ;
    
        return false ;        
    }
/**
* Exuqute query on NEWS database 
*/
	private function dbExecute( $sql ){
	    $res = mysql_query( $this->conn, $sql ) ;
	    return $res ;
	}

/************************************
* User's part of this module
*************************************/
/**
* Register new user   
*/
    public function addUser( $user = array() ) {
        if ( empty( $user ) ) return false ;
        if ( !$this->checkUserParms($user) ) return false ;
        
        $sql = "INSERT INTO `users`(`user_login`, `user_email`, `user_pass`)
                VALUES(".$this->dbQuote($user['user_login'].", '$user[user_email]' ,
                ENCODE(".$this->dbQuote($user['user_login'].", '".$this->$crypt."'))" ;
        $res = $this->dbExecute($sql) ;        
        
        if ( !$res ) return $this->dbError( "Cannot add record INTO USERS table" ) ;
        
        return $user['user_login'] ;
    
    }
/**
* Check if user exists  
*/
    public function authUser( $user = array() ) {
        $pass = '' ;
        
        if ( empty( $user ) ) return false ;
        if ( !$this->checkUserParms($user) ) return false ;
        
        $sql = "SELECT DECODE(`user_pass`, '".$this->$crypt."') FROM `users' 
                 WHERE `user_login` = ".$this->dqQuote($user['user_login']) ;
        $res = $this->dbExecute($sql) ;
        if ( $res === false ) return $this->dbError( "Cannot select record from USERS table" ) ;
        list( $pass ) =  mysql_fetch_row( $res ) ;
        
        if ( !$pass OR $pass != $user['user_pass'] ) return false ;
        // Save auth user into session for using in the future
        session_start() ;
        $_SESSION['auth'] = $user['user_login'] ; 
        
        return true ;
    }
/**
* Logout user
*/
    public function logoutUser() {
        session_start() ;
        session_destroy() ;
    }
/**
* Check user parameters
*/
    private function checkUserParms( $user = array() ) {
        if ( empty( $user ) ) return false ;
        if ( empty( $user['user_login'] ) OR trim( $user['user_login'] ) == '' ) return false ;
        if ( empty( $user['user_pass'] ) OR trim( $user['user_pass'] ) == '' ) return false ;
        
        return true ;
    }
    
    
}

?>
