<?php

namespace AppBundle\Services;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class ActivitiesDownloadService
{
    const RSA_ACTIVITIES_URL = 'http://rss.trojmiasto.pl/rss,23.xml';

    public function getActivityDataArray($value)
    {
        $activityLinkData = $this->getActivityDataFromUrl($value['link']);
        if (!empty($activityLinkData)) {
            $activityEntityData = [
                'title' => $value['title'],
                'link' => $value['link'],
                'description' => $value['description']['#text'],
                'pubDate' => new \DateTime($value['pubDate']),
                'place' => $activityLinkData['place']
            ];
            if (array_key_exists('dateStart', $activityLinkData['date'])) {
                $activityEntityData['dateStart'] = $activityLinkData['date']['dateStart'];
            } else {
                $activityEntityData['dateStart'] = null;
            }
            if (array_key_exists('dateEnd', $activityLinkData['date'])) {
                $activityEntityData['dateEnd'] = $activityLinkData['date']['dateEnd'];
            } else {
                $activityEntityData['dateEnd'] = null;
            }
            return $activityEntityData;
        }
        return [];
    }

    /**
     * @param string $link
     * @return array
     */
    private function getActivityDataFromUrl($link)
    {
        $html = file_get_contents($link);
        $crawler = new Crawler($html);
        $place = $this->getActivityPlace(clone $crawler);
        $date = $this->getActivityDates(clone $crawler);

        return [
            'place' => $place,
            'date' => $date
        ];
    }

    /**
     * @param Crawler $crawler
     * @return string
     */
    private function getActivityPlace(Crawler $crawler)
    {
        $place = $crawler->filterXPath('//*[@class="adress"][2]');
        if ($place->getNode(0) != null) {
            $place = $place->getNode(0)->nodeValue;
            $place = preg_replace('/\([^)]+\)/', '', $place);
            return trim($place);
        }
        return null;
    }

    /**
     * @param Crawler $crawler
     * @return string
     */
    private function getActivityDates(Crawler $crawler)
    {
        $dateElement = $crawler->filterXPath('//*[@class="date"][2]');
        $timeElement = $crawler->filterXPath('//*[@class="hour"][2]');

        if ($dateElement->getNode(0) != null && $timeElement->getNode(0) != null) {
            $dateString = trim($dateElement->getNode(0)->nodeValue);
            $dates = explode('-', $dateString);

            if (count($dates) > 1) {
                return $this->getValidDates($dates);
            }

            $dateString = $this->getValidDateString($dateString);
            $timeString = trim($timeElement->getNode(0)->nodeValue);
            $times = explode('-', $timeString);
            if (count($times) > 1) {
                $timesData = [
                    'dateStart' => \DateTime::createFromFormat('j n Y G:i', trim($dateString) . $this->getFilteredTime(array_shift($times))),
                    'dateEnd' => \DateTime::createFromFormat('j n Y G:i', trim($dateString) . $this->getFilteredTime(array_shift($times)))
                ];
                return $timesData;
            } elseif (count(explode(',', $timeString)) > 1) {
                $times = explode(',', $timeString);
                $timesData = [
                    'dateStart' => \DateTime::createFromFormat('j n Y G:i', trim($dateString) . $this->getFilteredTime(array_shift($times))),
                    'dateEnd' => \DateTime::createFromFormat('j n Y G:i', trim($dateString) . $this->getFilteredTime(array_shift($times)))
                ];
                return $timesData;
            }

            return [
                'dateStart' => \DateTime::createFromFormat('j n Y G:i', trim($dateString) . $this->getFilteredTime($timeString))
            ];
        }

        return [];
    }

    /**
     * @param array $dates
     * @return array
     */
    private function getValidDates(array $dates)
    {
        $firstDate = array_shift($dates);
        $dateEnd = $this->getValidDateString(trim(array_shift($dates)));
        $dateEnd = \DateTime::createFromFormat('j n Y', trim($dateEnd));

        if (preg_match('/\p{L}/u', $firstDate)) {
            $dateStartString = $this->getValidDateString($firstDate . $dateEnd->format('Y'));
        } else {
            $dateStartString = $firstDate . $dateEnd->format('n Y');
        }
        $dateStart = \DateTime::createFromFormat('j n Y', trim($dateStartString));

        return [
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd
        ];
    }

    /**
     * @param $dateString
     * @return mixed
     */
    private function getValidDateString($dateString)
    {
        $monthsFirst = ['styczeń', 'luty', 'marzec', 'kwiecień', 'maj', 'czerwiec', 'lipiec', 'sierpień', 'wrzesień', 'październik', 'listopad', 'grudzień'];
        $monthsSecond = ['stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia'];

        $dateString = preg_replace('/\([^)]+\)/', '', $dateString);
        $dateString = preg_replace_callback('/(?:\d+(?:-\d+)?\s*)(?P<month>\p{L}+)(?:\s*)(\d{4})/u', function ($match) use ($dateString, $monthsFirst, $monthsSecond) {
            if (in_array($match['month'], $monthsFirst)) {
                $key = array_search($match['month'], $monthsFirst) + 1;
                return str_replace($match['month'], $key, $dateString);
            } elseif (in_array($match['month'], $monthsSecond)) {
                $key = array_search($match['month'], $monthsSecond) + 1;
                return str_replace($match['month'], $key, $dateString);
            }
            return $dateString;
        }, $dateString);

        return $dateString;
    }

    /**
     * @return array|mixed|string
     */
    public function getXmlDataAsArray()
    {
        $xmlContent = file_get_contents(self::RSA_ACTIVITIES_URL);
        $xmlEncoder = new XmlEncoder();
        return $xmlEncoder->decode($xmlContent, 'xml');
    }

    /**
     * @param $timeString
     * @return string
     */
    private function getFilteredTime($timeString)
    {
        return trim(str_replace('.', ':', $timeString));
    }
}