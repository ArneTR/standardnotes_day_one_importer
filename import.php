<?php


/*
    Raw import strcture

      "creationDate": "2017-10-08T22:32:00Z",
      "duration": 0,
      "modifiedDate": "2017-12-04T00:17:01Z",
      "starred": false,
      "text": "Becca\nIch habe heute keine positiven Emotionen verspürt. Das Treffen mit der Becca hat mir wirklich gar nicht gefallen.\nSie erzählt nur von Ihren Dates wo ich mich iwi überhaupt nicht wiederfinde. Ich habe das Gefühl, wir sind so in unterschiedlichen Altersklassen, dass es gar keinen Sinn macht mich Ihr zu öffnen. Wir denken einfach zu unterschiedlich.\nVlt. ist sie für eine Frau nicht schlecht. Aber für mich ist das gerade nichts. Ich brauche sinnvollen Input.\nVictoria ist das wirklich gerade noch so ok. \nAuch Tobias möchte ich einfach nicht weiter privat in meinem Leben haben.\nCarlos auch nicht. Diese Zeitschleifen kann ich mir einfach nicht mehr geben.\nErschreckend muss ich aber feststellen, dass ich mich von praktisch allen Leuten damit abwende. Ich bin dann allein.\nDas erste mal fühle ich das gerade überhaupt. Bisher hatte ich immer noch den Tobias als letzten Anker für eine dauerhafte Freundschaft. Aber auch diese habe ich gerade mal emotional losgelöst.",
      "timeZone": "Europe/Berlin",
      "uuid": "A35F720534474AEDB4362A6130350F2F"

 */

$json_import = [
  "items" => [
  ]
];


foreach(glob(__DIR__.'/*.json') as $file) {
    $data = file_get_contents($file);
    echo "Processing $file", PHP_EOL;
    $json = json_decode($data, false, 512, JSON_THROW_ON_ERROR);
    $count = 0;
    foreach($json->entries as $entry) {
        $count++;
        if(empty($entry->text))var_dump($entry);
        $text = trim(strstr($entry->text, "\n"));
        $title = trim(strstr($entry->text, "\n", true));

        $json_import['items'][] = [
            "created_at" => $entry->creationDate,
            "uuid" => get_uuid(),
            "content_type" => "Note",
            "content" => [
              "title" => $title,
              "text" => $text,
              "references" => [],
              "appData" => [
                "org.standardnotes.sn" => [
                  "client_updated_at" => $entry->creationDate
                ]
              ]
            ]
        ];
    }
    $filename = pathinfo($file)['filename'];
    file_put_contents("./imports/SNIMPORT_{$filename}.{$count}.json", json_encode($json_import));
}


function get_uuid() {
            $pr_bits = null;
        $fp = @fopen('/dev/urandom','rb');
        if ($fp !== false) {
            $pr_bits .= @fread($fp, 16);
            @fclose($fp);
        } else {
            die('randomNumber');
        }

        $time_low = bin2hex(substr($pr_bits,0, 4));
        $time_mid = bin2hex(substr($pr_bits,4, 2));
        $time_hi_and_version = bin2hex(substr($pr_bits,6, 2));
        $clock_seq_hi_and_reserved = bin2hex(substr($pr_bits,8, 2));
        $node = bin2hex(substr($pr_bits,10, 6));

        /**
         * Set the four most significant bits (bits 12 through 15) of the
         * time_hi_and_version field to the 4-bit version number from
         * Section 4.1.3.
         * @see http://tools.ietf.org/html/rfc4122#section-4.1.3
         */
        $time_hi_and_version = hexdec($time_hi_and_version);
        $time_hi_and_version = $time_hi_and_version >> 4;
        $time_hi_and_version = $time_hi_and_version | 0x4000;

        /**
         * Set the two most significant bits (bits 6 and 7) of the
         * clock_seq_hi_and_reserved to zero and one, respectively.
         */
        $clock_seq_hi_and_reserved = hexdec($clock_seq_hi_and_reserved);
        $clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved >> 2;
        $clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved | 0x8000;

        return sprintf('%08s-%04s-%04x-%04x-%012s',
            $time_low, $time_mid, $time_hi_and_version, $clock_seq_hi_and_reserved, $node);

}
