<?php

	class Call {

		var $class;
		var $method;
		var $parameters;
		var $constructor_parameters;
		var $callback;
		var $return;
		var $instances;
		var $instance_index;
		var $reuse_instance;
		
		/**
		 * Class constructor
		 * 
		 * @param string $class_method The 'function' or 'Class::method' to be called in remote instance
		 * @param array $parameters Parameters to be passed to the function/class method
		 * @param array $constructor_parameters Parameters to be passed to the class constructor
		 * @param string/Call $callback Callback to be called when the processing 
		 *							  in the remote instance is done
		 *							  
		 */
		function Call ($class_method, $parameters = null,
					   $constructor_parameters = null, $callback = null) {
                      
			if (preg_match("/(&)?(.*)::(.*)/", $class_method, $return)) {
            
				$this->prev_instance = $return[1] == "&";
				$this->class = $return[2];
				$this->method = $return[3];
                
			}
			else {
            
				$this->class = null;
				$this->method = $class_method;
                
			}				
			
			$this->parameters = $parameters;
			$this->constructor_parameters = $constructor_parameters;
			$this->callback = $callback;
			
			self::$instance_index = array();
			self::$instances = array();
			
		}
		
			/**
		 * Executa as funções/métodos passados através do atributo ::$methods
		 * na instância cliente
		 * @return mixed
		 */
		function make () {
			
			/* Oh yeah, this is a real problem!
			 * To instantiate a new class for every Call in the queue
			 * or develop a way to use the same instance for every subsequent call? 
			 */
						
			
			// @todo move this verification to CallsQueue::process()
			if (!PHPGR_IS_BACKEND)
				return false;
				
			$class = $this->class;
			$method = $this->method;
			$static = $this->static;
			$parameters = $this->parameters;
			$constructor_parameters = $this->constructor_parameters;
			$callback = $this->callback;
				
			if ($parameters && !is_array($parameters))
				$parameters = array($parameters);

			if ($constructor_parameters && !is_array($constructor_parameters))
				$constructor_parameters = array($constructor_parameters);					
				
			if ($class && class_exists($class)) {
				
				self::$instance_index[$class] = (int) self::$instance_index[$class];
				
				if (!$object = self::$instances[$class][self::$instance_index[$class]]) {
                
					if (!$this->prev_instance) {
                    
						$object = $instances[$class][self::$instance_index[$class]] 
							= &new $class($constructor_parameters);

						self::$instance_index[$class]++;
						
						$this->_log("call $class::__construct()");	
                        
					}
					else {
                    
						$object = $instances[$class][self::$instance_index[$class] - 1];
						
						$this->_log("reuse $class");
                        
					}			
                    
				}
				
				if (method_exists($object, $method)) {
                
					$return = call_user_func_array(array($object, $method), $params);
					
					$this->_log("call $class::$method()");
                    
				}
				
				$this->calls[$i_method]->return = $return;
				
			}
			else {
            
				if (function_exists($method)) {
                
					$return = call_user_func_array($method, $params);
					$this->_log("call $method()");
                    
				}
				
				if ($return != null)
					$this->calls[$i_method]->return = $return;					
				
			}
			
			$i_method++;
            
			unset($class);
			unset($params);  
			
			return $_CALLS = $this->calls;
		}
		
		/**
		 * Returns an human readable form of the class
		 * @example  'Callback(Class(p1, p2, p3)::Method(p4, p5, p6))'
		 * 
		 * @return string
		 */
		function __toString () {
        
			if ($this->constructor_parameters)
				$constructor_parameters = "(" . implode(", ", $this->constructor_parameters) . ")";
			
			if ($this->class)
				$class = $this->class . $constructor_parameters . "::"; 
				
			$parameters = implode(", ", $this->parameters);
			$callback = $this->callback;
			$method = $this->method;
			
			return sprintf("%s(%s%s(%s))", $callback, $class, $method, $parameters);
		}
		
	}
    
?>