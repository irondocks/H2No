<?php

  namespace H2No;
  require_once 'h2no.php';
  
	$g = serialize(json_decode(file_get_contents("six.json")));
	file_put_contents("six.serialized",$g);	
	$h2no = new H2No("six.serialized");
	// $h2no->set_h2no("six.serialized");
	$h2no->load_db("six.serialized");
	$h2no->delete("ten");
	$h2no->update(["eight" => ["seven" => 17]]);
	var_dump($h2no->result);
	$h2no->save("sixc.serialized");
?>
