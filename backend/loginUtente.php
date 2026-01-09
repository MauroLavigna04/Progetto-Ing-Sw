<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

$servername = "db";
$username = "mioUtente";       
$password = "MiaPassword";  
$dbname = "progettoingsoft";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) 
{
    echo json_encode(["success" => false, "message" => "Connessione fallita"]);
    exit;
}
if (isset( $_POST['email'], $_POST['password'])) 
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Controllo campi vuoti
    if (empty($email) || empty($password)) 
	{
        echo json_encode(["success" => false, "message" => "Tutti i campi sono obbligatori"]);
        exit;
    }
	$stmt = $conn->prepare("SELECT password, nome, cognome FROM utente WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
	if ($result->num_rows == 0) 
	{
        echo json_encode(["success" => false, "message" => "Account non trovato"]);
        exit;
    }
	$user = $result->fetch_assoc();
	error_log("Password inserita: " . $password);
	error_log("Hash DB: " . $user['password']);

    // 2. VERIFICA LA PASSWORD (fondamentale!)
    // Nota: password_verify confronta la pass in chiaro con l'hash del DB
    if (password_verify($password, $user['password'])) 
	{
        echo json_encode(["success" => true, "message" => "Login effettuato", "nome" => $user['nome'], "cognome" => $user['cognome']]); // Puoi passare dati extra se vuoi
    }
	else 
	{
        echo json_encode(["success" => false, "message" => "Password errata"]);
    }
}
else 
{
    echo json_encode(["success" => false, "message" => "Parametri mancanti"]);
}
?>