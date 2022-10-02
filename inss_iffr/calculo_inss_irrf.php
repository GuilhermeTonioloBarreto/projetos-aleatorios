<?php 
    function checarDados($data){
        return htmlspecialchars($data);
    }

    function desconto_inss($salario){
        define('INSS_VALORES', array(0, 1212, 2427.35, 3641.03, 7087.22));
        define('INSS_PORCENTAGEM', array(0, 0.075, 0.09, 0.12, 0.14));
        
        $inss = 0;
    
        $i = 0;
        $encerrar_loop = false;
    
        while(!$encerrar_loop){
            if($i == count(INSS_VALORES)){
                break;
            }elseif($salario < INSS_VALORES[$i]){
                $inss += INSS_PORCENTAGEM[$i]*($salario - INSS_VALORES[$i - 1]);
                $encerrar_loop = true;
            }else{
                $inss += INSS_PORCENTAGEM[$i]*(INSS_VALORES[$i] - INSS_VALORES[$i - 1]);
            }
            $i++;
        }
    
        return round($inss, 2);
    }


    function desconto_irrf($salario_bruto, $num_dependentes = 0){
        // constantes definidas pelo sistema
        define('DESCONTO_DEPENDENTES', 189.59);
        define('IRRF_SALARIO', array(0, 1903.98, 2826.65, 3751.05, 4664.68));
        define('IRRF_PORCENTAGEM', array(0, 0.075, 0.15, 0.225, 0.275));
        define('IRRF_DEDUCAO', array(0, 142.8, 354.8, 636.13, 869.36));

        // variaveis definidas 
        $desconto_irrf = 0;
        $comp_array = count(IRRF_SALARIO);

        // invoca funcao que calcula o desconto do inss
        $desconto_inss = desconto_inss($salario_bruto);

        $base_calculo_irrf = $salario_bruto - $desconto_inss - $num_dependentes*DESCONTO_DEPENDENTES;
        
        for($i = 0; $i < $comp_array; $i++){    
            if($base_calculo_irrf < IRRF_SALARIO[$i]){
                $desconto_irrf = $base_calculo_irrf*IRRF_PORCENTAGEM[$i-1] - IRRF_DEDUCAO[$i-1];
                break;
            }
            $desconto_irrf = $base_calculo_irrf*IRRF_PORCENTAGEM[$comp_array - 1] - IRRF_DEDUCAO[$comp_array - 1];
        }

        return round($desconto_irrf, 2);

    }  
?>

<html>
    <body>
        <form action= "<?php $_PHP_SELF ?>" method="POST">
            <p>Digite aqui o salário bruto do mês: </p>
            <input type="text" name="salario_bruto">
            <br/>

            <p>Digite aqui o número de dependentes: </p>
            <input type="number" name="numero_dependentes">
            <br/>
            <br/>

            <input type="submit" value="Calcular">
        </form>
        

        <?php
            $salario_bruto = checarDados($_POST['salario_bruto']);
            $num_dependentes = checarDados($_POST['numero_dependentes']);
            if($salario_bruto){
                $desconto_inss = desconto_inss($salario_bruto);
                $desconto_irrf = desconto_irrf($salario_bruto, $num_dependentes);
            
                echo("<p>Desconto INSS: $desconto_inss</p>");
                echo("<p>Desconto IRRF: $desconto_irrf</p>");
            }
            
            exit();  
        ?>

        

    </body>
</html>


