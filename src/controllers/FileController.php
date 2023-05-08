<?php
require_once 'AppController.php';
require_once  __DIR__ . '/../services/ParseService.php';

class FileController extends AppController
{
    const MAX_FILE_SIZE = 2048 * 1024 * 1024 * 2;
    const SUPPORTED_TYPES = ['text/xml', 'application/xml'];
    const UPLOAD_DIRECTORY = '/../public/uploads/';
    private ParseService $parseService;

    public function __construct()
    {
        parent::__construct();
        $this->parseService = new ParseService(new XMLReader(), new XMLWriter());
    }

    public function parseFile()
    {

        if ($this->isPost()) {
            $outputFile = $_POST['output_file'];
            $inputFile = $_POST['input_file'];
            $res = $this->parseService->parseXML(
                dirname(__DIR__) . self::UPLOAD_DIRECTORY . $inputFile,
                dirname(__DIR__) . self::UPLOAD_DIRECTORY . $outputFile
            );
            return $this->render('parse', $res);
        }
        return $this->render('parse');
    }
}
