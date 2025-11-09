<?php
include_once "inc.php";
require_once 'Agora/RtcTokenBuilder.php';

/**
 * Generate an Agora RTC token for a given user and channel.
 *
 * @param bool $isHost Determines if the user is a host or attendee
 * @param string|null $channelName Channel name (optional)
 * @param string $agoraAppID Your Agora App ID
 * @param string $agoraCertificate Your Agora App Certificate
 * @param int|string $userID Unique ID of the user (int recommended for UID-based generation)
 * @return string The generated RTC token
 */
function agora_token_builder(bool $isHost = true, ?string $channelName = null, string $agoraAppID, string $agoraCertificate, $userID): string
{
    // Generate a random channel name if not provided
    $channelName = $channelName ?: generate_random_channel_name();

    // Define Agora user role
    $role = $isHost ? RtcTokenBuilder::RolePublisher : RtcTokenBuilder::RoleAttendee;

    // Token expiration time (in seconds)
    $expireTimeInSeconds = 3600;

    // Current UTC timestamp
    $currentTimestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
    $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

    // Build and return token using UID
    return RtcTokenBuilder::buildTokenWithUid(
        $agoraAppID,
        $agoraCertificate,
        $channelName,
        (int) $userID,
        $role,
        $privilegeExpiredTs
    );
}

/**
 * Generate a hashed channel name.
 * Useful when a random unique channel name is needed.
 *
 * @return string
 */
function generate_random_channel_name(): string
{
    return md5(generate_random_seed());
}

/**
 * Generate a pseudo-random numeric seed.
 * Used for channel name hashing.
 *
 * @return int
 */
function generate_random_seed(): int
{
    return time() * rand(1000, 99999);
}
?>