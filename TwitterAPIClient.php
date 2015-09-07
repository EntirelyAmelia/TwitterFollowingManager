<?php

require_once('vendor/j7mbo/twitter-api-php/TwitterAPIExchange.php');

class TwitterAPIClient {
    private $creds = array();

    public function __construct($oauth, $userName) {
        $this->creds['oauth'] = $oauth;
        $this->creds['user'] = $userName;
    }

    public function getFollowingIds() {
        $url = 'https://api.twitter.com/1.1/friends/ids.json';
        $responseObj = $this->getRequest($url, array('screen_name=' => $this->creds['user']));

        return $responseObj->ids;
    }

    public function addListMembers($listId, $userIds) {
        $url = 'https://api.twitter.com/1.1/lists/members/create_all.json';
        $payload = array(
            'list_id' => $listId,
            'screen_name' => implode(',', $userIds),
        );

        return $this->postRequest($url, $payload);
    }

    private function postRequest($url, $params = array()) {
        $postFields = array();
        foreach($params as $key => $value) {
            $postFields[$key] = $value;
        }

        $twitter = new TwitterAPIExchange($this->creds['oauth']);
        $response = $twitter->buildOauth($url, 'POST')
            ->setPostfields($postFields)
            ->performRequest();

        return $response;
    }

    private function getRequest($url, $params = array()) {
        $getfield = '?';

        foreach($params as $key => $value) {
            $getfield .= '&' . $key . '=' . $value;
        }

        $twitter = new TwitterAPIExchange($this->creds['oauth']);
        $response = $twitter->setGetfield($getfield)
            ->buildOauth($url, 'GET')
            ->performRequest();

        return json_decode($response);
    }
}
