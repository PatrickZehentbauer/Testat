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
        let answer = document.querySelectorAll("span") //Vierer Paare der Antworten
        let inputs = [];
        
        answer.forEach(item => {
            for (let i = 0; i < 12; i++) {
                if (item.children[i].tagName == 'INPUT') { //Nur die Input Felder wählen
                    inputs.push(item.children[i])
                }
            }
        })

        function checkAnswers() {
            let check = true //Äußere Prüfvariable da eine forEach-Schleife in Javascript nicht mit 'return false' abgebrochen werden kann
            inputs.forEach(item => {
                if (check == true) {
                    let answered = 0 //innere Prüfsumme (zuviele oder zuwenig Antworten gegeben)
                    if (item.checked == true) { //Prüfen ob Input Feld gechecked, wenn ja Prüfsumme erhöhen
                        answered++
                    }
                    //Falls die Prüfsumme für eine Frage nicht 1 betrifft wurden entweder zu viele oder zu wenig Häckchen gesetzt
                    if(answered < 1) {
                        console.log("Nicht alle Fragen beantwortet")
                        check = false
                    } else if (answered > 1) {
                        console.log("Bitte geben Sie pro Frage nur eine Antwort an")
                        check = false;
                    }
                }
            })
            return check;
        }

        function save() {
            let data = []
            console.log(checkAnswers())
            //if( checkAnswers() ){
                inputs.forEach( item , index => {
                    console.log( index )
                    if( item.checked == true ){
                        switch( index % 4 ){
                        case 0: data.push('a')
                        break;
                        case 1: data.push('b')
                        break;
                        case 2: data.push('c')
                        break;
                        case 3: data.push('d')
                        break;
                        }
                    }
                })
                //}
            data.forEach( item => console.log( item ))
            } 
    </script>
</body>

</html>