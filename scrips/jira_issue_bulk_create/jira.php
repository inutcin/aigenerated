<?php

include("./secret/jira.php");

// Issue template with additional fields
$issueTemplate = [
    'fields' => [
        'project' => [
            'key' => $projectKey
        ],
        'summary' => 'Sample Issue Summary',
        'description' => 'Description of the issue.',
        'issuetype' => [
            'name' => 'История' // Changed to Story
        ],
        'assignee' => [
            'name' => $username // Replace with the assignee's username
        ],
        'customfield_10101' => 'MP-8580', // Replace with your Epic Link field ID and Epic key
        'labels' => ['MpRefactoring2025'], // Replace with your desired tags
        'timetracking' => [
            'originalEstimate' => '1h' // Estimated time
        ]
    ]
];

// Function to create an issue in Jira
function createIssue($jiraUrl, $username, $apiToken, $issueData) {
    $url = $jiraUrl . '/rest/api/2/issue/';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$apiToken");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($issueData));
    $response = json_decode(curl_exec($ch));
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $self = (string)($response->self ?? "");
    
    if(!$self) { return 0; }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $self);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$apiToken");
    $issue = json_decode(curl_exec($ch));
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $key = $issue->key ?? 0;

    if(preg_match("#^\w+\-(\d+)$#", $key, $m)) {
        return $m[1];
    }

    return 0;
}