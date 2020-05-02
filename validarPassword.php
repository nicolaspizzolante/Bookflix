<?php 
function validar_password($password,&$error_password){
	$error_password = "";
  
  $pattern = '/[\'\/~`\!@#$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\]/';
  if (!((preg_match('`[0-9]`',$password)) or (preg_match($pattern, $password))) or (strlen($password) < 6)) {
    $error_password .= "<li>La contraseña debe tener al menos 8 alfanumericos.</li>";
  }

  if($error_password){
  	return false;
  }

  return true; 
}