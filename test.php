<?php
  namespace src;
  require_once 'src/h2no.php';
  require_once 'src/pasm.php';
	$g = serialize(json_decode(file_get_contents("six.json")));
	file_put_contents("six.serialized",$g);
	
	$h2no = new H2No("six.serialized");
	// $h2no->set_h2no("six.serialized");
	// $h2no->load_db(__NAMESPACE__."/../six.serialized");
	$h2no->create(["ten" => 11 ]);
	$h2no->update(["eight" => ["seven" => 17]]);
	var_dump($h2no::$result);
	$h2no->save("sixc.serialized");
?>
