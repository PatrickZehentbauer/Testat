<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <title>Testat 2</title>
    <link rel="stylesheet" href="Testat2.css" />
</head>

<body>
    <h3>Fragebogen</h3>
    <label>Nachname</label><Input>
    <label>Vorname</label><Input>
    <br>
    <br>
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
                <div><input type='checkbox'>
                <label>" . getAnswer($i, $counter) . "</label></div>
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
        <button id="save" onclick="save()">Speichern</button>
        <button onclick="deleteAnswers()">Abbrechen</button>
        <br>
        <br>
        <button onclick="showResults()" id="Auswertung" style="visibility : hidden">Auswertung anzeigen</button>
    </footer>

    <script type="text/javascript">
        var answer = document.querySelectorAll("span") //Vierer Paare der Antworten
        var inputs = [];
        var div = document.querySelectorAll("div")
        var shown = false;

        div.forEach(item => {
            for (let i = 0; i < 2; i++) {
                if (item.children[i].tagName == 'INPUT') { //Nur die Input Felder wählen
                    inputs.push(item.children[i])
                }
            }
        })

        function checkAnswers() {
            let check = true //Äußere Prüfvariable da eine forEach-Schleife in Javascript nicht mit 'return false' abgebrochen werden kann
            let quarter = 0; //Zusammenfassung der vier Antowrtfelder einer Frage
            let answered = 0 //innere Prüfsumme (zuviele oder zuwenig Antworten gegeben)
            inputs.forEach(item => {
                if (check == true) {
                    quarter++;
                    if (item.checked == true) { //Prüfen ob Input Feld gechecked, wenn ja Prüfsumme erhöhen
                        answered++
                    }
                    //Falls die Prüfsumme für eine Frage nicht 1 betrifft wurden entweder zu viele oder zu wenig Häckchen gesetzt
                    if (answered < 1 && quarter == 4) {
                        console.log("Nicht alle Fragen beantwortet")
                        check = false
                    } else if (answered > 1 && quarter == 4) {
                        console.log("Bitte geben Sie pro Frage nur eine Antwort an")

                        check = false;
                    }
                    if (quarter == 4) {
                        quarter = 0;
                        answered = 0;
                    }
                }
            })
            return check;
        }
        var data = [];

        function save() {
            if (checkAnswers()) {
                inputs.forEach(createData)
                let button = document.getElementById("Auswertung")
                button.setAttribute("style", "visibility : display")
            }

        }

        function deleteAnswers() {
            inputs.forEach(item => {
                if (item.checked == true) {
                    item.checked = false;
                }
            })
        }

        function createData(item, index) {
            if (item.checked == true) {
                switch (index % 4) {
                    case 0:
                        data.push('a')
                        break;
                    case 1:
                        data.push('b')
                        break;
                    case 2:
                        data.push('c')
                        break;
                    case 3:
                        data.push('d')
                        break;
                }
            }
        }

        var werte = get_statistics( readTextFile("Testat_Pseudodaten.csv" ) );
        var anzahl = 0;

        function showResults() {
            anzahl = werte[ 0 ][ 0 ] + werte[ 0 ][ 1 ] + werte[ 0 ][ 2 ] + werte[ 0 ][ 3 ]
            if (!shown) {
                div.forEach(appendResults)
            }

        }

        function appendResults(item, index) {
            let result = document.createElement("progress")
            result.setAttribute("max", 100);
            let value = werte[Math.floor(index / 4)][index % 4]
            value = 100* (value / anzahl )
            result.setAttribute("value", value);

            item.appendChild(result);
            shown = true;
        }
        //Für jede Anntwortmöglichkeit eine Prozentzahl in die fn-Arrays pushen

        //klappt
        function readTextFile(file) {
            var rawFile = new XMLHttpRequest();
            var allText;
            rawFile.open("GET", file, false);
            rawFile.onreadystatechange = function() {
                if (rawFile.readyState === 4) {
                    if (rawFile.status === 200 || rawFile.status == 0) {
                        allText = rawFile.responseText;
                        //alert(allText);
                    }
                }
            }
            rawFile.send(null);
            return allText;
        }

        function get_statistics(text) {
            var lines = text.split("\r\n");
            var line_split = [];
            var frage_1 = {
                0: 0,
                1: 0,
                2: 0,
                3: 0
            }
            var frage_2 = {
                0: 0,
                1: 0,
                2: 0,
                3: 0
            }
            var frage_3 = {
                0: 0,
                1: 0,
                2: 0,
                3: 0
            }
            var frage_4 = {
                0: 0,
                1: 0,
                2: 0,
                3: 0
            }
            var fragen = [frage_1, frage_2, frage_3, frage_4];

            for (let i = 0; i < lines.length - 1; i++) {
                line_split[i] = lines[i].split(";");
                //console.log(line_split[i]);
            }

            for (let i = 1; i < line_split.length; i++) {
                //console.log(line_split[i][3]);
                var curr_frage = 0;
                for (let j = 3; j < line_split.length; j++) {
                    switch (line_split[i][j]) {
                        case "A":
                            fragen[curr_frage][0] += 1;
                            break;
                        case "B":
                            fragen[curr_frage][1] += 1;
                            break;
                        case "C":
                            fragen[curr_frage][2] += 1;
                            break;
                        case "D":
                            fragen[curr_frage][3] += 1;
                            break;
                        default:
                            break;
                    }
                    curr_frage += 1;
                }
            }
            /* Antworten ausgeben
            for(let i = 0; i < fragen.length; i++) {
                console.log(fragen[i]);
            }
            */
            return fragen;
        }

        var stats = get_statistics(readTextFile("Testat_Pseudodaten.csv"));
        let button = document.getElementById("save");
        button.addEventListener('click', () => {
            saveFile();
            event.stopPropagation();
        })

        async function saveFile() {
            const opts = {
                types: [{
                    description: 'Text file',
                    accept: {
                        'text/plain': ['.txt']
                    },
                }],
            };
            await window.showSaveFilePicker()
                .then(fileHandle => fileHandle.createWritable())
                .then(stream => {
                    stream.write(loadFile()+"jetzt aber")
                        .catch(e => console.log(e))
                    return stream
                })
                .then(stream => stream.close())
                .catch(e => console.log(e))
        }

        
        function loadFile() {
            window.showOpenFilePicker()
            .then(fileHandle => fileHandle[0].getFile())
            .then(file => file.text())
            .then(text => {return text})
            .catch(e => console.log(e))
        }

        //Hier die Daten im richtigen Format angeben (als 1 Zeile)
        function readData() {
            let str = Array()
            fields.forEach((item, i) => {
                str[i] = `${labels[i].innerHTML}:${item.value}`
            })

            return str.join("\n")
        }
    </script>

</body>

</html>