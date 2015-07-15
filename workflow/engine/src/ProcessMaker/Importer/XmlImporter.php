<?php
namespace ProcessMaker\Importer;

class XmlImporter extends Importer
{
    /**
     * @var \DOMDocument
     */
    protected $dom;
    protected $root;
    protected $version = "";

    public function __construct()
    {
        $this->dom = new \DOMDocument();
    }

    /**
     * @return array
     * Example:
     * array(
     *   "tables" => array("bpmn" => array(), "workflow" => array())
     *   "files" => array("bpmn" => array(), "workflow" => array())
     * )
     * @throws \Exception
     */
    public function load($filename = null)
    {
        if (!is_null($filename) && !file_exists($filename)) {
            throw new \Exception(\G::LoadTranslation("ID_INVALID_FILE"));
        }

        $this->dom->load((is_null($filename))? $this->filename : $filename);
        $this->root = $this->dom->documentElement;

        // validate version
        $this->version = $this->root->getAttribute("version");

        if (empty($this->version)) {
            throw new \Exception(\G::LoadTranslation("ID_IMPORTER_FILE_PROCESSMAKER_PROJECT_VERSION_IS_MISSING"));
        }

        // read metadata section
        $metadataNode = $this->root->getElementsByTagName("metadata");

        if ($metadataNode->length != 1) {
            throw new \Exception(\G::LoadTranslation("ID_IMPORTER_FILE_INVALID_DOCUMENT_FORMAT_METADATA_IS_MISSING"));
        }

        $metadataNodeList = $metadataNode->item(0)->getElementsByTagName("meta");

        if ($metadataNodeList->length == 0) {
            throw new \Exception(\G::LoadTranslation("ID_IMPORTER_FILE_INVALID_DOCUMENT_FORMAT_METADATA_IS_CORRUPT"));
        }


        foreach ($metadataNodeList as $metadataNode) {
            $this->metadata[$metadataNode->getAttribute("key")] = $this->getTextNode($metadataNode);
        }

        // load project definition
        /** @var \DOMElement[]|\DomNodeList $definitions */
        $definitions = $this->root->getElementsByTagName("definition");

        if ($definitions->length == 0) {
            throw new \Exception(\G::LoadTranslation("ID_IMPORTER_FILE_DEFINITION_SECTION_IS_MISSING"));
        } elseif ($definitions->length < 2) {
            throw new \Exception(\G::LoadTranslation("ID_IMPORTER_FILE_DEFINITION_SECTION_IS_INCOMPLETE"));
        }

        $tables = array();

        foreach ($definitions as $definition) {
            $defClass = strtolower($definition->getAttribute("class"));
            $tables[$defClass] = array();

            // getting tables def
            // first we need to know if the project already exists
            /** @var \DOMElement[] $tablesNodeList */
            $tablesNodeList = $definition->getElementsByTagName("table");

            foreach ($tablesNodeList as $tableNode) {
                $tableName = ($defClass == "workflow")? $tableNode->getAttribute("name") : strtolower($tableNode->getAttribute("name"));
                $tables[$defClass][$tableName] = array();
                /** @var \DOMElement[] $recordsNodeList */
                $recordsNodeList = $tableNode->getElementsByTagName("record");

                foreach ($recordsNodeList as $recordsNode) {
                    if (! $recordsNode->hasChildNodes()) {
                        continue;
                    }

                    $columns = array();

                    foreach ($recordsNode->childNodes as $columnNode) {
                        if ($columnNode->nodeName == "#text") {
                            continue;
                        }

                        //$columns[strtoupper($columnNode->nodeName)] = self::getTextNode($columnNode);;
                        $columnName = $defClass == "workflow" ? strtoupper($columnNode->nodeName) : $columnNode->nodeName;
                        $columns[$columnName] = self::getTextNode($columnNode);
                    }

                    $tables[$defClass][$tableName][] = $columns;
                }
            }
        }

        $wfFilesNodeList = $this->root->getElementsByTagName("workflow-files");
        $wfFiles = array();

        if ($wfFilesNodeList->length > 0) {
            $filesNodeList = $wfFilesNodeList->item(0)->getElementsByTagName("file");

            foreach ($filesNodeList as $fileNode) {
                $target = strtolower($fileNode->getAttribute("target"));

                if (! isset($wfFiles[$target])) {
                    $wfFiles[$target] = array();
                }

                $fileContent = self::getTextNode($fileNode->getElementsByTagName("file_content")->item(0));
                $wfFiles[$target][] = array(
                    "file_name" => self::getTextNode($fileNode->getElementsByTagName("file_name")->item(0)),
                    "file_path" => self::getTextNode($fileNode->getElementsByTagName("file_path")->item(0)),
                    "file_content" => base64_decode($fileContent)
                );
            }
        }

        return array(
            "tables" => $tables,
            "files" => array("workflow" => $wfFiles, "bpmn" => array())
        );
    }

    private static function getTextNode($node)
    {
        if ($node->nodeType == XML_ELEMENT_NODE) {
            return $node->textContent;
        } elseif ($node->nodeType == XML_TEXT_NODE || $node->nodeType == XML_CDATA_SECTION_NODE) {
            return (string) simplexml_import_dom($node->parentNode);
        }
    }
}

