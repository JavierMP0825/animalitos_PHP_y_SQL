<?php
// Contraseñas originales para los usuarios
$passwords = [
    'admin1',
    'admin2',
    'usu1',
    'usu2'
];

foreach($passwords as $p){
    echo "Original: $p => Hash: " . password_hash($p, PASSWORD_DEFAULT) . "\n";
}
?>
