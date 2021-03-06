<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <title>Testat 2</title>
    <link rel="stylesheet" href="testat.css" />
</head>

<body>
    <h3>Fragebogen</h3>
    <?php
    $fragen = array(
        "0" => "Who let the dogs out?",
        "1" => "Victorias Secret?",
        "2" => "What does the fox say?",
        "3" => "What shall we do with the drunken Sailer?"
    );
    $null = array(
        //Antworten auf erste Frage
        "a" => "You",
        "b" => "Me",
        "c" => "He",
        "d" => "They",
    );
    $eins = array(
        //Antworten auf zweite Frage
        "a" => "Underwear",
        "b" => "Bra",
        "c" => "Pantsy",
        "d" => "Bralette",

    );
    $zwei = array(
        //Antworten auf dritte Frage
        "a" => "Ding",
        "b" => "DingDing",
        "c" => "DingDingDing",
        "d" => "DingDingDingDingDingDingDingDingDing",

    );
    $drei = array(
        //Antworten auf evierte Frage
        "a" => "Early",
        "b" => "in",
        "c" => "the",
        "d" => "morning",

    );
    $antworten = array(
        //Alle Antwort-Arrays in einem Array zusammengefasst
        "0" => $null,
        "1" => $eins,
        "2" => $zwei,
        "3" => $drei,
    );
    function createQuestion($number, $question)
    {
        //Erstellt Fragen
        echo "
        <label><b> $number. Frage: $question </b></label>
        <br>";
    };
    function getAnswer($answerCount, $questionCount)
    {
        //Holt die passenden Antworten
        global $antworten;
        return $antworten[$questionCount][$answerCount];
    }
    function createAnswer($counter)
    {
        //Erstellt Checkboxen
        echo "<span class='answer'>";
        for ($i = 'a'; $i < 'e'; $i++) {
            echo "
            <input type='checkbox'>
            <label>" . getAnswer($i, $counter) . "</label>
            <br>";
        }
        echo "</span><br>";
    };
    //Erstellt den Frageboden
    for ($counter = 0; $counter < count($fragen); $counter++) {
        createQuestion((key($fragen) + 1), $fragen[$counter]);
        createAnswer($counter);
        next($fragen);
    }
    ?>
    <footer>
        <button onclick="save()">Speichern</button>
    </footer>

    <script type="text/javascript">
        function checkAnswers() {
            let answer = document.querySelectorAll("span") //Vierer Paare der Antworten
            let check = true; //??u??ere Pr??fvariable da eine forEach-Schleife in Javascript nicht mit 'return false' abgebrochen werden kann
            answer.forEach(item => {
                if (check == true) {
                    let answered = 0 //innere Pr??fsumme (zuviele oder zuwenig Antworten gegeben)
                    for (let i = 0; i < 12; i++) {
                        if (item.children[i].tagName == 'INPUT') { //Nur die Input Felder w??hlen
                            if (item.children[i].checked == true) { //Pr??fen ob Input Feld gechecked, wenn ja Pr??fsumme erh??hen
                                answered++
                            }
                        }
                    }
                    //Falls die Pr??fsumme f??r eine Frage nicht 1 betrifft wurden entweder zu viele oder zu wenig H??ckchen gesetzt
                    if (answered < 1) {
                        console.log("Nicht alle Fragen beantwortet")
                        check = false;
                    } else if (answered > 1) {
                        console.log("Bitte geben Sie pro Frage nur eine Antwort an")
                        check = false;
                    }
                }
            })
            return check;
        }

        function save() {
            console.log(checkAnswers())
        }
    </script>
</body>

</html>