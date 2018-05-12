<?php

function siteInsert($site)
{
    $pdo = dbconn();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO sites (site, date) values(?, ?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($site, date('Y-m-d H:i:s')));
}

function getSite($link)
{
    $pdo = dbconn();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM sites where site = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($link));
    return $data = $q->fetch(PDO::FETCH_ASSOC);
}

function linkInsert($data, $link)
{
    $pdo = dbconn();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO sitelinks (site_id, link) values(?, ?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($data['site_id'], $link));
}

?>