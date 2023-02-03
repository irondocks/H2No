<?php
	namespace pasm;

	require_once 'pasm.php';


	class H2No {

		public $h2no;
		public $result;
		public $db;

		function __construct(string $file)
		{
			$sha256 = "";
			$this->result = [];

			if (file_exists($file))
			{
				$sha256 = hash_file('sha256','pasm.php');
				$this->h2no = new PASM();
			}
			try
			{
				$this->h2no::restore($file);
				$this->db = $this->h2no::$stack;
			}
			catch (e){exit(0);}
			$this->h2no::verified();
			if ($this->h2no::$checksum == "68abaa9117c6f055987c4240f4950e41aec32efa212c3bad3c9384f834936feb")
			{
				echo 'PASM Verified as Version ' . $this->h2no::$version;
			}
			else
			{
				echo 'PASM version was unaquirable' . $this->h2no::$version;
				exit();
			}
		}

		/**
		  * CRUD
		  * Create, Read, Update, Delete
		  *
		*/

		/**
		  * Set Database Name
		  * 
		  * @method set_h2no
		  * @param db H2No Database Name (default: h2no)
		  * @return void 
		 */
		public function set_h2no(string $db)
		{
			if (file_exists($db))
				$this->h2no::restore($db);
			else
				echo 'Error: Failed Callback - Please check data';
			$this->db = $this->h2no::$stack;
			return $this;
		}

		/**
		  * Create Record
		  *
		  * @method create
		  * @param kv key/value array
		  * @return void
		 */
		public function create(array $kv)
		{
			$temp_stack = [];

			foreach ($this->db as $key => $val)
			{
				$temp_stack = array_merge($temp_stack, [ $key => $val ]);
			}
			
			foreach ($kv as $key => $val)
			{
				$temp_stack = array_merge($temp_stack, [ $key => $val ]);
			}

			$this->db = $temp_stack;
			$this->result = $temp_stack;
			return $this;
		}

		/**
		  * Read
		  * Go through matching elements from NoSQL
		  * query.
		  *
		  * @method read
		  * @param kv key/value array
		  * @return void results in $this->result
		 */
		public function read($kv)
		{ 
			$result = [];
			if (is_string($kv))
			{
				// var_dump($this->h2no::$stack);
				foreach ($this->db as $k => $v)
				{
					if ($kv == $k)
						$this->result = array_merge($this->result,[$k => $v]);
				}
				return $this;
			}
			else if (is_array($kv))
			{	
				foreach($kv as $value)
				{
					foreach ($this->db as $k => $v)
					{
						if (is_array($v))
							return $this->read($v);
						else
							$this->result = array_merge($this->result,[$k => $v]);
					}
				}
			}
			else
			{
				echo 'Error: Param #1 should be array or string';
			}
			$this->result;
			return $this;
		}

		/**
		  * Update
		  * Match elements from NoSQL
		  * query and update results.
		  *
		  * @method update
		  * @param kv key/value array
		  * @return void 
		 */
		public function update(array $key)
		{
			$this->create($key);
			return $this;
		}


		/**
		  * Delete
		  * Match elements from NoSQL
		  * query and return results.
		  *
		  * @method delete
		  * @param kv key/value array
		  * @param db H2No Database Name (default: h2no)
		 */
		public function delete($kv)
		{
			$temp_stack = [];

			if (is_string($kv))
			{
				// var_dump($this->h2no::$stack);
				foreach ($this->db as $k => $v)
				{

					if ($kv == $k)
						;
					else $temp_stack = array_merge($temp_stack, [$k => $v]);
				}
				$this->db = $temp_stack;
				return $this;
			}
			else if (is_array($kv))
			{
				foreach($kv as $value)
				{
					foreach ($this->db as $k => $v)
					{
						if (is_array($v))
							return $this->delete($v);
						else
							$temp_stack = array_merge($temp_stack, [ $k => $v ]);
					}
				}
				$this->db = $temp_stack;
				return $this;
			}
		}

		/**
		  * Save
		  * Save Results back to file
		  *
		 */
		public function save($file)
		{
			$temp = $this->h2no::$stack = $this->db;
			file_put_contents($file, serialize($temp));
			return $this;
		}

		public function load_db($file)
		{
			$this->db = file_get_contents($file);
			$this->db = unserialize($this->db);
			return $this;
		}
	}	
?>
