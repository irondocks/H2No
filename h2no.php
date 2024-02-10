<?php declare (strict_types = 1);
//	namespace src;

	require_once 'src/pasm.php';

	class H2No {

		public static $h2no;
		public static $result;
		public static $db;

		function __construct(string $file)
		{	
			$sha256 = "";
			H2No::$result = [];
			try
			{
				if (file_exists($file))
				{
					H2No::$h2no = new PASM();
					H2No::$h2no::restore($file);
					H2No::$db = H2No::$h2no::$stack;
				}
				else {
					H2No::$h2no = new PASM();
					H2No::$db = H2No::$h2no::$stack;
				}
			}
			catch (e){ exit(0); }
			H2No::$h2no::verified();
			if (H2No::$h2no::$checksum == "a2b1ddd23dcda40accd3ae4a1faa6b22d7570c299f1bb4afeeeaf8860e9a5aba")
			{
				echo 'PASM Verified as Version ' . H2No::$h2no::$version;
			}
			else
			{
				echo 'PASM version was unaquirable' . H2No::$h2no::$version;
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
		public static function set_h2no(string $db)
		{
			if (file_exists($db))
				H2No::$h2no::restore($db);
			else
				echo 'Error: Failed Callback - Please check data';
			H2No::$db = H2No::$h2no::$stack;
			return H2No::$db;
		}

		/**
		  * Create Record
		  *
		  * @method create
		  * @param kv key/value array
		  * @return void
		 */
		public static function create(array $kv)
		{
			$temp_stack = H2No::$db;
			
			// foreach (H2No::$db as $key => $val)
			// {
			// 	$temp_stack = array_merge($temp_stack, [ $key => $val ]);
			// }
			
			foreach ($kv as $key => $val)
			{
				$temp_stack = array_merge($temp_stack, [ $key => $val ]);
			}

			H2No::$db = $temp_stack;
			H2No::$result = $temp_stack;
			return H2No::$db;
		}

		/**
		  * Find
		  * Go through matching elements from NoSQL
		  * query.
		  *
		  * @method find
		  * @param kv key/value array or key string
		  * @return void results in H2No::$result
		 */
		public static function find($key = null, $value = null, $count = 0)
		{ 
			$result = [];
			if (is_string($key) or is_string($value))
			{
				if ($key == null)
				foreach (H2No::$db as $k => $v)
				{
					if ($key != null && $key == $k)
						H2No::$result = array_merge(H2No::$result,[$k => $v]);
					else if ($value != null && $value == $v)
						H2No::$result = array_merge(H2No::$result,[$k => $v]);
					if ($count == 0)
						continue;
					else if ($count == count(H2No::$result))
						break;
				}
				return H2No::$result;
			}
			else if (is_array($key))
			{
				foreach($key as $value)
				{
					foreach (H2No::$db as $k => $v)
					{
						if (is_array($v))
							return H2No::find($v);
						else
							H2No::$result = array_merge(H2No::$result,[$k => $v]);
						if ($count == 0)
							continue;
						else if ($count == count(H2No::$result))
							break;
					}
					if ($count == count(H2No::$result))
						break;
				}
			}
			else
			{
				echo 'Error: Param #1 should be array or string';
			}
			H2No::$result;
			return H2No::$db;
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
		public static function update(array $key)
		{
			H2No::create($key);
			return H2No::$db;
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
		public static function delete($kv)
		{
			$temp_stack = [];

			if (is_string($kv))
			{
				
				foreach (H2No::$db as $k => $v)
				{
					if ($kv == $k)
						;
					else $temp_stack = array_merge($temp_stack, [$k => $v]);
				}
				H2No::$db = $temp_stack;
				return H2No::$db;
			}
			else if (is_array($kv))
			{
				foreach($kv as $value)
				{
					foreach (H2No::$db as $k => $v)
					{
						if (is_array($v))
							return H2No::$delete($v);
						else
							$temp_stack = array_merge($temp_stack, [ $k => $v ]);
					}
				}
				H2No::$db = $temp_stack;
				return H2No::$db;
			}
		}

		/**
		  * Save
		  * Save Results back to file
		  *
		 */
		public static function save($file)
		{
			$temp = H2No::$h2no::$stack = H2No::$db;
			file_put_contents($file, serialize($temp));
			return H2No::$db;
		}

		public static function load_db($file)
		{
			$temp = H2No::$h2no::restore($file);
			H2No::$db = unserialize($temp);
			return H2No::$db;
		}
	}
	
?>
