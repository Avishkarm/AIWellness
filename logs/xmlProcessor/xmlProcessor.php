<?php
class xmlProcessor
{


    public function readXML($filename, $logStorePath, $year, $month, $date, $hour)
    {

        echo $URL = $logStorePath . $year . "/" . $month . "/" . $date . "/" . $hour . "_OClock_" . $filename;

        libxml_use_internal_errors(true);
        $xml = simplexml_load_file($URL);
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        $xml = new SimpleXMLElement($dom->saveXML());

        if ($xml == false) {
            echo "Failed loading XML: ";
            foreach (libxml_get_errors() as $error) {
                echo "<br>", $error->message;
            }
        } else {
            return $xml;
        }

    }

    /* Write XML
    $filename=name of file
    $logStorePath -path from config
    $xml_data-data to write in XMl
    $activity_attribute-attributes for activity tag
    */
    function writeXML($filename, $logStorePath, $xml_data, $activity_attribute)
    {

       $year = date("Y");
        $month = date("m");
        $date = date("d");
        $hour = date("h");


        $logDir = $logStorePath . $year . "/" . $month . "/" . $date;
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $URL = $logStorePath . $year . "/" . $month . "/" . $date . "/" . $hour . "_OClock_" . $filename;

        if (!file_exists($URL)) {
            $method = (file_exists($URL)) ? 'a' : 'w';
            $myfile = fopen($URL, $method); //or die("Unable to open file!");
            fwrite($myfile, "<root>\n</root>");
            fclose($myfile);
        }

        libxml_use_internal_errors(true);

        $xml = simplexml_load_file($URL);

          $activity = $xml->addChild('activity');
       foreach ($activity_attribute as $key => $value) {
            $activity->addAttribute($key, $value);

        }

           foreach ($xml_data as $key => $value) {
            foreach ($value as $keys => $dvalue) {


                if (gettype($dvalue) != "array") {
                    $resultData = $activity->addChild($key, $dvalue);
                } else if (gettype($dvalue) == "array") {
                    foreach ($dvalue as $keyss => $valuedata) {
                        if($keys=="childelement")
                        {
                            $resultData->addChild($keyss, $valuedata);
                        }else
                        {
                            $resultData->addAttribute($keyss, $valuedata);
                        }

                    }

                }
            }
        }

 
        $xml->asXML($URL);
    }

}
?>