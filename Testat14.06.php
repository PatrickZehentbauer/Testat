<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <title>Testat 2</title>
    <link rel="stylesheet" href="Testat2.css" />
</head>

<body>
    <h3>Fragebogen</h3>
    <label>Nachname</label><Input id="nachname">
    <label>Vorname</label><Input id="vorname">
    <br>
    <br>
    <?php

    $fragen = array(
        "0" => "I want ...",
        "1" => "Einige Monate haben 31 Tage, andere 30. Wie viele Monate haben 28 Tage?",
        "2" => "Welcher ist der erste Buchstabe des Alphabets?",
        "3" => "Würden sie zufällig antworten, wie hoch ist die Chance, richtig zu antworten?"
    );
    $null = array(
        //Antworten auf erste Frage
        "a" => "To break free",
        "b" => "To ride my bicycle",
        "c" => "It all",
        "d" => "To make a supersonic man out of you",
    );
    $eins = array(
        //Antworten auf zweite Frage
        "a" => "Ein Monat",
        "b" => "Erst drei Jahre keiner, dann in einem Jahr ein Monat",
        "c" => "Alle zwölf Monate",
        "d" => "Keiner",

    );
    $zwei = array(
        //Antworten auf dritte Frage
        "a" => "A: B",
        "b" => "B: A",
        "c" => "C: C",
        "d" => "D: Keiner der obigen",

    );
    $drei = array(
        //Antworten auf evierte Frage
        "a" => "25%",
        "b" => "0%",
        "c" => "50%",
        "d" => "25%",

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
        <button id="save" onclick="save()">Speichern</button>
        <button onclick="deleteAnswers()">Abbrechen</button>
        <br>
        <br>
        <button onclick="showResults()" id="Auswertung" style="visibility : hidden">Auswertung anzeigen</button>
    </footer>

    <script type="text/javascript">
        //Globale Variablen
        var answer = document.querySelectorAll("span") //Vierer Paare der Antworten
        var inputs = [];
        var div = document.querySelectorAll("div")
        var shown = false;
        var checkedAnswers = [];
        var data = [];
        var werte = get_statistics(readTextFile("Testat_Pseudodaten.csv"));
        var anzahl = 0;

        div.forEach(item => {
            for (let i = 0; i < 2; i++) {
                if (item.children[i].tagName == 'INPUT') { //Nur die Input Felder wählen
                    inputs.push(item.children[i])
                }
            }
        })

        //Funktionen

        //Prüft, ob es genau eine Antwort für jede Frage gibt
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

        //Wird bei click auf den Button "Speichern" ausgeführt
        function save() {
            if (checkAnswers()) {
                inputs.forEach(createData)
                let button = document.getElementById("Auswertung")
                button.setAttribute("style", "visibility : display")
                saveFile();
                event.stopPropagation();
            }

        }

        //Wird bei click auf den Button "Abbrechen" ausgeführt
        function deleteAnswers() {
            inputs.forEach(item => {
                if (item.checked == true) {
                    item.checked = false;
                }
            })
        }

        //Füllt das Array data mit den Antworten
        function createData(item, index) {
            if (item.checked == true) {
                switch (index % 4) {
                    case 0:
                        data.push('A')
                        break;
                    case 1:
                        data.push('B')
                        break;
                    case 2:
                        data.push('C')
                        break;
                    case 3:
                        data.push('D')
                        break;
                }
            }
        }

        //Erzeugt die Statistik
        function showResults() {
            anzahl = werte[0][0] + werte[0][1] + werte[0][2] + werte[0][3]
            if (!shown) {
                div.forEach(appendResults)
            }
        }

        //Erzeugt Balken für die Statistik
        function appendResults(item, index) {
            let result = document.createElement("progress")
            result.setAttribute("max", 100);
            let value = werte[Math.floor(index / 4)][index % 4]
            value = 100 * (value / anzahl)
            result.setAttribute("value", value);
            item.appendChild(result);
            shown = true;
        }

        //Gibt den Inhalt der Datenbank aus
        function readTextFile(file) {
            var rawFile = new XMLHttpRequest();
            var allText;
            rawFile.open("GET", file, false);
            rawFile.onreadystatechange = function() {
                if (rawFile.readyState === 4) {
                    if (rawFile.status === 200 || rawFile.status == 0) {
                        allText = rawFile.responseText;
                    }
                }
            }
            rawFile.send(null);
            return allText;
        }

        //Gibt ein Array von Dictionarys mit allen Antworten für die Statistik aus
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
            }

            for (let i = 1; i < line_split.length; i++) {
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
            return fragen;
        }

        //Erzeugt das Input für eine neue Zeile zum schreiben in die Datenbank
        function createInput() {
            checkedAnswers.push(werte[0][0] + werte[0][1] + werte[0][2] + werte[0][3] + 2)
            checkedAnswers.push(document.getElementById("vorname").value)
            checkedAnswers.push(document.getElementById("nachname").value)
            inputs.forEach(getAnswers);
            return checkedAnswers.join(";")
        }

        //Füllt das Array checkedAnswers mit den Antworten
        function getAnswers(item, index) {
            let ch;
            if (item.checked == true) {
                ch = index % 4;
                switch (ch) {
                    case 0:
                        checkedAnswers.push('A')
                        break;
                    case 1:
                        checkedAnswers.push('B')
                        break;
                    case 2:
                        checkedAnswers.push('C')
                        break;
                    case 3:
                        checkedAnswers.push('D')
                        break;
                }
            }
        }

        //Schreibt die Daten in die Datenbank bei klick auf Speichern

        //Anmerkung: Wir haben versucht, anstatt der Methode aus der Vorlesung (in abgeänderter Form) mittels Cookies,
        //Requests und anderen Tricks die Daten an den PHP Teil irgendwie zu bekommen. Nach 3 Tagen frustrierender Recherche
        //Ging letztendlich doch nur diese Methode. (Im Internet wird stets behauptet Javascript kann nicht in Dateien schreiben¿?)

        //Wählen Sie beim drücken auf Speichern immer die Datei "Testat_Pseudodaten.csv", sonst funktioniert es nicht
        async function saveFile() {
            let str = readTextFile("Testat_Pseudodaten.csv");
            checkedAnswers = [];
            await window.showSaveFilePicker()
                .then(fileHandle => fileHandle.createWritable())
                .then(stream => {
                    stream.write(str + "\n" + createInput() + "\n")
                        .catch(e => console.log(e))
                    return stream
                })
                .then(stream => stream.close())
                .catch(e => console.log(e))
        }
    </script>
</body>

</html>