<?php


namespace juliocapuano\SimpleAntiSpam;

use juliocapuano\SimpleAntiSpam\Clients\StopForumSpamClient;

class SimpleAntiSpam
{
    protected static $mockClient = null;

    /**
     * @param $client
     */
    public static function setStopForumSpamClient($client)
    {
        self::$mockClient = $client;
    }

    public static function resetStopForumSpamClient()
    {
        self::$mockClient = null;
    }

    /**
     * @param $email
     * @return bool
     */
    public static function isSpamEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        if (self::$mockClient) {
            $info = self::$mockClient->get('email', $email);
        } else {
            $info = StopForumSpamClient::get('email', $email);
        }

        if (!$info) {
            return true;
        }
        
        if (!isset($info->appears)) {
            return true; // Treat as spam if 'appears' property is missing
        }

        return (boolean)$info->appears;
    }


    /**
     * @param bool|string $ip
     * @return bool|mixed
     */
    private static function getClientIp($ip = false)
    {
        if (!$ip) {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }
        return $ip;
    }

    /**
     * @param bool|string $ip
     * @return bool
     */
    public static function isSpamIp($ip = false)
    {
        $ip = self::getClientIp($ip);

        if (!$ip) {
            return true;
        }

        if (self::$mockClient) {
            $info = self::$mockClient->get('ip', $ip);
        } else {
            $info = StopForumSpamClient::get('ip', $ip);
        }

        if (!$info) {
            return true;
        }
        
        if (!isset($info->appears)) {
            return true; // Treat as spam if 'appears' property is missing
        }

        return (boolean)$info->appears;
    }

    /**
     * @param string $text
     * @return bool
     */
    public static function isUrlInText($text)
    {
        $match = [];
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#i', $text, $match);
        return count($match[0]) > 0;
    }


}
