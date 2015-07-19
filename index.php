<?php

require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

use Aws\DynamoDb\DynamoDbClient;

$client = DynamoDbClient::factory(
    array(
        'key' => 'IAM_KEY',
        'secret' => 'IAM_SECRET',
        'region' => 'eu-west-1',
    )
);
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token, $access_token_secret);

$favourites = $connection->get("favorites/list", array('count' => 200));

foreach ($favourites as $favourite) {

    $client->putItem(array(
        "TableName" => 'twitter_favourites',
        "Item" => array(
            "id" => array('N' => $favourite->id),
            "text" => array('S' => $favourite->text),
            "source" => array('S' => $favourite->source),
            "created_at" => array('N' => strtotime($favourite->created_at)),
            "user_id" => array('N' => $favourite->user->id),
            "favorite_count" => array('N' => $favourite->favorite_count),
            "retweet_count" => array('N' => $favourite->retweet_count),
        )
    ));

}