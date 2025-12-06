<?php
function createProfiles($accountId) {
    $profiles = [];
    $dir = __DIR__ . '/../Config/DefaultProfiles';

    foreach (scandir($dir) as $fileName) {
        if ($fileName === '.' || $fileName === '..') continue;

        $filePath = "$dir/$fileName";
        $profile = json_decode(file_get_contents($filePath), true);

        $profile['accountId'] = $accountId;
        $profile['created'] = date('c'); // ISO 8601
        $profile['updated'] = date('c');

        $profiles[$profile['profileId']] = $profile;
    }

    return $profiles;
}

function validateProfile($profileId, $profiles) {
    try {
        if (!isset($profiles['profiles'][$profileId]) || !$profileId) {
            throw new Exception("Invalid profile/profileId");
        }
    } catch (Exception $e) {
        return false;
    }

    return true;
}