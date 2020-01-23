<?php

function escolherOpcaoUm()
{
    echo "\n";
    $caminho = readline("Escolha a unidade onde pesquisar (c:\pastaOrigem): ");
    echo "\n";
    $extensao = readline("Escolha a extensão a pesquisar(não inserir o ponto da extensão): ");
    echo "\n";
    $subPastas = strtolower(readline("Deseja pesquisar em sub-pastas? (true,false): "));
    echo "\n";

    if (!empty($caminho) && !empty($extensao) && !empty($subPastas)) {
        if ($subPastas != "true" && $subPastas != "false") {
            echo "Apenas pode escolher true ou false.";
            escolherOpcaoUm();
        } else {
            if ($subPastas === "true") {
                $aFsos = procurarFicheiro($caminho, $extensao, true);
            } else if ($subPastas === "false") {
                $aFsos = procurarFicheiro($caminho, $extensao, false);
            }
            echo fsosToString($aFsos);
        }
    } else {
        echo "\nTêm de preencher todos os campos.";
        escolherOpcaoUm();
    }
}


function escolherOpcao($opcao)
{
    echo "\n";
    $caminho = readline("Escolha a unidade onde pesquisar (c:\pastaOrigem): ");
    echo "\n";
    $extensao = readline("Escolha a extensão a pesquisar(não inserir o ponto da extensão): ");
    echo "\n";
    $subPastas = strtolower(readline("Deseja pesquisar em sub-pastas? (true,false): "));
    echo "\n";
    if (!empty($caminho) && !empty($extensao) && !empty($subPastas)) {
        if ($subPastas != "true" && $subPastas != "false") {
            echo "Apenas pode escolher true ou false.";
            escolherOpcaoUm();
        } else {
            try {
                if ($subPastas === "true") {
                    $aFsos = procurarFicheiro($caminho, $extensao, true);
                } else if ($subPastas === "false") {
                    $aFsos = procurarFicheiro($caminho, $extensao, false);
                }
                if ($opcao === 1)
                    sortFileSystemObjects($aFsos, "byCreationDate");
                else if ($opcao === 2)
                    sortFileSystemObjects($aFsos, "bySizeAsc");
                else if ($opcao === 3)
                    sortFileSystemObjects($aFsos, "bySizeDesc");
                else if ($opcao === 4)
                    sortFileSystemObjects($aFsos, "byCreationDateDesc");
                $iSlotOfTheFinalObject = count($aFsos) - 1;
                $oBiggestSize = $aFsos[$iSlotOfTheFinalObject];
                echo converterFrase($oBiggestSize);
            } catch (Exception $e) {
                exit();
            }
        }
    } else {
        echo "\nTêm de preencher todos os campos.";
        escolherOpcao($opcao);
    }
}

function moverFicheiros()
{
    echo "\n";
    $caminhoOrigem = readline("Escolha a localização do ficheiro a copiar (c:\pastaOrigem\.nomeFile.ext): ");
    echo "\n";
    $file = basename($caminhoOrigem);
    $caminhoDestino = readline("Escolha onde deseja adicionar o ficheiro (c:\pastaDestino): ");
    $caminhoDestino = $caminhoDestino . "/" . $file;
    echo "\n";
    rmove($caminhoOrigem, $caminhoDestino);
}

function rmove($src, $dst)
{
    if (file_exists($src)) {
        if (file_exists($dst)) {
            echo "\nFicheiro de destino já existe.\n";
        } else {
            mkdir(dirname($dst), 0777, true);
            copy($src, $dst);
            echo "\nFicheiro criado com sucesso.\n";
        }
    } else {
        echo "\nFicheiro NÃO existe.\n";
    }
}

// função onde vai ao caminho desejado, pela extensão procurar, e se deseja procurar em sub-pastas ou não
function procurarFicheiro(string $caminho, string $extensaoFicheiro, bool $procuraSubPastas = true)
{
    $aRet = []; //col de objetos do sistema de ficheiros (FS)
    // verifica se o caminho existe
    $bCaution0 = file_exists($caminho);
    if ($bCaution0) {
        $oFSNavigator = new DirectoryIterator($caminho);
        if ($oFSNavigator) {
            foreach ($oFSNavigator as $o) {
                //@return bool — true if a directory, false otherwise.
                $bIsDir = $o->isDir();
                //Determine if current DirectoryIterator item is '.' or '..'
                $bIsDot = $o->isDot();
                //true if the file exists and is a regular file (not a link), false otherwise.
                $bIsFile = $o->isFile();
                //a string containing the file extension, or an empty string if the file has no extension.
                $strExt = $o->getExtension();
                // extrair a extensão do ficheiro
                $isExtension = strcasecmp($strExt, $extensaoFicheiro) === 0;
                if ($bIsFile && $isExtension) {
                    $aRet[] = clone ($o);
                }

                // Se desejar pesquisar em sub-Pastas executa este IF
                if ($procuraSubPastas && $bIsDir && !$bIsDot) {
                    $strSubDir = $o->getRealPath();
                    $subEntries = procurarFicheiro($strSubDir, $extensaoFicheiro, $procuraSubPastas);
                    $aRet = array_merge($aRet, $subEntries);
                } //if
            }
        }  //if 
    }  //if
    return $aRet;
} //procurarFicheiro

/*
 * representar enquanto frase um só objecto do tipo DirectorIterator
 */
function converterFrase(DirectoryIterator $p)
{
    $strRet = "";

    $bCaution0 = !empty($p);
    if ($bCaution0) {
        $iSize = $p->getSize(); //tamanho em bytes
        $iSizeGbs = $iSize / 1024 / 1024 / 1024;
        $iSizeMbs = $iSize / 1024 / 1024;
        $aTime = date("d-m-Y H:i:s", $p->getATime());
        $cTime = date("d-m-Y H:i:s", $p->getCTime());
        $mTime = date("d-m-Y H:i:s", $p->getMTime());
        $strRealPath = $p->getRealPath();
        $strBasename = $p->getBasename();
        $strExt = $p->getExtension();

        $strRet = sprintf(
            "%s [%d byte(s)/ %d Mbytes/ %d GBytes]\nCaminho do ficheiro: %s\nData Criação: %s\nData de Modificação: %s\nÚlimo Acesso: %s\nExtensão: .%s\n" . PHP_EOL,
            $strBasename,
            $iSize,
            $iSizeMbs,
            $iSizeGbs,
            $strRealPath,
            $cTime,
            $mTime,
            $aTime,
            $strExt
        );
        return $strRet;
    } //bCaution0
    return $strRet;
} //converterFrase

/*
 * representar enquanto frase uma coleção (array) de objetos DirectoryIterator
 * cada objeto saberá representar-se via converterFrase
 */
function fsosToString(array $pFsos)
{
    $strRet = "";
    foreach ($pFsos as $o) $strRet .= converterFrase($o);
    return $strRet;
} //fsosToString


// função onde dizemos que função queremos utilizar, e qual dos criterios desejamos utilizar.
function sortFileSystemObjects(array &$p, string $pCriterion)
{
    usort($p, $pCriterion);
} //sortFileSystemObjects

function bySizeAsc($pa, $pb)
{
    $iSizeA = $pa->getSize();
    $iSizeB = $pb->getSize();
    if ($iSizeA > $iSizeB) return +1;
    if ($iSizeA < $iSizeB) return -1;
    return 0;
} //bySizeAsc

function bySizeDesc($pa, $pb)
{
    $iSizeA = $pa->getSize();
    $iSizeB = $pb->getSize();
    if ($iSizeA > $iSizeB) return -1;
    if ($iSizeA < $iSizeB) return +1;
    return 0;
} //bySizeDesc

function byCreationDate($pa, $pb)
{
    $iSizeA = $pa->getCTime();
    $iSizeB = $pb->getCTime();
    if ($iSizeA > $iSizeB) return +1;
    if ($iSizeA < $iSizeB) return -1;
    return 0;
} //byCreationDate


function byCreationDateDesc($pa, $pb)
{
    $iSizeA = $pa->getCTime();
    $iSizeB = $pb->getCTime();
    if ($iSizeA > $iSizeB) return -1;
    if ($iSizeA < $iSizeB) return +1;
    return 0;
}//byCreationDateDesc
