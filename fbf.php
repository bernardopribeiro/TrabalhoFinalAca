<?php

require_once "helpers.php";

echo "\n===== Menu =====";
echo "\n-> Opção 1 - Procurar ficheiros por extensão e diretoria.";
echo "\n-> Opção 2 - Procura personalizada.";
echo "\n-> Opção 0 - Sair";

echo "\nEnter a number: ";
fscanf(STDIN, "%d\n", $number);

switch ($number) {
    case "1":
        escolherOpcaoUm();
        break;
    case "2":
        echo "\n***** Sub Menu *****";
        echo "\n-> Opção 1 - Procurar ficheiro mais recente.";
        echo "\n-> Opção 2 - Procurar ficheiro maior.";
        echo "\n-> Opção 3 - Procurar ficheiro mais pequeno.";
        echo "\n-> Opção 4 - Procurar ficheiro mais antigo.";
        echo "\n-> Opção 0 - Sair";

        echo "\nEnter a number: ";
        fscanf(STDIN, "%d\n", $number);

        switch ($number) {
            case "1":
                escolherOpcao(1);
                break;
            case "2":
                escolherOpcao(2);
                break;
            case "3":
                escolherOpcao(3);
                break;
            case "4":
                escolherOpcao(4);
                break;
            case "0":
                exit();
                break;
            default:
                echo "Apenas pode escolher 1, 2,3,4 e 0.";
        }
        break;
    case "0":
        exit();
        break;
    default:
        echo "Apenas pode escolher 1, 2 e 0.";
}
