<?php

function escolherOpcaoUm()
{
    echo "\nEscolha a unidade onde pesquisar: ";
    //STDIN é um apontador de escrita
    $caminho = trim(fgets(STDIN));
    echo "\nEscolha a extensão a pesquisar(não inserir o ponto da extensão): ";
    $extensao = trim(fgets(STDIN));
    echo "\nDeseja pesquisar em sub-pastas? (true,false): ";
    $subPastas = trim(fgets(STDIN));
    echo "\n";

    if (!empty($caminho) && !empty($extensao) && !empty($subPastas)) {
        if ($subPastas === "true") {
            $aFsos = getAllFileSystemObjectsStartingAt($caminho, $extensao, true);
        } else if ($subPastas === "false") {
            $aFsos = getAllFileSystemObjectsStartingAt($caminho, $extensao, false);
        }
        echo fsosToString($aFsos);
    } else {
        echo "\nInsira o endereço onde deseja pesquisar. EX: C:/";
    }
}

function escolherOpcao($opcao)
{
    echo "\nEscolha o caminho a pesquisar: ";
    $caminho = trim(fgets(STDIN));
    echo "\nEscolha a extensão a pesquisar(não inserir o ponto da extensão): ";
    $extensao = trim(fgets(STDIN));
    echo "\nDeseja pesquisar em sub-pastas? (true,false): ";
    $subPastas = trim(fgets(STDIN));
    echo "\n";
    if (!empty($caminho) && !empty($extensao) && !empty($subPastas)) {
        try {
            if ($subPastas === "true") {
                $aFsos = getAllFileSystemObjectsStartingAt($caminho, $extensao, true);
            } else if ($subPastas === "false") {
                $aFsos = getAllFileSystemObjectsStartingAt($caminho, $extensao, false);
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
            echo fsoToString($oBiggestSize);
        } catch (Exception $e) {
            exit();
        }
    } else {
        echo "\nInsira o endereço onde deseja pesquisar. EX: C:/";
    }
}

// função onde vai ao caminho desejado, pela extensão procurar, e se deseja procurar em sub-pastas ou não
function getAllFileSystemObjectsStartingAt(string $caminho, string $pFileExtension, bool $pbRecursive = true)
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
                $isExtension = strcasecmp($strExt, $pFileExtension) === 0;
                if ($bIsFile && $isExtension) {
                    $aRet[] = clone ($o);
                }

                // Se desejar pesquisar em sub-Pastas executa este IF
                if ($pbRecursive && $bIsDir && !$bIsDot) {
                    $strSubDir = $o->getRealPath();
                    $subEntries = getAllFileSystemObjectsStartingAt($strSubDir, $pFileExtension, $pbRecursive);
                    $aRet = array_merge($aRet, $subEntries);
                } //if
            }
        } //if 
    } //if
    return $aRet;
} //getAllFileSystemObjectsStartingAt

/*
 * representar enquanto frase um só objecto do tipo DirectorIterator
 */
function fsoToString(DirectoryIterator $p)
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
} //fsoToString

/*
 * representar enquanto frase uma coleção (array) de objetos DirectoryIterator
 * cada objeto saberá representar-se via fsoToString
 */
function fsosToString(array $pFsos)
{
    $strRet = "";
    foreach ($pFsos as $o) $strRet .= fsoToString($o);
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
