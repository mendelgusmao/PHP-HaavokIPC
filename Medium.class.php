<?php

	/**
	 * Class responsible for the input and output of the data used by PHP-Ghetto-RPC
	 * depending on the value of PHPGR_MEDIUM
	 * 
	 * PHPGR_MEDIUM can be:
	 * - PHPGR_MEDIUM_FILE
	 *   PHP-Ghetto-RPC will use a file as the medium
	 *   
	 * - PHPGR_MEDIUM_MEMCACHE
	 *   PHP-Ghetto-RPC will use Memcache as the medium
	 *   
	 * - PHPGR_MEDIUM_STD 
	 *   PHP-Ghetto-RPC will use STDIN/STDOUT as the Medim // NOT IMPLEMENTED YET 
	 * 
	 * @author Mendel Gusmão
	 *
	 */
	class Medium {
		
		var $id;
		var $medium_file;
		var $data;
		var $valid;
		
		/**
		 * Class constructor
		 * 
		 * @param $id 
		 */
		function Medium ($id) {
        
			$this->id = $id;
			$this->medium_file = PHPGR_TMP . $this->id . PHPGR_EXT;
			
			$this->initialize();
	
		}
		
		/**
		 * Constructor extender  
		 */
		function initialize () {
			
			$this->valid = false;
			
			if (PHPGR_MEDIUM == PHPGR_MEDIUM_MEMCACHE && class_exists("Memcache")) {
            
				$this->mem = new Memcache;
				
	   			if (!$this->mem->addServer(PHPGR_MEMCACHED, PHPGR_MEMCACHEDP)) {
                
	   				define("PHPGR_MEDIUM", PHPGR_MEDIUM_FILE);
                    
	   			}
	   			else {
                
	   				$this->valid = true;
                    
	   			}
			}
			else 
			{
				define("PHPGR_MEDIUM", PHPGR_MEDIUM_FILE);
				
				$this->medium_file = PHPGR_TMP . $this->id . PHPGR_EXT;
									
				$this->medium_res = @fopen($this->medium_file, "w");

				$this->valid = !is_null($this->medium_res);
			}
			
		} 
		
		/**
		 * Sets the data to the medium
		 * 
		 * @param mixed $data The data to be set
		 */
		function set ($data) {
        
			switch (PHPGR_MEDIUM) {
				
				case PHPGR_MEDIUM_MEMCACHE:
					return $this->mem->set($this->id, $data, 1);

				case PHPGR_MEDIUM_FILE:
					$data = serialize($data);
					fwrite($medium_res, $data);			
					fclose($medium_res);
					return true;

				case PHPGR_MEDIUM_STD:
					
				break;					
				
			}
            
		}
		
		/**
		 * Gets the data from the medium
		 * 
		 * @return mixed $data The data
		 */
		function get () {
			
			switch (PHPGR_MEDIUM)  {
				
				case PHPGR_MEDIUM_FILE:
					
					/* $this->medium_file = PHPGR_TMP . $this->id . PHPGR_EXT;
	
					if (!$this->medium_res) 
					{
						Bridge::_log("cannot import: error opening bridge file");
						
						trigger_error("PHP-Ghetto-RPC: cannot import. error opening bridge file '{$this->medium_file}' for reading", E_USER_ERROR);					
						
						return false;
					} */
		
					$data = "";
						
					while ($temp = fread($this->medium_res, 1024))
						$data .= $temp; 
					
					fclose($this->medium_res);
					
					return unserialize($data);
					

				case PHPGR_MEDIUM_MEMCACHE:
					return $this->mem->get($this->id);

				case PHPGR_MEDIUM_STD:
					
					// @TODO: Add support for reading from WTF
					
				break;					
				
			}
		}
		
		function delete ($id) {
        
			switch (PHPGR_MEDIUM) {
				
				case PHPGR_MEDIUM_FILE:
					return @unlink($this->medium_file);
				
				case PHPGR_MEDIUM_MEMCACHE:
					return $this->mem->delete($this->id);

			}		
					
		}
		
		/**
		 * Verify if the medium is valid
		 * @return bool 
		 */
		function valid () {
        
			return $this->valid;
            
		}
	}
    
?>