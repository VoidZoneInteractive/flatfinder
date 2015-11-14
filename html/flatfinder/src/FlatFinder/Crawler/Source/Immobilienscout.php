<?php
/**
 * Created by PhpStorm.
 * User: grzegorzgurzeda
 * Date: 14.11.15
 * Time: 00:16
 */

namespace FlatFinder\Crawler\Source;


class Immobilienscout {
    const SOURCE_URL = 'http://www.immobilienscout24.de';
    const DETAILS_URL = 'http://www.immobilienscout24.de/expose/';
    const SEARCH_URL_PART = '/Suche/controller/asyncResults.go?searchUrl=';

    private $entries = array();

    public function getData()
    {
        // Fetch first batch

        $dataUrl = static::SOURCE_URL . static::SEARCH_URL_PART . '/Suche/S-T/Wohnung-Miete/Berlin/Berlin/-/-/-/EURO--400,00';
        $data = $this->getRawData($dataUrl, '');
        $referer = $dataUrl;
        $data = json_decode($data);

        // Isolate entries
//        preg_match('/model: {"results":(.+)travelTimeModel/msU', $data, $dataMatch);

//        $dataMatch = substr($dataMatch[1], 0, -10);
        $dataMatch = $data->searchResult->results;

//        $dataMatch = json_decode($dataMatch);
        foreach ($dataMatch as &$entry)
        {
            $this->prepareData($entry);
        }

        // Isolate nextPage
//        preg_match('/nextPage: "(.+)",/U', $data, $nextPageMatch);

//        if ($dataUrl = !empty($nextPageMatch[1]) ? $nextPageMatch[1] : false)
        if ($dataUrl = !empty($data->nextPage) ? $data->nextPage : false)
        {
            while (!empty($dataUrl))
            {
//                sleep(3);

                // Fetch batch
                $dataUrl = static::SOURCE_URL . $dataUrl;
                $data = $this->getRawData($dataUrl, $referer);
                $referer = $dataUrl;

                unset($dataUrl);

                $data = json_decode($data);

                $dataMatch = $data->searchResult->results;

                foreach ($dataMatch as &$entry)
                {
                    $this->prepareData($entry);
                }

                $dataUrl = !empty($data->nextPage) ? $data->nextPage : false;
            }
        }

        header('Content-type: application/json');
        exit(json_encode($this->entries));
    }

    const USER_AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36';

    private function getRawData($url, $referer)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
//                'cache-control: no-cache',
                'content-type: application/json, text/plain, */*',
//                'postman-token: 58e7877b-f14f-6f37-1605-f2bf8e201488',
                'referer: ' . $referer,
                'user-agent: ' . static::USER_AGENT,
            ),
        ));

        $response = curl_exec($curl);

        $curlInfo = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($curlInfo != 200)
        {
            echo '<pre>';
            echo $url . "\n";
            echo $referer . "\n";
            exit($curlInfo);
        }

        $err = curl_error($curl);

        curl_close($curl);

        if ($err)
        {
            exit("cURL Error #:" . $err);
        }
        else
        {
            return $response;
        }
    }

    private function prepareData(&$entry)
    {
//        exit(json_encode($entry));
        $output = new \stdClass();
        $output->url       = static::DETAILS_URL . $entry->id;
        $output->id        = $entry->id;
        $output->latitude  = !empty($entry->latitude) ? $entry->latitude : null;
        $output->longitude = !empty($entry->longitude) ? $entry->longitude : null;
        $output->title     = $entry->title;
        $output->net_price = !empty($entry->attributes[0]->value) ? $entry->attributes[0]->value : null;
        $output->area      = !empty($entry->attributes[1]->value) ? $entry->attributes[1]->value : null;
        $output->rooms     = !empty($entry->attributes[2]->value) ? $entry->attributes[2]->value : null;
        $output->district  = $entry->district;
        $output->address   = $entry->address;
        $this->entries[$entry->id] = $output;
    }
} 