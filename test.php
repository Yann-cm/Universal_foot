    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Jeux</title>
    </head>
    <body>
        <?php
        if (isset($_POST['answer']) or isset($_POST['rater'])) {
            $reponse = (isset($_POST["answer"])) ?$_POST["answer"] : "Temps écouler" ;
            echo "<p>Votre réponse est : $reponse</p>";
        } 
        else {
            $liste = [["Pays :","Angleterre"],["Poste :","atackant"],["Numero :", "10"],["Age :", "18"],["Equipes :", "paris saint germain"]];
            shuffle($liste);
            $nom = "Dembelle";
        ?>
        <form id="gameForm" method="post">
            <input type="hidden" name="answer" id="answerField">
            <button type="button" id="startButton">Démarrer</button>
            <div id="countdown"></div>
            <div id="info"></div>
            <input type="text" id="userInput" placeholder="Votre réponse...">
            <button type="button" id="submitButton">Envoyer</button>
        </form>

        <script>
            document.getElementById('startButton').addEventListener('click', function() {
                var countdownElement = document.getElementById('countdown');
                var infoElement = document.getElementById('info');
                var userInput = document.getElementById('userInput');
                var submitButton = document.getElementById('submitButton');
                var start = 27;

                var countdownInterval = setInterval(function() {
                    start--;
                    countdownElement.textContent = start;
                    if (start === 25) {
                        appendInfo("<?php echo $liste[0][0].' '.$liste[0][1]; ?>");
                    } else if (start === 20) {
                        appendInfo("<?php echo $liste[1][0].' '.$liste[1][1]; ?>");
                    } else if (start === 17) {
                        appendInfo("<?php echo $liste[2][0].' '.$liste[2][1]; ?>");
                    } else if (start === 14) {
                        appendInfo("<?php echo $liste[3][0].' '.$liste[3][1]; ?>");
                    } else if (start === 10) {
                        appendInfo("<?php echo $liste[4][0].' '.$liste[4][1]; ?>");
                    }else if (start === 0) {
                        clearInterval(countdownInterval);
                        var form = document.createElement('form');
                        form.setAttribute('method', 'POST');
                        var button = document.createElement('button');
                        button.setAttribute('type', 'submit');
                        button.setAttribute('name', 'rater');
                        button.textContent = 'Voir les réponses';
                        form.appendChild(button);
                        document.body.appendChild(form);
                        var formulaire = document.getElementById('gameForm');
                        formulaire.style.display = 'none';
                    }
                }, 1000);

                function appendInfo(info) {
                    var infoParagraph = document.createElement('p');
                    infoParagraph.textContent = info;
                    infoElement.appendChild(infoParagraph);
                }

                submitButton.addEventListener('click', function() {
                    var answer = userInput.value;
                    document.getElementById('answerField').value = answer;
                    document.getElementById('gameForm').submit();
                });
            });
        </script>
        <?php
        }
        ?>
    </body>
    </html>
