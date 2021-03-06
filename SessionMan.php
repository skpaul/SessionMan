
<?php 

    /*
        Last modified - 16-04-2020
    */
    
    class SessionMan{
      
        /**
         * @var int $defaultSessionTimeoutValue Stores the default timeout value in seconds.
         * 
         * Default value is 3600 seconds (60 minutes).
         */
        private $defaultSessionTimeoutValue = 3600; //
        
        /**
         * constructor()
         * 
         * @param int $defaultDuration Sets the defualt duration in seconds. Default value is 3600 seconds (60 minutes).
         */
        public function __construct($defaultDuration = 3600) {
            $this->defaultSessionTimeoutValue = $defaultDuration;
        }

        public function __destruct(){ }


        /**
         * start()
         * 
         * Start a brand new session.
         * Sets default session lifetime value.
         * Updates last activity time.
         */
        public function start(){
            /*PHP_SESSION_ACTIVE*/ /*PHP_SESSION_NONE*/
            if (session_status() == PHP_SESSION_NONE) {

                //Tell server to keep session data for AT LEAST 3600 seconds (the defaultSessionTimeoutValue)
                ini_set('session.gc_maxlifetime', $this->defaultSessionTimeoutValue);
                
                //Each client should remember their session id for EXACTLY 1 hour
                session_set_cookie_params($this->defaultSessionTimeoutValue);
                
                session_start();
            }             

            //Update last activity time stamp
            $_SESSION['LAST_ACTIVITY'] = time(); 
        }

        /**
         * set()
         * 
         * Sets value in session variable.
         * Also update the last activity time.
         * 
         * @param string $identifier The name of the variable i.e. "user_id".
         * @param mixed $value The value to set.
         * 
         * @throws Exception of type SessionManException if session is not initialized.
         */
        public function set($identifier, $value){

            if(!$this->isActive()){
                throw new SessionManException("Session is not initialized.");
            }

            if($this->isExpired()){
                throw new SessionManException("Session expired.");
            }
        
            $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
            $_SESSION[$identifier] = $value;
        }

        /**
         * get()
         * 
         * Get value from the specified session variable.
         * 
         * @param string $identifier The name of the variable
         * 
         * @return mixed Value
         * 
         * @throws Exception of type SessionManException if session is not initialized.
         */
        public function get($identifier){

            if(!$this->isActive()){
                throw new SessionManException("Session is not initialized.");
            }

            if($this->isExpired()){
                throw new SessionManException("Session expired.");
            }

            if(!isset($_SESSION[$identifier])) { 
                throw new SessionManException("$identifier not found in current session.");
            } 

            //update last activity time stamp
            $_SESSION['LAST_ACTIVITY'] = time(); 

            return $_SESSION[$identifier];

        }

        /**
         * has()
         * 
         * Checks whether current session has this value.
         * 
         * @param string $identifier The name of the session variable.
         * 
         * @return boolean
         */
        public function has($identifier){

            if(isset($_SESSION[$identifier])) { 
                //update last activity time stamp
                $_SESSION['LAST_ACTIVITY'] = time(); 

                return true;
                // header('Location:' . $this->session_expired_url, true, 303);
            } 

            return false;
        }
       
        /**
         * isActive()
         * 
         * Checks whether current session is active or not.
         * 
         * @return bool Returns 'true' if active, otherwise 'false'
         */
        public function isActive(){
            /*PHP_SESSION_ACTIVE*/ /*PHP_SESSION_NONE*/
            if (session_status() == PHP_SESSION_ACTIVE) {
               return true;
            }
            else{
                return false;
            }
        }

        /**
         * isExpired()
         * 
         * Checks whether current session has been expired or not.
         * 
         * @return bool Returns 'true' if expired, otherwise 'false'
         */
        public function isExpired(){
            if(isset($_SESSION['LAST_ACTIVITY'])) {
                if((time() - $_SESSION['LAST_ACTIVITY']) > $this->defaultSessionTimeoutValue) { 
                    // $diff = time() - $_SESSION['LAST_ACTIVITY'] ;
                    session_unset();     // unset $_SESSION variable for the run-time 
                    session_destroy();   // destroy session data in storage
                    return true;
                }
                else{
                    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
                    return false;
                }
            }
            else{
                //'LAST_ACTIVITY' variable not found.
                return true;
            }  
        }

        /**
         * close()
         * 
         * Unset and destroy the curren session.
         */
        public function close(){
             /*PHP_SESSION_ACTIVE*/ /*PHP_SESSION_NONE*/
             if (session_status() == PHP_SESSION_ACTIVE) {
                
                //unset $_SESSION variable for the run-time 
                session_unset();    
                
                //destroy session data in storage
                session_destroy();   
             }
        }
    } //<--class

    class SessionManException extends Exception{
        
    }
?>