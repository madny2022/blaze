<!DOCTYPE html>
<html>
<head>
    <title>Checklist Cores</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .panel {
            width: 90%;
            max-width: 300px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: auto;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .panel h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .panel textarea {
            width: 100%;
            height: 100px;
            resize: none;
            border: 2px solid #ccc;
            border-radius: 5px;
            padding: 5px;
        }

        .panel input[type="submit"],
        .panel input[type="button"] {
            margin-top: 20px;
            margin-bottom: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .panel input[type="submit"]:hover,
        .panel input[type="button"]:hover {
            background-color: #45a049;
        }

        .panel input[type="submit"].animate,
        .panel input[type="button"].animate {
            animation-name: buttonAnimation;
            animation-duration: 0.5s;
        }

        @keyframes buttonAnimation {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        #resultado {
            margin-top: 20px;
        }

        #resultado-text {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
    <script>
        function copyResults() {
            var resultados = document.getElementById("resultado").innerText;
            
            // Cria um elemento temporário <textarea> para copiar o texto
            var textarea = document.createElement("textarea");
            textarea.value = resultados;
            
            // Adiciona o <textarea> à página
            document.body.appendChild(textarea);
            
            // Seleciona o texto do <textarea>
            textarea.select();
            textarea.setSelectionRange(0, 99999); // Para dispositivos móveis
            
            // Copia o texto para a área de transferência
            document.execCommand("copy");
            
            // Remove o <textarea> temporário
            document.body.removeChild(textarea);
            
            // Exibe uma mensagem de confirmação
            alert("Resultados copiados para a área de transferência!");
        }

        function executeButtonAnimation() {
            var button = document.getElementById("executarButton");
            button.classList.add("animate");
        }

        function checkMatch() {
            var checklistText = document.getElementById("checklistText").value;
            var checarText = document.getElementById("checarText").value;
            var checkListArray = checklistText.split("\n");
            var checarArray = checarText.split("\n");
            var resultElement = document.getElementById("resultado");
            resultElement.innerHTML = "";

            for (var i = 0; i < checarArray.length; i++) {
                var checarItem = checarArray[i].trim();
                var matchFound = false;
                var horarioCor = "";

                for (var j = 0; j < checkListArray.length; j++) {
                    var item = checkListArray[j].trim();
                    if (item === checarItem) {
                        matchFound = true;
                        horarioCor = item.replace(";", " ");
                        break;
                    }
                }

                if (matchFound) {
                    resultElement.innerHTML += horarioCor + "⚪️✅" + "<br>";
                } else {
                    resultElement.innerHTML += checarItem + "⚪️💢"+ "<br>";
                }
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="panel">
            <h1>Histórico da Blaze ☢</h1>
            <form method="post" action="" style="text-align: center;">
                <textarea name="Checklist" id="checklistText" rows="5" cols="50"><?php
                    $url = 'https://api-v2.blaze.com/roulette_games/history';
                    $data = file_get_contents($url);
                    $results = json_decode($data, true);

                    if (isset($results['records'])) {
                        foreach ($results['records'] as $record) {
                            if (isset($record['roll']) && isset($record['created_at'])) {
                                $numero = $record['roll'];
                                $cor = '';
                                if ($numero >= 1 && $numero <= 7) {
                                    $cor = '🔴';
                                } elseif ($numero >= 8 && $numero <= 14) {
                                    $cor = '⚫️';
                                } elseif ($numero == 0) {
                                    $cor = '⚪️';
                                } else {
                                    $cor = 'Desconhecido';
                                }
                                $horario = date('H:i', strtotime($record['created_at'] . ' -5 hours'));
                                echo $horario . ' ' . $cor . PHP_EOL;
                            }
                        }
                    } else {
                        echo 'Nenhum resultado encontrado.';
                    }
                ?></textarea><br>
                <input type="submit" id="executarButton" name="executar" value="Atualize" onclick="executeButtonAnimation()">
            </form>

            <form method="post" action="" style="text-align: center;">
                <textarea name="Checar" id="checarText" rows="5" cols="50"></textarea><br>
                <input type="button" name="executar2" value="Checar 🖱" onclick="checkMatch()" class="animate">
                <button onclick="copyResults()">Copiar Resultados</button>
            </form>

            <div id="resultado">
                
                <div id="resultado-text">▼▼▼ Resultados da Lista ▼▼▼</div>
                
            </div>
        </div>
    </div>
</body>
</html>


