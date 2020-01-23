<?php

//FileBrowerFiles

require_once "helpers.php";

echo "\n===== Menu =====";
echo "\n-> Opção 1 - Procurar ficheiros por extensão e diretoria.";
echo "\n-> Opção 2 - Procura personalizada.";
echo "\n-> Opção 3 - Mover ficheiros entre pastas.";
echo "\n-> Opção 0 - Sair";

echo "\nQual a opão: ";
fscanf(STDIN, "%d\n", $number);
while ($number != 1 && $number != 2 && $number != 3 && $number != 0) {
    echo "\nQual a opção(1, 2 ou 0 Sair): ";
    fscanf(STDIN, "%d\n", $number);
}
switch ($number) {
    case "1":
        echo "\n-> Opção 1 - Procurar ficheiros por extensão e diretoria.";
        escolherOpcaoUm();
        break;
    case "2":
        echo "\n***** PROCURA PERSONALIZADA *****";
        echo "\n-> Opção 1 - Procurar ficheiro mais recente.";
        echo "\n-> Opção 2 - Procurar ficheiro maior.";
        echo "\n-> Opção 3 - Procurar ficheiro mais pequeno.";
        echo "\n-> Opção 4 - Procurar ficheiro mais antigo.";
        echo "\n-> Opção 0 - Sair";

        echo "\nQual a opão: ";

        fscanf(STDIN, "%d\n", $number);

        while ($number != 1 && $number != 2  && $number != 3 && $number != 4 && $number != 0) {
            echo "\nQual a opção(1, 2, 3, 4 ou 0 Sair): ";
            fscanf(STDIN, "%d\n", $number);
        }
        switch ($number) {
            case "1":
                echo "\n-> Opção 1 - Procurar ficheiro mais recente.";
                escolherOpcao(1);
                break;
            case "2":
                echo "\n-> Opção 2 - Procurar ficheiro maior.";
                escolherOpcao(2);
                break;
            case "3":
                echo "\n-> Opção 3 - Procurar ficheiro mais pequeno.";
                escolherOpcao(3);
                break;
            case "4":
                echo "\n-> Opção 4 - Procurar ficheiro mais antigo.";
                escolherOpcao(4);
                break;
            case "0":
                exit();
                break;
            default:
                echo "Apenas pode escolher 1, 2, 3, 4e 0.";
        }
        break;
    case "3":
        moverFicheiros();
        break;
    case "0":
        exit();
        break;
    default:
        echo "Apenas pode escolher 1, 2 e 0.";
}
