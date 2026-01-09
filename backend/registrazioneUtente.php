<?php
header('Content-Type: application/json; charset=utf-8');
$servername = "db";
$username   = "mioUtente";
$password   = "MiaPassword";
$dbname     = "progettoingsoft";
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");
if ($conn->connect_error) 
{
    echo json_encode(["success" => false,"message" => "Connessione al DB fallita"]);
    exit;
}
//TEST
// if (!isset($_POST['nome'])) 
// {
//     echo json_encode(["success" => false,"message" => "un parametro mantante"]);
//     exit;
// }
if (!isset($_POST['nome'],$_POST['cognome'],$_POST['email'],$_POST['telefono'],$_POST['password'])) 
{
    echo json_encode(["success" => false,"message" => "Parametri mancanti"]);
    exit;
}
$nome     = trim($_POST['nome']);
$cognome  = trim($_POST['cognome']);
$email    = trim($_POST['email']);
$telefono = trim($_POST['telefono']);
$password = $_POST['password'];
if ($nome === "" || $cognome === "" || $email === "" || $telefono === "" || $password === "") 
{
    echo json_encode(["success" => false,"message" => "Tutti i campi sono obbligatori"]);
    exit;
}
/* Controllo email */
$sql = "SELECT idUtente FROM utente WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) 
{
    echo json_encode(["success" => false,"message" => "Email già registrata"]);
    exit;
}
$stmt->close();
/* Inserimento */
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO utente (nome, cognome, email, telefono, password) VALUES(?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $nome, $cognome, $email, $telefono, $password_hash);

if ($stmt->execute()) 
{
    echo json_encode(["success" => true,"message" => "Registrazione OK"]);
} 
else 
{
    echo json_encode(["success" => false,"message" => "Errore durante la registrazione"]);
}
$stmt->close();
$conn->close();
exit;
?>
