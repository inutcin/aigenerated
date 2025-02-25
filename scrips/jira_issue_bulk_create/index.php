<?php

require_once("parse_md.php");
require_once("jira.php");

// Create multiple issues
$fd = fopen("result.md", "at");
for ($i = 0; $i <= count($issues); $i++) {
    $issueData = $issueTemplate;
    $issue = $issues[$i];
    $issueData['fields']['summary'] = "Рефакторинг ".$issue["className"];
    $issueData['fields']['description'] = ".";
    $issueData['fields']['timetracking']['originalEstimate'] = $issue['estimatedTime'];


    $issueId = 1;
    $issueId = createIssue($jiraUrl, $username, $apiToken, $issueData);

    fwrite($fd, "| ".$issue["className"]." | "
        .$issue["startComplex"]." | "
        .$issue["targetComplex"]." | "
        .$issue["estimatedTime"]." | "
        .$jiraUrl."/browse/".$projectKey."-".$issueId."  |\n");
    sleep(1);
}
fclose($fd);
