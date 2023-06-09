<?php

/**
 * A class that parses XML data and writes it to a new file.
 */
class ParseService
{
    /**
     * @var XMLReader The XMLReader instance used to read input XML data.
     */
    private XMLReader $xmlReader;

    /**
     * @var XMLWriter The XMLWriter instance used to write output XML data.
     */
    private XMLWriter $xmlWriter;

    /**
     * Creates a new ParseService instance.
     *
     * @param XMLReader $xmlReader The XMLReader instance to use for reading input XML data.
     * @param XMLWriter $xmlWriter The XMLWriter instance to use for writing output XML data.
     */
    public function __construct(XMLReader $xmlReader, XMLWriter $xmlWriter)
    {
        $this->xmlReader = $xmlReader;
        $this->xmlWriter = $xmlWriter;
    }


    /**
     * Parses XML data from an input file and writes it to an output file.
     *
     * @param string|null $inputFile The path to the input XML file.
     * @param string|null $outputFile The path to the output XML file.
     * @param DateTime|null $currentTime The current date and time to use for determining offer availability.
     *
     * @return array An array with keys "paused" and "active" representing the number of paused and active offers, respectively.
     */
    public function parseXML(String $inputFile = null, String $outputFile = null, DateTime $currentTime = null)
    {
        if ($currentTime === null) {
            $currentTime = new DateTime();
        }
        $xmlReader = $this->xmlReader;
        $xmlWriter = $this->xmlWriter;
        $activeOrders = 0;
        $pausedOrders = 0;
        $dateTime = new DateTime("now", new DateTimeZone("CET"));
        $currentWeekDay = (int) $dateTime->format("N");
        $xmlReader->open($inputFile);
        $xmlWriter->openURI($outputFile);
        $xmlWriter->setIndent(true);
        $xmlWriter->setIndentString('  ');
        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->startElement('offers');

        while ($xmlReader->read()) {
            if ($xmlReader->nodeType == XMLReader::ELEMENT && $xmlReader->name == 'offer') {
                $offer = array();
                while ($xmlReader->read()) {
                    if ($xmlReader->nodeType == XMLReader::ELEMENT) {
                        $name = $xmlReader->name;
                        $xmlReader->read();
                        $offer[$name] = $xmlReader->value;
                    }
                    if ($xmlReader->nodeType == XMLReader::END_ELEMENT && $xmlReader->name == 'offer') {
                        if ($offer['opening_times'] === null) {
                            $offer['is_active'] = false;
                            $pausedOrders++;
                        } else {
                            $offer['is_active'] = false;
                            $opTimes = json_decode($offer['opening_times'], true);
                            if (isset($opTimes[$currentWeekDay])) {
                                foreach ($opTimes[$currentWeekDay] as $opTime) {
                                    $op = new DateTime($opTime["opening"]);
                                    $cl = new DateTime($opTime["closing"]);

                                    if ($cl->format('H:i:s') === '00:00:00') {
                                        $cl->setTime(23, 59, 59);
                                    }
                                    if ($op->format("Y-d-m H:i:s") < $dateTime->format("Y-d-m H:i:s") && $dateTime->format("Y-d-m H:i:s") < $cl->format("Y-d-m H:i:s")) {
                                        $offer['is_active'] = true;
                                        $activeOrders++;
                                    } else {
                                        $pausedOrders++;
                                    }
                                }
                            } else {
                                $pausedOrders++;
                            }
                        }
                        $this->writeElements($offer);
                        break;
                    }
                }
            }
        }

        $xmlReader->close();
        // End the root element
        $xmlWriter->endElement();

        // End the XML document
        $xmlWriter->endDocument();

        // Write the XML data to a file
        $xmlWriter->flush();

        return ["paused" => $pausedOrders, "active" => $activeOrders];
    }


    /**
     * Writes an offer element to the output XML data.
     *
     * @param array $offer An array representing the offer data to write.
     */
    private function writeElements(array $offer)
    {
        $xmlWriter = $this->xmlWriter;
        $xmlWriter->startElement('offer');
        $xmlWriter->startElement('id');
        $xmlWriter->writeCdata($offer['id']);
        $xmlWriter->endElement();
        $xmlWriter->startElement('name');
        $xmlWriter->writeCdata($offer['name']);
        $xmlWriter->endElement();
        $xmlWriter->startElement('category');
        $xmlWriter->writeCdata($offer['category']);
        $xmlWriter->endElement();
        $xmlWriter->startElement('description');
        $xmlWriter->writeCdata($offer['description']);
        $xmlWriter->endElement();
        $xmlWriter->startElement('price');
        $xmlWriter->writeCdata($offer['price']);
        $xmlWriter->endElement();
        $xmlWriter->startElement('url');
        $xmlWriter->writeCdata($offer['url']);
        $xmlWriter->endElement();
        $xmlWriter->startElement('image_url');
        $xmlWriter->writeCdata($offer['image_url']);
        $xmlWriter->endElement();
        $xmlWriter->startElement('opening_times');
        $xmlWriter->writeCdata($offer['opening_times']);
        $xmlWriter->endElement();
        $xmlWriter->startElement('is_active');
        $xmlWriter->writeCdata(json_encode($offer['is_active']));
        $xmlWriter->endElement();
        $xmlWriter->endElement();
    }
}
