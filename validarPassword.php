<?php 
function validar_password($password,&$error_password){
	$error_password = "";
  
  $pattern = '/[\'\/~`\!@#$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\]/';
  if (!((preg_match('`[0-9]`',$password)) or (preg_match($pattern, $password))) or 
    (strlen($password) < 6) or
    (!preg_match('`[a-z]`',$password)) or 
    (!preg_match('`[A-Z]`',$password))) {
    $error_password .= "<li>La contraseña debe tener al menos 6 caracteres, una mayuscula, una minuscula y un numero o un simbolo.</li>";
  }

  if($error_password){
  	return false;
  }

  return true; 
}