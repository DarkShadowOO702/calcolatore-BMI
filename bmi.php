<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    header("Location: form.html");
    exit;
}

// Lettura dei dati
$nome = isset($_POST["nome"]) ? trim($_POST["nome"]) : "";
$peso = isset($_POST["peso"]) ? $_POST["peso"] : "";
$altezza = isset($_POST["altezza"]) ? $_POST["altezza"] : "";

// Validazione dei dati
$nomeValido = ($nome != "");
$pesoValido = (is_numeric($peso) && $peso > 0);
$altezzaValida = (is_numeric($altezza) && $altezza > 0);
// Controlla che tutti i dati siano validi
if (!$nomeValido || !$pesoValido || !$altezzaValida) {
    exit("Errore: inserisci dei dati validi!");
}

// Conversione dell'altezza da centimetri a metri
$altezza = $altezza / 100;

// Calcolo del BMI
$bmi = $peso / ($altezza * $altezza);
$bmi = round($bmi, 2);

// Assegnazione della fascia e della classe CSS
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

    <div class="card <?php echo $classe; ?>">

        <div class="risultato">

            <p>
                <?php echo htmlspecialchars($nome, ENT_QUOTES, "UTF-8"); ?>
            </p>

            <p>Il tuo BMI è:</p>

            <div class="bmi-numero">
                <?php echo number_format($bmi, 2, ".", ""); ?>
            </div>

            <div class="fascia">
                <?php echo $fascia; ?>
            </div>

        </div>

    </div>

    <a class="link-nuovo" href="form.html">
        Calcola di nuovo
    </a>

</body>
</html>