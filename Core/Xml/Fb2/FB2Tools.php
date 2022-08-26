<?php

/**
 *
 * @author acround
 */

namespace analib\Core\Xml\Fb2;

class FB2Tools
{

    const MULTI_AUTOR_FOLDER_NUM          = 3;
    const MULTI_AUTOR_FOLDER              = 'Сборник';
    const MODE_DIRECTORY_ACCESS           = 0777;
    const MODE_FILE_ACCESS                = 0666;
    const MODE_AUTO_SEQUENCE              = '/a';
    const MODE_AUTO_SEQUENCE_OTHER        = '/o';
    const MODE_AUTO_SEQUENCE_NUMBER_BEGIN = '/nb';
    const MODE_AUTO_SEQUENCE_NUMBER       = '/n';
    const MODE_AUTO_SEQUENCE_TREE         = '/t';

    static protected $blockTags     = array(
        'description',
        'title-info',
        'genre',
        'author',
        'first-name',
        'middle-name',
        'last-name',
        'book-title',
        'annotation',
        'date',
        'coverpage',
        'lang',
        'document-info',
        'nickname',
        'program-used',
        'src-ocr',
        'src-lang',
        'translator',
        'id',
        'version',
        'publish-info',
        'book-name',
        'publisher',
        'city',
        'year',
        'isbn',
        'body',
        'section',
        'title',
        'p',
        'poem',
        'stanza',
        'v',
        'cite',
        'binary',
        'sequence',
        'FictionBook',
    );
    static protected $translitMap   = array(
        'а'  => 'a',
        'б'  => 'b',
        'в'  => 'w',
        'г'  => 'g',
        'д'  => 'd',
        'е'  => 'e',
        'ё'  => 'jo',
        'ж'  => 'hz',
        'з'  => 'z',
        'и'  => 'i',
        'й'  => 'j',
        'к'  => 'k',
        'л'  => 'l',
        'м'  => 'm',
        'н'  => 'n',
        'о'  => 'o',
        'п'  => 'p',
        'р'  => 'r',
        'с'  => 's',
        'т'  => 't',
        'у'  => 'u',
        'ф'  => 'f',
        'х'  => 'h',
        'ц'  => 'c',
        'ч'  => 'ch',
        'ш'  => 'sh',
        'щ'  => 'sch',
//        'ъ'  => '`',
        'ы'  => 'y',
//        'ь'  => '`',
        'э'  => 'e',
        'ю'  => 'ju',
        'я'  => 'ja',
        'А'  => 'A',
        'Б'  => 'B',
        'В'  => 'W',
        'Г'  => 'G',
        'Д'  => 'D',
        'Е'  => 'E',
        'Ё'  => 'JO',
        'Ж'  => 'HZ',
        'З'  => 'Z',
        'И'  => 'I',
        'Й'  => 'J',
        'К'  => 'K',
        'Л'  => 'L',
        'М'  => 'M',
        'Н'  => 'N',
        'О'  => 'O',
        'П'  => 'P',
        'Р'  => 'R',
        'С'  => 'S',
        'Т'  => 'T',
        'У'  => 'U',
        'Ф'  => 'F',
        'Х'  => 'H',
        'Ц'  => 'C',
        'Ч'  => 'CH',
        'Ш'  => 'SH',
        'Щ'  => 'SCH',
//        'Ъ'  => '`',
        'Ы'  => 'Y',
//        'Ь'  => '`',
        'Э'  => 'E',
        'Ю'  => 'JU',
        'Я'  => 'JA',
        '\'' => '`',
        '"'  => '``',
        '\\' => '.',
        '/'  => '.',
        '|'  => '.',
        '?'  => '',
        ':'  => '-',
        ' '  => '_',
    );
    static protected $translitAllow = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890()!-=+.,[]{}_\'';
    protected $startDirectory       = null;
    protected $workDirectory        = null;
    protected $params               = '';
    protected $params2              = null;
    protected $cont                 = array();
    protected $out                  = array();
    protected $dirTree              = [];
    protected $genresList           = [];

    public function __construct()
    {
        $this->workDirectory = realpath('./');
    }

    public static function create()
    {
        return new self();
    }

    public function execute($operation, $params = null, $params2 = null)
    {
        $this->startDirectory = realpath('./');
        $this->params         = $params;
        $this->params2        = $params2;
        $recurrent            = strpos($operation, '@') !== false;
        if ($recurrent) {
            $operation = substr($operation, 1);
        }
        if ($operation) {
            $genresListName = $this->startDirectory . DIRECTORY_SEPARATOR . 'genres.txt';
//            if (file_exists($genresListName)) {
//                $genresListFile = explode("\n", file_get_contents($genresListName));
//            } else {
//                $genresListFile = [];
//            }
//            foreach ($genresListFile as $row) {
//                $splittedRow = explode('=', trim($row));
//                $this->genresList[$splittedRow[0]] = $splittedRow[1];
//            }
            switch ($operation) {
                // help
                case 'help':
                case 'h':
                case '?':
                    $this->help();
                    break;
                // files Up
                case 'u':
                    $this->filesUp();
                    break;
                // split
                case 'l':
                    $this->runDir('splitFile', $recurrent);
                    break;
                // rename
                case 'r':
                    $this->runDir('renameFile');
                    break;
                // sequence
                case 's':
                    $this->runDir('sequenceFile', $recurrent);
                    $this->dirTree = [dirname($this->workDirectory)];
                    if (!$this->params) {
                        $this->outInfo();
                    }
                    break;
                // drop sequence
                case 's-':
                    $this->runDir('dropSequenceFile', $recurrent);
                    break;
                // publish sequence
                case 'p':
                    $this->runDir('publishSequenceFile', $recurrent);
                    if (!$this->params) {
                        $this->outInfo();
                    }
                    break;
                // drop publish sequence
                case 'p-':
                    $this->runDir('dropPublishSequenceFile', $recurrent);
                    break;
                // image to binary
                case 'ib':
                    $this->image2BinaryFile($this->params);
                    break;
                // images to binary
                case 'ib+':
                    $this->runDir('image2BinaryFile');
                    break;
                // get cover
                case 'cv':
                    $this->getCoverFile($this->params);
                    break;
                // fb2 to fb2.zip
                case 'z':
                    $this->runDir('zipFile', $recurrent);
                    break;
                // title
                case 't':
                    $this->runDir('titleFile', $recurrent);
                    $this->outInfo();
                    break;
                // info
                case 'i':
                    switch ($this->params) {
                        case 't':
                        case 'title':
                            $this->runDir('titleFile', $recurrent);
                            $this->outInfo();
                            break;
                        case 'a':
                        case 'author':
                            $this->runDir('authorFile', $recurrent);
                            $this->outInfo();
                            break;
                        case 's':
                        case 'seq':
                            $this->params = '';

                            $this->runDir('sequenceFile', $recurrent);
                            $this->outInfo();
                            break;
                        case 'p':
                        case 'pseq':
                            $this->params = '';
                            $this->runDir('publishSequenceFile', $recurrent);
                            $this->outInfo();
                            break;
                        case 'y':
                            $this->params = '';
                            $this->runDir('titleYearFile', $recurrent);
                            $this->outInfo();
                            break;
                        case 'Y':
                            $this->params = '';
                            $this->runDir('publishYearFile', $recurrent);
                            $this->outInfo();
                            break;
                    }
                    break;
                case 'y':
                    break;
                case 'Y':
                    $this->runDir('yearPublishFile', $recurrent);
                    break;
                case 'm':
                    $this->runDir('tireFile', $recurrent);
                    break;
                case 'e':
                    $this->runDir('encodingFile', $recurrent);
                    $this->outInfo();
                    break;
                case 'bn':
                    $this->runDir('binaryNormalyse', $recurrent);
                    $this->outInfo();
                    break;
                default :
                    $this->help();
            }
            if ($this->genresList) {
                ksort($this->genresList);
                $genresListFile = [];
                foreach ($this->genresList as $genre => $number) {
                    $genresListFile[] = $genre . '=' . $number;
                }
                file_put_contents($genresListName, implode("\n", $genresListFile));
            }
        }
    }

    protected function outInfo()
    {
        ksort($this->out);
        $out = array();
        foreach ($this->out as $info) {
            foreach ($info as $k => $v) {
                if (!isset($out[$k])) {
                    $out[$k] = 0;
                }
                if ($out[$k] < mb_strlen($v, 'utf-8')) {
                    $out[$k] = mb_strlen($v, 'utf-8');
                }
            }
        }
        foreach ($this->out as $k1 => $info) {
            foreach ($info as $k => $v) {
                if (mb_strlen($this->out[$k1][$k], 'utf-8') < $out[$k]) {
                    $this->out[$k1][$k] .= str_repeat(' ', $out[$k] - mb_strlen($this->out[$k1][$k], 'utf-8'));
                }
            }
        }
        foreach ($this->out as $row) {
            foreach ($row as $k => $v) {
                echo $k . ':' . $v . ';';
            }
            echo "\n";
        }
    }

    protected function help()
    {
        echo 'help, h, ? — эта помощь;' . "\n";
        echo 'i — информация об fb2-документах. Параметры:' . "\n";
        echo '	a — автор(ы);' . "\n";
        echo '	t — заголовок;' . "\n";
        echo '	s — авторская серия;' . "\n";
        echo '	p — издательская серия;' . "\n";
        echo 'u — подьём файлов с нижележащих директорий;' . "\n";
        echo 'l — разбивка fb2-документа по основным block-тегам;' . "\n";
        echo 'e — смена кодировки;' . "\n";
        echo '  Вызов:;' . "\n";
        echo '      e <новая кодировка>' . "\n";
        echo 'r — переименование fb2-документа. Параметры:' . "\n";
        echo '	P — добавление папки издательской серии;' . "\n";
        echo '	S — добавление папки авторской серии;' . "\n";
        echo '	A — добавление папки автора;' . "\n";
        echo '	p — добавление издательской серии в название файла;' . "\n";
        echo '	s — добавление авторской серии в название файла;' . "\n";
        echo '	a — добавление автора в название файла.' . "\n";
        echo '	e, t — перекодировка имени файла в транслит.' . "\n";
        echo '	i — добавление в название данных из секции publish-info:' . "\n";
        echo '		i p — добавление издателя' . "\n";
        echo '		i c — добавление места публикации' . "\n";
        echo '		i y — добавление года публикации' . "\n";
        echo '		i Y — добавление года публикации в начало' . "\n";
        echo 's — запись авторской серии. Параметр:' . "\n";
        echo '	серия;' . "\n";
        echo '	' . self::MODE_AUTO_SEQUENCE . ' — автоматическое добавление серии из файла .sequence или названия папки.' . "\n";
        echo '	' . self::MODE_AUTO_SEQUENCE_OTHER . ' — перенос серии из publish/sequence.' . "\n";
        echo '	' . self::MODE_AUTO_SEQUENCE_NUMBER_BEGIN . ' — взять серию из начала названия файла.' . "\n";
        echo '	' . self::MODE_AUTO_SEQUENCE_NUMBER . ' — взять серию из названия файла (не обязательно начала).' . "\n";
        echo '	' . self::MODE_AUTO_SEQUENCE_TREE . ' — построить список серий названий папок и файла.' . "\n";
        echo '	- — удаление авторской серии;' . "\n";
        echo 's- — удаление авторской серии;' . "\n";
        echo 'p — запись издательской серии. Параметр:' . "\n";
        echo '	серия;' . "\n";
        echo '	' . self::MODE_AUTO_SEQUENCE . ' — автоматическое добавление серии из файла .publishSequence или названия папки.' . "\n";
        echo '	' . self::MODE_AUTO_SEQUENCE_OTHER . ' — перенос серии из title-info/sequence.' . "\n";
        echo '	' . self::MODE_AUTO_SEQUENCE_NUMBER_BEGIN . ' — взять серию из начала названия файла.' . "\n";
        echo '	' . self::MODE_AUTO_SEQUENCE_NUMBER . ' — взять серию из названия файла (не обязательно начала).' . "\n";
        echo '	' . self::MODE_AUTO_SEQUENCE_TREE . ' — построить список серий названий папок и файла.' . "\n";
        echo '	- — удаление издательской серии;' . "\n";
        echo 'p- — удаление издательской серии;' . "\n";
        echo 't — вывод названия книги;' . "\n";
        echo 'Y — запись года публикации. Параметр:' . "\n";
        echo '	' . self::MODE_AUTO_SEQUENCE_NUMBER . ' — взять год из названия файла.' . "\n";
        echo 'ib — создать binary из изображения;' . "\n";
        echo 'ib+ — создать binary из изображений в папке;' . "\n";
        echo 'cv — вытащить обложку в виде файла;' . "\n";
        echo 'bn <length>- нормализовать строки изображений по длине <length>';
        echo 'z — зазиповать fb2-документы.' . "\n";
        echo 'm — поменять `–` на `—`' . "\n";
        echo "\n";
        echo 'Для операций i, l, s, s-, p, p-, z можно указать рекурсивное выполнение при помощи символа «@» перед обозначением операции:' . "\n";
        echo 'fb2 @i t' . "\n";
        echo "\n";
        echo "\n";
    }

    protected function setWorkDirectory($dir = null)
    {
        if ($dir && file_exists($dir) && is_dir($dir)) {
            $this->workDirectory = realpath($dir);
        } else {
            $this->workDirectory = realpath('./');
        }
    }

    protected function loadContent()
    {
        $this->cont = array();
        $dir        = $this->workDirectory;
        if (file_exists($dir . DIRECTORY_SEPARATOR . 'cont.txt')) {
            $cont = file($dir . DIRECTORY_SEPARATOR . 'cont.txt');
            foreach ($cont as $line) {
                $lineArr = explode('|', trim($line));
                $contEl  = array();
                foreach ($lineArr as $el) {
                    $elArr = explode('=', $el);
                    if (count($elArr) == 2) {
                        $contEl[$elArr[0]] = $elArr[1];
                    }
                }
                $this->cont[] = $contEl;
            }
        }
    }

    protected function getContentByOrder($order)
    {
        if (isset($this->cont[$order])) {
            return $this->cont[$order];
        } else {
            return array();
        }
    }

    protected function getContentByBookTitle($bookTitle)
    {
        $ret = array();
        foreach ($this->cont as $el) {
            if ($el['book-title'] == $bookTitle) {
                echo $el['book-title'] . "\n";
                $ret = $el;
                break;
            }
        }
        return $ret;
    }

    protected function readDir($directory)
    {
        $ret    = [
            'dirs'  => [],
            'files' => [],
        ];
        $handle = opendir($directory);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if (($file === '.') || ($file === '..')) {
                    continue;
                }
                if (!is_readable($directory . DIRECTORY_SEPARATOR . $file)) {
                    continue;
                }
                if ($file == 'lost+found') {
                    continue;
                }
                if (is_dir($file)) {
                    $ret['dirs'][] = $file;
                } else {
                    $ret['files'][] = $file;
                }
            }
            sort($ret['dirs']);
            sort($ret['files']);
        }
        return $ret;
    }

    protected function runDir($method, $recurrent = false)
    {
        $dir = $this->workDirectory;
        \analib\System\Console\Colors::fontBold();
        \analib\System\Console\Colors::colorYellow();
        echo '/---> ' . $dir . "\n";
        \analib\System\Console\Colors::fontBold(false);
        \analib\System\Console\Colors::colorsDefault();
        $this->loadContent();
        if (
            !file_exists($dir) ||
            !is_dir($dir) ||
            is_readable($dir)
        ) {
            $dir = realpath('./');
        }
        $directoryContent = $this->readDir($dir);
        if ($recurrent) {
            foreach ($directoryContent['dirs'] as $directory) {
                $this->workDirectory = $dir . DIRECTORY_SEPARATOR . $directory;
                chdir($this->workDirectory);
                $this->runDir($method, $recurrent);
            }
        }
        $this->workDirectory = $dir;
        chdir($this->workDirectory);
        foreach ($directoryContent['files'] as $file) {
            if (strtolower(substr($file, -4)) === '.fb2') {
                try {
                    $this->$method($dir . DIRECTORY_SEPARATOR . $file);
                } catch (\Exception $ex) {
                    \analib\System\Console\Colors::colorRed();
                    \analib\System\Console\Colors::fontBold();
                    echo "##################################################\n";
                    echo $ex->getMessage() . "\n";
                    echo 'In file ' . $ex->getFile() . "\n";
                    echo 'In line #' . $ex->getLine() . "\n";
                    echo "Trace:\n\n";
                    echo str_replace('\n\n', "\n", $ex->getTraceAsString());
                    echo "##################################################\n";
                    \analib\System\Console\Colors::colorsDefault();
                    \analib\System\Console\Colors::fontBold(false);
                }
            }
        }
        \analib\System\Console\Colors::fontBold();
        \analib\System\Console\Colors::colorYellow();
        echo '\\<--- ' . $dir . "\n";
        \analib\System\Console\Colors::fontBold(false);
        \analib\System\Console\Colors::colorsDefault();
    }

    protected function _getExtension($fileName)
    {
        $name = explode('.', $fileName);
        if (count($name) > 1) {
            return end($name);
        } else {
            return null;
        }
    }

    protected function _getClearName($fileName)
    {
        $name = explode('.', $fileName);
        if (count($name) > 1) {
            unset($name[count($name) - 1]);
        }
        return implode('.', $name);
    }

    protected function _safeMove($oldName, $newName)
    {
        if (file_exists($newName)) {
            $path = dirname($newName);
            $name = basename($newName);
            $ext  = $this->_getExtension($name);
            if ($ext) {
                $ext = '.' . $ext;
            }
            $name  = $this->_getClearName($name);
            $index = 1;
            while (file_exists($newName)) {
                $newName = $path . DIRECTORY_SEPARATOR . $name . '(' . $index++ . ')' . $ext;
            }
        }
        rename($oldName, $newName);
    }

    protected function _filesUp($target, $source)
    {
        $sourceDir = opendir($source);
        $file      = readdir($sourceDir);
        while ($file !== false) {
            if (substr($file, 0, 1) !== '.') {
                $fullFileName = $source . DIRECTORY_SEPARATOR . $file;
                if (is_dir($fullFileName)) {
                    $this->_filesUp($target, $source . DIRECTORY_SEPARATOR . $file);
                } else {
                    $newName = $target . DIRECTORY_SEPARATOR . $file;
                    $this->_safeMove($fullFileName, $newName);
                }
            }
            $file = readdir($sourceDir);
        }
    }

    protected function filesUp()
    {
        $currentDirName = realpath('./');
        if ($currentDirName) {
            $currentDir = opendir($currentDirName);
            $file       = readdir($currentDir);
            while ($file !== false) {
                if (substr($file, 0, 1) !== '.') {
                    $fullFileName = $currentDirName . DIRECTORY_SEPARATOR . $file;
                    if (is_dir($fullFileName)) {
                        $this->_filesUp($currentDirName, $currentDirName . DIRECTORY_SEPARATOR . $file);
                    }
                }
                $file = readdir($currentDir);
            }
        }
    }

    protected function splitFile($fileName)
    {

        if (file_exists($fileName)) {
            $f2    = $f     = file_get_contents($fileName);
            $count = 0;
            foreach (self::$blockTags as $tag) {
                $patterns    = [
                    '|>\s*<' . $tag . '|',
                    '|</' . $tag . '>\s*<|',
//                    '|' . chr(194) . '|',
                ];
                $replacement = [
                    ">\n<" . $tag,
                    "</" . $tag . ">\n<",
//                    ' ',
                ];
                $f2          = preg_replace($patterns, $replacement, $f2, -1, $c);
                $count       += $c;
            }
            if ($f != $f2) {
                file_put_contents($fileName, $f2);
                echo $fileName;
                \analib\System\Console\Colors::colorGreen();
                \analib\System\Console\Colors::fontBold();
                echo " - OK (" . $count . " replacements)\n";
                \analib\System\Console\Colors::colorsDefault();
                \analib\System\Console\Colors::fontBold(false);
            }
        }
    }

    protected function denySymbols($string, $noEmpty = false)
    {
        $denyMap = array(
            '\'' => '`',
            '"'  => '``',
            '\\' => '_',
            '/'  => '_',
            '|'  => '_',
            '?'  => '',
//			' '	 => '_',
            ':'  => '_',
            '*'  => '_',
        );
        $out     = '';
        for ($i = 0; $i < mb_strlen($string, 'utf-8'); $i++) {
            $symbol = mb_substr($string, $i, 1, 'utf-8');
            if (isset($denyMap[$symbol])) {
                $out .= $denyMap[$symbol];
            } else {
                $out .= $symbol;
            }
        }
        $out = trim($out, '_');
        if ($noEmpty) {
            if (!$out) {
                $out = '_';
            }
        }
        return $out;
    }

    protected function renameFile($file)
    {
        $fb2    = FB2Informer::create($file);
        $name   = $this->denySymbols(trim($fb2->bookTitle()));
        $folder = array();
        $prefix = array();
        for ($i = 0; $i < strlen($this->params); $i++) {
            $param = substr($this->params, $i, 1);
            switch ($param) {
                case 'P':
                case 'p':
                    /* @var $sequence FB2Sequence */
                    $sequence = $fb2->sequencePublish();
                    $s        = $this->denySymbols($sequence->getName());
                    if ($s) {
                        $number = $sequence->getNumber();
                        if (substr($this->params, $i, 1) == 'P') {
                            $folder[] = $s;
                            if ($number) {
                                $name = $sequence->getNumber() . '.' . $name;
                            }
                        } else {
                            $prefix[] = $s;
                            if ($number) {
                                $prefix[] = $sequence->getNumber();
                            }
                        }
                    }
                    break;
                case 'S':
                case 's':
                    /* @var $sequence FB2Sequence */
                    $sequence = $fb2->sequence();
                    $s        = $this->denySymbols($sequence->getName());
                    if ($s) {
                        $number = $sequence->getNumber();
                        if (substr($this->params, $i, 1) == 'S') {
                            $folder[] = $s;
                            if ($number) {
                                $name = $sequence->getNumber() . '.' . $name;
                            }
                        } else {
                            $prefix[] = $s;
                            if ($number) {
                                $prefix[] = $sequence->getNumber();
                            }
                        }
                    }
                    break;
                case 'A':
                case 'a':
                    $authors = $fb2->authors();
                    $a       = array();
                    foreach ($authors as $author) {
                        /* @var $author FB2Author */
                        if ($param == 'A') {
                            $a[] = $author->toString(false);
                        } else {
                            $a[] = $author->getLastName();
                        }
                    }
                    if (count($a) > self::MULTI_AUTOR_FOLDER_NUM) {
                        $f = self::MULTI_AUTOR_FOLDER;
                    } else {
//                        $a = array_unique($a);
//                        sort($a);
                        $f = $this->denySymbols(implode(', ', $a));
                    }
                    if (substr($this->params, $i, 1) == 'A') {
                        $folder[] = $f;
                    } else {
                        $prefix[] = $f;
                    }
                    break;
                case 'e':
                case 't':
                    $name = self::Translite($name);
                    break;
                case 'y':
                    $year = trim($fb2->titleInfo()->getDate());
                    if ($year) {
                        $prefix[] = $year;
                    }
                    break;
                case 'Y':
                    $year = trim($fb2->publishInfo()->getYear());
                    if ($year) {
                        $prefix[] = $year;
//                        $name = $year . '.' . $name;
                    }
                    break;
                case 'G':
                    $genres = $fb2->genres();
                    if (count($genres)) {
                        $genre = $genres[0];
                        if (!isset($this->genresList[$genre])) {
                            $this->genresList[$genre] = 0;
                        }
                        $this->genresList[$genre]++;
                    }
                    break;
                case 'g':
                    $genres = $fb2->genres();
                    foreach ($genres as $genre) {
                        if (!isset($this->genresList[$genre])) {
                            $this->genresList[$genre] = 0;
                        }
                        $this->genresList[$genre]++;
                    }
                    break;
            }
        }

        $folderNew = $folderOld = dirname($file);
        if (count($folder)) {
            foreach ($folder as $part) {
                if ($part) {
                    $folderNew .= DIRECTORY_SEPARATOR . $part;
                    if (!file_exists($folderNew)) {
                        echo $folderNew . "\n";
                        mkdir($folderNew);
                        chmod($folderNew, self::MODE_DIRECTORY_ACCESS);
                    }
                }
            }
        }

        $prefix[] = $name;
        if (strpos($this->params, 'i') !== FALSE) {
            $pi = $fb2->publishInfo();
            if (strpos($this->params2, 'p') !== FALSE) {
                if ($pi->getPublisher()) {
                    $prefix[] = $pi->getPublisher();
                }
            }
            if (strpos($this->params2, 'c') !== FALSE) {
                if ($pi->getCity()) {
                    $prefix[] = $pi->getCity();
                }
            }
            if (strpos($this->params2, 'y') !== FALSE) {
                if ($pi->getYear()) {
                    $prefix[] = $pi->getYear();
                }
            }
            if (strpos($this->params2, 'Y') !== FALSE) {
                if ($pi->getYear()) {
                    array_unshift($prefix, $pi->getYear());
                }
            }
        }
        $name = trim(implode('.', $prefix));

        $fileName = \analib\Util\Translit::clearDenySymbols($name);
        while (strlen($fileName) > 245) {
            $fileName = mb_substr($fileName, 0, mb_strlen($fileName, 'utf-8') - 1, 'utf-8');
        }
        $fileName = $folderNew . DIRECTORY_SEPARATOR . $fileName . '.fb2';

        if (($name != '_') && ($fileName != $file) && ($fileName != '.fb2')) {
            $fileNum = 0;
            do {
                if ($fileName == $file) {
                    break;
                }
                if (file_exists($fileName)) {
                    if ((md5_file($fileName) == md5_file($file)) && ($fileName != $file)) {
                        unlink($file);
                    } else {

                    }
                }
                if ($fileNum) {
                    $fileName = $folderNew . DIRECTORY_SEPARATOR . $name . ($fileNum ? '.n(' . $fileNum . ')' : '') . '.fb2';
                }
                $fileNum++;
            } while (file_exists($fileName) && file_exists($file));

            if (file_exists($file) && ($fileName != $file)) {
                rename($file, $fileName);
                echo $fileName . "\n";
            }
        }
    }

    protected function sequenceFile($file)
    {
        $fb2 = FB2Informer::create($file);
        if (substr($this->params, 0, strlen(self::MODE_AUTO_SEQUENCE)) == self::MODE_AUTO_SEQUENCE) {
            if (file_exists($this->workDirectory . DIRECTORY_SEPARATOR . '.sequence')) {
                $sequenceName = file_get_contents($this->workDirectory . DIRECTORY_SEPARATOR . '.sequence');
            } else {
                $sequenceName = basename($this->workDirectory);
            }
            $number = $fb2->sequence()->getNumber();
        } elseif (substr($this->params, 0, strlen(self::MODE_AUTO_SEQUENCE_OTHER)) == self::MODE_AUTO_SEQUENCE_OTHER) {
            $sequenceOther = $fb2->sequencePublish();
            $sequenceName  = $sequenceOther->getName();
            $number        = $sequenceOther->getNumber();
        } elseif (substr($this->params, 0, strlen(self::MODE_AUTO_SEQUENCE_NUMBER)) == self::MODE_AUTO_SEQUENCE_NUMBER) {
            if (file_exists($this->workDirectory . DIRECTORY_SEPARATOR . '.sequence')) {
                $sequenceName = file_get_contents($this->workDirectory . DIRECTORY_SEPARATOR . '.sequence');
            } else {
                $sequenceName = basename($this->workDirectory);
            }
            $fileName = basename($file);
            $matches  = [];
            if (substr($this->params, 0, strlen(self::MODE_AUTO_SEQUENCE_NUMBER_BEGIN)) == self::MODE_AUTO_SEQUENCE_NUMBER_BEGIN) {
                $ptrn = '|^(\d+)+.*$|s';
            } else {
                $ptrn = '|(\d+)+.*$|s';
            }
            $r = preg_match($ptrn, $fileName, $matches);
            if ($r) {
                $number = $matches[1];
            } else {
                $number = '';
            }
        } elseif (substr($this->params, 0, strlen(self::MODE_AUTO_SEQUENCE_TREE)) == self::MODE_AUTO_SEQUENCE_TREE) {
            $wd        = $this->startDirectory;
            $path      = trim(substr($file, strlen($wd)), DIRECTORY_SEPARATOR);
            $pathNoExt = substr($path, 0, strrpos($path, '.'));
            $dirlist   = explode(DIRECTORY_SEPARATOR, $pathNoExt);
            $number    = '';
            $ptrnN     = '|(\d+)+.*$|s';
            $ptrnNB    = '|^(\d+)+.*$|s';
            $ptrnWB    = '/^([\s\.]+)/';
            $ptrnW     = '/^([\s\.]+)/';
            for ($i = 0; $i < count($dirlist); $i++) {
                $cell = $dirlist[$i];
                if ($i == count($dirlist) - 1) {
                    $pp = $ptrnN;
                } else {
                    $pp = $ptrnNB;
                }
                if (preg_match($ptrnN, $cell, $matches)) {
                    $number      = $matches[1];
                    $dirlist[$i] = substr($dirlist[$i], strlen($number));
                    $dirlist[$i] = preg_replace($pp, '', $dirlist[$i]);
                } else {
                    $number = '';
                }
                if ($i == count($dirlist) - 1) {
                    $dirlist[$i] = '';
                } else {
                    $pp          = $ptrnWB;
                    $dirlist[$i] = preg_replace($pp, '', $dirlist[$i]);
                }
                $dirlist[$i] = [
                    'name'   => $dirlist[$i],
                    'number' => $number,
                ];
            }
            $seqList = [];
            foreach ($dirlist as $dir) {
                $seqList[] = FB2Sequence::create($dir);
            }
            return;
        } elseif (substr($this->params, 0, 1) == '-') {
            $sequenceName = '';
            $this->dropSequenceFile($file);
        } else {
            $sequenceName = $this->params;
            $number       = 0;
        }
        try {
            if ($sequenceName) {
                $v      = explode('::', $sequenceName);
                $values = array(
                    'name'   => $v[0],
                    'number' => $number
                );
                if (isset($v[1])) {
                    $values['number'] = $v[1];
                }
                $forceNumber = substr($this->params, 0, strlen(self::MODE_AUTO_SEQUENCE_NUMBER)) == self::MODE_AUTO_SEQUENCE_NUMBER;
                $fb2->setSequence(FB2Sequence::create($values), $forceNumber);
            } else {
                $this->out[basename($fb2->getFileName())] = array(
                    'File'     => basename($fb2->getFileName()),
                    'Sequence' => $fb2->sequence()->getName(),
                    'Number'   => $fb2->sequence()->getNumber(),
                );
            }
        } catch (BaseException $e) {
            echo $e->getMessage();
        }
    }

    protected function dropSequenceFile($file)
    {
        FB2Informer::create($file)->removeSequence();
    }

    protected function publishSequenceFile($file)
    {
        $fb2 = FB2Informer::create($file);
        if (substr($this->params, 0, strlen(self::MODE_AUTO_SEQUENCE)) == self::MODE_AUTO_SEQUENCE) {
            if (file_exists($this->workDirectory . DIRECTORY_SEPARATOR . '.publishSequence')) {
                $sequenceName = file_get_contents($this->workDirectory . DIRECTORY_SEPARATOR . '.publishSequence');
            } else {
                $sequenceName = basename($this->workDirectory);
            }
            $info = $this->getContentByBookTitle($fb2->bookTitle());
            if (isset($info['publishSequenceNumber'])) {
                $number = $info['publishSequenceNumber'];
            } else {
                $number = $fb2->sequencePublish()->getNumber();
            }
        } elseif (substr($this->params, 0, strlen(self::MODE_AUTO_SEQUENCE_NUMBER)) == self::MODE_AUTO_SEQUENCE_NUMBER) {
            if (file_exists($this->workDirectory . DIRECTORY_SEPARATOR . '.publishSequence')) {
                $sequenceName = file_get_contents($this->workDirectory . DIRECTORY_SEPARATOR . '.publishSequence');
            } else {
                $sequenceName = basename($this->workDirectory);
            }
            if (substr($this->params, 0, strlen(self::MODE_AUTO_SEQUENCE_NUMBER_BEGIN)) == self::MODE_AUTO_SEQUENCE_NUMBER_BEGIN) {
                $ptrn = '|^(\d+)+.*$|s';
            } else {
                $ptrn = '|(\d+)+.*$|s';
            }
            if (preg_match($ptrn, basename($file), $matches)) {
                if (isset($matches[1])) {
                    $number = $matches[1];
                }
                if (!is_numeric($number)) {
                    $number = '';
                }
            } else {
                $number = '';
            }
        } elseif (substr($this->params, 0, strlen(self::MODE_AUTO_SEQUENCE_OTHER)) == self::MODE_AUTO_SEQUENCE_OTHER) {
            $sequenceOther = $fb2->sequence();
            $sequenceName  = $sequenceOther->getName();
            $number        = $sequenceOther->getNumber();
        } elseif (substr($this->params, 0, strlen(self::MODE_AUTO_SEQUENCE_TREE)) == self::MODE_AUTO_SEQUENCE_TREE) {

        } elseif (substr($this->params, 0, 1) == '-') {
            $sequenceName = '';
            $this->dropPublishSequenceFile($file);
        } else {
            $sequenceName = $this->params;
            $number       = 0;
        }
        try {
            if ($sequenceName) {
                $v      = explode('::', $sequenceName);
                $values = array(
                    'name'   => $v[0],
                    'number' => $number
                );
                if (isset($v[1])) {
                    $values['number'] = $v[1];
                }
                $forceNumber = substr($this->params, 0, strlen(self::MODE_AUTO_SEQUENCE_NUMBER)) == self::MODE_AUTO_SEQUENCE_NUMBER;
                $fb2->setSequencePublish(FB2Sequence::create($values), $forceNumber);
            } else {
                $this->out[basename($fb2->getFileName())] = array(
                    'File'     => basename($fb2->getFileName()),
                    'Sequence' => $fb2->sequencePublish()->getName(),
                    'Number'   => $fb2->sequencePublish()->getNumber(),
                );
            }
        } catch (BaseException $e) {
            echo $e->getMessage();
        }
    }

    protected function publishYearFile($file)
    {
        $fb2 = FB2Informer::create($file);
        $pi  = $fb2->publishInfo();

        $this->out[basename($fb2->getFileName())] = array(
            'File' => basename($fb2->getFileName()),
            'Year' => $pi->getYear(),
        );
    }

    protected function titleYearFile($file)
    {
        $fb2 = FB2Informer::create($file);
        $pi  = $fb2->titleInfo();

        $this->out[basename($fb2->getFileName())] = array(
            'File' => basename($fb2->getFileName()),
            'Year' => $pi->getDate(),
        );
    }

    protected function yearPublishFile($file)
    {
        $fb2 = FB2Informer::create($file);
        if (substr($this->params, 0, strlen(self::MODE_AUTO_SEQUENCE)) == self::MODE_AUTO_SEQUENCE) {
            $fileName = basename($file);
            if (preg_match('/(19|20)\d\d/', $fileName, $matches)) {
                foreach ($matches as $year) {
                    if (($year > 1900) && ($year <= date('Y'))) {
                        $fb2->setPublishYear($year);
                        break;
                    }
                }
            }
        } else {

        }
    }

    protected function tireFile($file)
    {
        $text    = file_get_contents($file);
        $badSym  = [
//            '–',
            '–',
            '...',
            ' ',
            '&#160;',
            '<empty-line/>',
            '<empty-line></empty-line>',
            'вЂ¦',
        ];
        $goodSym = [
//            '—',
            '—',
            '…',
            ' ',
            ' ',
            '<empty-line />',
            '<empty-line />',
            '…',
        ];
        $tags    = [
            'first-name',
            'middle-name',
            'last-name',
            'book-title',
            'coverpage',
//            'title-info',
        ];
        $text2   = str_replace($badSym, $goodSym, $text, $count);
        foreach ($tags as $tag) {
            $text2 = preg_replace('|<' . $tag . '>\s|', '<' . $tag . '>', $text2, -1, $c);
            $count += $c;
            $text2 = preg_replace('|\s</' . $tag . '>|', '</' . $tag . '>', $text2, -1, $c);
            $count += $c;
        }
        if ($text != $text2) {
            file_put_contents($file, $text2);
            echo $file;
            \analib\System\Console\Colors::colorGreen();
            \analib\System\Console\Colors::fontBold();
            echo " - OK (" . $count . " replacements)\n";
            \analib\System\Console\Colors::colorsDefault();
            \analib\System\Console\Colors::fontBold(false);
        }
    }

    protected function dropPublishSequenceFile($file)
    {
        FB2Informer::create($file)->removePublishSequence();
    }

    protected function image2BinaryFile($file)
    {
        $splitLength = ($this->params2 !== null) ? (int) $this->params2 : 2048;
        if (file_exists($file)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $file, FILEINFO_MIME_TYPE);
            if (strpos($mime, 'image') !== false) {
                $f = base64_encode(file_get_contents($file));
                if ($splitLength) {
                    $out = array();
                    while (strlen($f)) {
                        $out[] = substr($f, 0, $splitLength);
                        $f     = substr($f, $splitLength);
                    }
                    $f = implode("\n", $out);
                }
                $out = '<binary content-type="' . $mime . '" id="' . basename($file) . '">' . $f . '</binary>';
                file_put_contents($file . '.binary', $out);
            }
        }
    }

    protected function getCoverFile($file)
    {
        $fb2       = FB2Informer::create($file);
        $cover     = $fb2->cover();
        $coverName = $fb2->coverName();
        if ($cover && $coverName) {
            $dir = dirname($file);
            file_put_contents($dir . DIRECTORY_SEPARATOR . $coverName, $cover);
        }
    }

    /**
     * 	Перевод кириллицы в транслит
     * @param string $string
     * @return string
     */
    static protected function Translite($string)
    {
        $out = '';
        for ($i = 0; $i < mb_strlen($string, 'utf-8'); $i++) {
            $symbol = mb_substr($string, $i, 1, 'utf-8');
            if (isset(self::$translitMap[$symbol])) {
                $out .= self::$translitMap[$symbol];
            } elseif (mb_strpos(self::$translitAllow, $symbol, null, 'utf-8') !== false) {
                $out .= $symbol;
            }
        }
        $out = trim($out, '_');
        return $out;
    }

    protected function zipFile($file)
    {
        $name    = basename($file);
        $path    = dirname($file);
        $newFile = self::Translite($name);
        echo $name . "\n";
        $zip     = new \ZipArchive();
        $r       = $zip->open($path . DIRECTORY_SEPARATOR . $newFile . '.zip', \ZipArchive::CREATE);
        if ($r) {
            $zip->addFile($file, $newFile);
            $zip->close();
        }
        if (strpos($this->params, '+') === false) {
            @unlink($file);
        }
    }

    protected function authorFile($file)
    {
        $fb2 = FB2Informer::create($file);

        $this->out[basename($fb2->getFileName())] = array(
            'File'    => basename($fb2->getFileName()),
            'Authors' => implode(', ', $fb2->authors()),
        );
    }

    protected function titleFile($file)
    {
        $fb2 = FB2Informer::create($file);

        $this->out[basename($fb2->getFileName())] = array(
            'File'  => basename($fb2->getFileName()),
            'Title' => $fb2->bookTitle(),
        );
    }

    protected function encodingFile($file)
    {
        $fb2         = FB2Informer::create($file);
        $encoding    = $fb2->getEncoding();
        $encodingOut = $this->params;
        if (!$encodingOut) {
//            $this->out[basename($fb2->getFileName())] = array(
//                'File'     => basename($fb2->getFileName()),
//                'Encoding' => $encoding,
//            );
//            echo basename($fb2->getFileName()) . ' ' . $encoding . "\n";
        } else {
            if (strtolower($encoding) !== strtolower($encodingOut)) {
                $text           = $fb2->getText();
                $startTagEndPos = strpos($text, '>');
                $newStartTag    = '<?xml version="' . $fb2->getVersion() . '" encoding="' . $encodingOut . '"?>';
                $newText        = $newStartTag . mb_convert_encoding(substr($text, $startTagEndPos + 1), $encodingOut, $encoding);

                file_put_contents($file, $newText);
//                $this->out[basename($fb2->getFileName())] = array(
//                    'File'     => basename($fb2->getFileName()),
//                    'Encoding' => $encoding . ' -> ' . $encodingOut,
//                );
                \analib\System\Console\Colors::colorYellow();
                \analib\System\Console\Colors::fontBold();
                echo basename($fb2->getFileName()) . ': ' . $encoding . ' -> ' . $encodingOut . "\n";
                \analib\System\Console\Colors::colorsDefault();
                \analib\System\Console\Colors::fontBold(false);
            } else {
//                $this->out[basename($fb2->getFileName())] = array(
//                    'File'     => basename($fb2->getFileName()),
//                    'Encoding' => $encoding,
//                );
//                echo basename($fb2->getFileName()) . ' is already ' . $encoding . "\n";
            }
        }
    }

    protected function binaryNormalyse($file)
    {
        $this->out   = [];
        $splitLength = $this->params ? $this->params : 1024;
        $fb2         = file_get_contents($file);
        $startTag    = 0;
        do {
            $startTag = strpos($fb2, '<binary ', $startTag);
            if (!is_bool($startTag)) {
                $startBinary = strpos($fb2, '>', $startTag) + 1;
                $endBinary   = strpos($fb2, '</binary>', $startBinary);
                $binary      = base64_encode(base64_decode(substr($fb2, $startBinary, $endBinary - $startBinary)));
                $out         = array();
                while (strlen($binary) > 0) {
                    $out[]  = substr($binary, 0, $splitLength);
                    $binary = substr($binary, $splitLength);
                }
                $fb2 = substr($fb2, 0, $startBinary) . implode("\n", $out) . substr($fb2, $endBinary);
                $startTag++;
            }
        } while ($startTag !== false);
        $this->out[basename($file)] = [
            'File' => basename($file),
            ':'    => ' Completed',
        ];
        file_put_contents($file, $fb2);
    }

}
