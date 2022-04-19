<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
    <h1>Gerar números aleatórios</h1>
    <form action="" method="post">
        <label for="val">Valores:</label>
        <input type="number" name="val" id="val">

        <br><br>

        <label for="ini">Início:</label>
        <input type="number" name="ini" id="ini">

        <br><br>

        <label for="fim">Fim:</label>
        <input type="number" name="fim" id="fim">

        <br><br>

        <label for="arq">Arquivo: </label>
        <input type="text" name="arq" id="arq">

        <br><br>

        <input type="submit" value="Gerar">
    </form>

    <?php
        # Gerar números aleatórios
        $val = isset($_POST['val']) ? $_POST['val'] : 0;
        $ini = isset($_POST['ini']) ? $_POST['ini'] : 0;
        $fim = isset($_POST['fim']) ? $_POST['fim'] : 0;
        $arq = isset($_POST['arq']) ? $_POST['arq'] : null;

        if($arq){
            $valores = [];
            for ($i=0; $i<=$val; $i++){
                array_push($valores, rand($ini, $fim));
            }
            
            $dados_json = json_encode($valores);
            $arq = str_replace(".json", "", $arq);
            $fp = fopen("$arq.json", "w");
            fwrite($fp, $dados_json);
            fclose($fp);
        }
    ?>

    <br><br>
    <hr>
    <br><br>

    <h1>Realizar cálculos</h1>
    <form action="" method="post">
        <label for="abrir">Arquivo:</label>
        <input type="text" name="abrir" id="abrir">
        
        <br><br>
        
        <input type="submit" value="Abrir">
    </form>
    
    <br><br>

    <?php
        # Realizar cálculos
        $abrir = isset($_POST['abrir']) ? $_POST['abrir'] : null;
        
        if($abrir){
            $abrir = str_replace(".json", "", $abrir);
            $arquivo = file_get_contents("$abrir.json");
            $json = json_decode($arquivo);



            $maior = max($json);
            $menor = min($json);
            
            $pares = [];
            $impares = [];
            $soma = 0;
            $primos = [];

            $ordenado = $json;
            sort($ordenado);
            $mediana = $ordenado[count($json)/2];
            
            function primo($valor){
                if ($valor == 1){
                    return True;
                }
                for ($i = 2; $i <= $valor/2; $i++){ 
                    if ($valor % $i == 0) 
                        return False; 
                } 

                return True; 
            } 

            foreach($json as $value){
                if($value%2 == 0)
                    array_push($pares, $value);
                else
                    array_push($impares, $value);
                
                $soma += $value;

                if(primo($value))
                    array_push($primos, $value);

            }

            $media = $soma/count($json);
            $acima_media = [];
            $abaixo_media = [];
            
            foreach($json as $value){
                if($value>$media)
                    array_push($acima_media, $value);
                else
                    array_push($abaixo_media, $value);
            }
            
            if($abrir){
                echo "Maior: $maior<br><br>";
                echo "Menor: $menor <br><br>";
                echo "Pares: ".implode(", ", $pares)."<br><br>";
                echo "Ímpares: ".implode(", ", $impares)."<br><br>";
                echo "Soma: $soma <br><br>";
                echo "Média: $media<br><br>";
                echo "Acima da média: ".implode(", ", $acima_media)."<br><br>";
                echo "Abaixo da média: ".implode(", ", $abaixo_media)."<br><br>";
                echo "Primos: ".implode(", ", $primos)."<br><br>";
                echo "Mediana: $mediana<br><br>";
            }
        }

    ?>

    <h2>Gráfico</h2>
    
    <script type="text/javascript">
        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

            // Create the data table.
            var data = google.visualization.arrayToDataTable([
                ['Índice', 'valor'],
                <?php
                    $i = 0;
                    foreach($json as $value){
                        echo "['$i', $value],";
                        $i += 1;
                    }
                ?>
            ]);

            // Set chart options
            var options = {'title':'Valores Gerados',
                            hAxis: {
                                title: 'Índice'
                            },
                            vAxis: {
                                title: 'Valor'
                            },
                            'width':400,
                            'height':300};

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>

    <div id="chart_div"></div>

</body>
</html>