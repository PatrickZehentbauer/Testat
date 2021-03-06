<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <title>Testat 2</title>
    <link rel="stylesheet" href="Testat2.css" />
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
        <!-- <button type="submit" >OK</button> -->
        <button onclick="save()">Speichern</button>
        <button onclick="deleteAnswers()">Abbrechen</button>
    </footer>
    <script type="text/javascript">
        let answer = document.querySelectorAll("span") //Vierer Paare der Antworten
        var inputs = [];
        
        answer.forEach(item => {
            for (let i = 0; i < 12; i++) {
                if (item.children[i].tagName == 'INPUT') { //Nur die Input Felder w??hlen
                    inputs.push(item.children[i])
                }
            }
        })
        function checkAnswers() {
            let check = true //??u??ere Pr??fvariable da eine forEach-Schleife in Javascript nicht mit 'return false' abgebrochen werden kann
            let quarter = 0; //Zusammenfassung der vier Antowrtfelder einer Frage
            let answered = 0 //innere Pr??fsumme (zuviele oder zuwenig Antworten gegeben)
            inputs.forEach( item => {
                if (check == true) {
                    quarter++;
                    if (item.checked == true) { //Pr??fen ob Input Feld gechecked, wenn ja Pr??fsumme erh??hen
                        answered++
                    }
                    //Falls die Pr??fsumme f??r eine Frage nicht 1 betrifft wurden entweder zu viele oder zu wenig H??ckchen gesetzt
                    if(answered < 1  && quarter == 4 ) {
                        console.log("Nicht alle Fragen beantwortet")
                        check = false
                    } else if (answered > 1 && quarter == 4 )  {
                        console.log("Bitte geben Sie pro Frage nur eine Antwort an")
                        
                        check = false;
                    }
                    if( quarter == 4 ){
                        quarter = 0;
                        answered = 0;
                    }
                }
            })
            return check;
        }
        var data = [];
        function save() {
            console.log(checkAnswers())
            //if( checkAnswers() ){
                inputs.forEach( saveData )
                //}
            data.forEach( item => console.log( item ))
            } 
        function deleteAnswers(){
            inputs.forEach( item => {
                if( item.checked == true ){
                    item.checked = false;
                }
            })
        }
        function saveData( item, index ){
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
        }
    </script>
</body>

</html>