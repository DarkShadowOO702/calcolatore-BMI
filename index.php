<?php
$nome = "";
$peso = "";
$altezza = "";
$bmi = null;
$fascia = "";
$classe = "";
$calcolato = false;

// Array per contenere i messaggi di errore specifici per ogni campo
$errori = [
    'nome' => '',
    'peso' => '',
    'altezza' => ''
];

// Gestione dell'invio del form via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recupero e pulizia dei dati
    $nome = isset($_POST["nome"]) ? trim($_POST["nome"]) : "";
    $pesoInput = isset($_POST["peso"]) ? trim($_POST["peso"]) : "";
    $altezzaInput = isset($_POST["altezza"]) ? trim($_POST["altezza"]) : "";

    $peso = filter_var($pesoInput, FILTER_VALIDATE_FLOAT);
    $altezza = filter_var($altezzaInput, FILTER_VALIDATE_FLOAT);

    // Validazione campo Nome
    if (empty($nome)) {
        $errori['nome'] = "Inserisci il tuo nome";
    }

    // Validazione campo Peso
    if ($peso === false || $peso <= 0) {
        $errori['peso'] = "Valore non valido";
    }

    // Validazione campo Altezza
    if ($altezza === false || $altezza <= 0) {
        $errori['altezza'] = "Valore non valido";
    }

    // Se non ci sono errori in nessun campo, procediamo con il calcolo
    if (empty($errori['nome']) && empty($errori['peso']) && empty($errori['altezza'])) {
        $altezzaMetri = $altezza / 100;
        $bmi = $peso / ($altezzaMetri * $altezzaMetri);
        $bmi = round($bmi, 2);

        // Determinazione della fascia e della classe CSS
        if ($bmi < 18.5) {
            $fascia = "SOTTOPESO";
            $classe = "sottopeso";
        } elseif ($bmi < 25) {
            $fascia = "NORMOPESO";
            $classe = "normopeso";
        } elseif ($bmi < 30) {
            $fascia = "SOVRAPPESO";
            $classe = "sovrappeso";
        } else {
            $fascia = "OBESITÀ";
            $classe = "obesita";
        }
        $calcolato = true;
    } else {
        // Ripristiniamo i valori per ripopolare i campi del form
        $peso = $pesoInput;
        $altezza = $altezzaInput;
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcolatore BMI</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 40px 24px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            background: #F1F3F5;
            color: #1F2328;
        }

        h1 {
            font-size: 32px;
            margin: 0 0 24px;
            text-align: center;
        }

        .card {
            width: 360px;
            max-width: 100%;
            padding: 24px;
            background: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .campo {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        label {
            display: block;
            font-weight: bold;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #D0D7DE;
            border-radius: 6px;
            font: inherit;
        }

        /* Stile per input con errore */
        input.input-errore {
            border-color: #B32D2D;
            background-color: #FFF5F5;
        }

        /* Messaggio d'errore sotto il campo */
        .messaggio-errore {
            color: #B32D2D;
            font-size: 12px;
            margin-top: 2px;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #1F2328;
            color: #FFFFFF;
            font: inherit;
            cursor: pointer;
            margin-top: 8px;
        }

        button:hover {
            background: #343A40;
        }

        .risultato {
            text-align: center;
        }

        .risultato p {
            margin: 12px 0;
        }

        .bmi-numero {
            margin: 16px 0;
            font-size: 48px;
            font-weight: bold;
        }

        .fascia {
            padding: 10px;
            border-radius: 6px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .sottopeso {
            background: #E3EEF9;
            border: 3px solid #2F6FB3;
        }
        .sottopeso .fascia {
            color: #2F6FB3;
        }

        .normopeso {
            background: #E3F4E6;
            border: 3px solid #256D34;
        }
        .normopeso .fascia {
            color: #256D34;
        }

        .sovrappeso {
            background: #FCEBD7;
            border: 3px solid #9A4F06;
        }
        .sovrappeso .fascia {
            color: #9A4F06;
        }

        .obesita {
            background: #FBE2E2;
            border: 3px solid #B32D2D;
        }
        .obesita .fascia {
            color: #B32D2D;
        }

        .link-nuovo {
            display: inline-block;
            margin-top: 20px;
            color: #1F2328;
            text-decoration: underline;
        }

        .link-nuovo:hover {
            color: #343A40;
        }

        @media (max-width: 480px) {
            body {
                padding: 24px 16px;
            }
            h1 {
                font-size: 28px;
            }
            .card {
                padding: 20px;
            }
            .bmi-numero {
                font-size: 40px;
            }
        }
    </style>
</head>
<body>

    <h1>Calcolatore BMI</h1>

    <?php if ($calcolato): ?>
        <div class="card <?php echo $classe; ?>">
            <div class="risultato">
                <p><strong><?php echo htmlspecialchars($nome, ENT_QUOTES, "UTF-8"); ?></strong></p>
                <p>Il tuo BMI è:</p>
                <div class="bmi-numero">
                    <?php echo number_format($bmi, 2, ".", ""); ?>
                </div>
                <div class="fascia">
                    <?php echo $fascia; ?>
                </div>
            </div>
        </div>
        <a class="link-nuovo" href="index.php">Calcola di nuovo</a>
    <?php else: ?>
        <div class="card">
            <form action="index.php" method="post" novalidate>
                <div class="campo">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome"
                           class="<?php echo !empty($errori['nome']) ? 'input-errore' : ''; ?>"
                           value="<?php echo htmlspecialchars($nome, ENT_QUOTES, "UTF-8"); ?>"
                           placeholder="Inserisci il tuo nome">
                    <?php if (!empty($errori['nome'])): ?>
                        <span class="messaggio-errore"><?php echo $errori['nome']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="campo">
                    <label for="peso">Peso in kg</label>
                    <input type="number" id="peso" name="peso"
                           class="<?php echo !empty($errori['peso']) ? 'input-errore' : ''; ?>"
                           value="<?php echo htmlspecialchars($peso, ENT_QUOTES, "UTF-8"); ?>"
                           placeholder="Inserisci il tuo peso"
                           step="any">
                    <?php if (!empty($errori['peso'])): ?>
                        <span class="messaggio-errore"><?php echo $errori['peso']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="campo">
                    <label for="altezza">Altezza in cm</label>
                    <input type="number" id="altezza" name="altezza"
                           class="<?php echo !empty($errori['altezza']) ? 'input-errore' : ''; ?>"
                           value="<?php echo htmlspecialchars($altezza, ENT_QUOTES, "UTF-8"); ?>"
                           placeholder="Inserisci la tua altezza"
                           step="any">
                    <?php if (!empty($errori['altezza'])): ?>
                        <span class="messaggio-errore"><?php echo $errori['altezza']; ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit">Calcola</button>
            </form>
        </div>
    <?php endif; ?>

</body>
</html>