<?php
// Set your API key here
$apiKey = 'YOUR_GEMINI_API_KEY'; // <--- IMPORTANT: REPLACE WITH YOUR API KEY

header('Content-Type: application/json');

// Get the request data
$data = json_decode(file_get_contents('php://input'), true);
$language = $data['language'] ?? 'English';
$prompt = $data['prompt'] ?? '';

if (empty($prompt)) {
    echo json_encode(['error' => 'Prompt is required.']);
    exit;
}

// Prepare the prompt for the Gemini API
$fullPrompt = "Write a short children's story in $language about $prompt. The story should be engaging, creative, and easy for a child to understand.";

// Set up the API request
$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $apiKey;
$postData = [
    'contents' => [
        [
            'parts' => [
                ['text' => $fullPrompt]
            ]
        ]
    ]
];

// Make the API call using cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Process the API response
if ($httpcode === 200) {
    $responseData = json_decode($response, true);
    $story = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, I could not generate a story at this time.';
    echo json_encode(['story' => $story]);
} else {
    echo json_encode(['error' => 'Failed to generate story. API response code: ' . $httpcode, 'response' => $response]);
}
