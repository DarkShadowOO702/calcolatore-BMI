<?php
if ($_SERVER["REQUEST_METHOD"] == "GET"){
    header("Location: form.html");
    exit;
}

$nome = $_POST["nome"];
$peso = $_POST["peso"];
$altezza = $_POST["altezza"];

// Validazzione Dati
$nomeValido = ($nome != "");
$pesoValido = (is_numeric($peso) && $peso > 0);
$altezzaValido = (is_numeric($altezza) && $altezza > 0);

// Converte l'altezza da centimetri a metri
$altezza = $altezza / 100;

// Registrazione dei dati non validi
$nomeLog = "NOT_VALID";
$pesoLog = "NOT_VALID";
$altezzaLog = "NOT_VALID";

$bmiLog = "NOT_VALID";
$classeLog = "NOT_VALID";

if ($nomeValido) {
    $nomeLog = $nome;
}

if ($pesoValido) {
    $pesoLog = $peso;
}

if ($altezzaValida) {
    $altezzaLog = $altezza;
}

// Calcola il BMI
if ($pesoValido && $altezzaValida) {

    $altezza = $altezza / 100;

    $bmi = $peso / ($altezza * $altezza);
    $bmi = round($bmi, 2);

    if ($bmi < 18.5) {
        $fascia = "SOTTOPESO";
        $classe = "sottopeso";
    } else if ($bmi < 25) {
        $fascia = "NORMOPESO";
        $classe = "normopeso";
    } else if ($bmi < 30) {
        $fascia = "SOVRAPPESO";
        $classe = "sovrappeso";
    } else {
        $fascia = "OBESITÀ";
        $classe = "obesita";
    }

    $bmiLog = $bmi;
    $classeLog = $classe;
}

// Arrotonda il BMI a due cifre decimali
$bmi = round($bmi, 2);

// Salvataggio dello storico in log.csv
$data = date("d/m/Y");
$ora = date("H:i:s");
$ip = $_SERVER["REMOTE_ADDR"];

$riga = array(
    $data,
    $ora,
    $ip,
    $nomeLog,
    $altezzaLog,
    $pesoLog,
    $bmiLog,
    $classeLog
);

$file = fopen("log.csv", "a");

if ($file != false) {
    fputcsv($file, $riga);
    fclose($file);
}

if (!$nomeValido || !$pesoValido || !$altezzaValida) {
    exit("Errore: inserisci dei dati validi!");
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risultato BMI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Calcolatore BMI</h1>

    <!-- Aggiunge alla card la classe della fascia BMI -->
    <div class="card <?php echo $classe; ?>">

        <div class="risultato">
            <p>
                <?php echo $nome; ?>
            </p>

            <p>Il tuo BMI è:</p>

            <div class="bmi-numero">
                <?php echo $bmi; ?>
            </div>

            <div class="fascia">
                <?php
                echo $fascia;
                ?>
            </div>
        </div>

        <a class="link-nuovo" href="form.html">
            Calcola di nuovo
        </a>

    </div>

</body>
</html>