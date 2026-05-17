<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Only admin can access
requireAdmin();

// Get all courses from database
$result = $conn->query("SELECT * FROM courses ORDER BY course_code");

// Create XML structure
$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><courses></courses>');

while ($course = $result->fetch_assoc()) {
    $course_xml = $xml->addChild('course');
    $course_xml->addChild('course_code', htmlspecialchars($course['course_code']));
    $course_xml->addChild('course_name', htmlspecialchars($course['course_name']));
    $course_xml->addChild('description', htmlspecialchars($course['description']));
    $course_xml->addChild('instructor', htmlspecialchars($course['instructor']));
    $course_xml->addChild('capacity', $course['capacity']);
    $course_xml->addChild('semester', htmlspecialchars($course['semester']));
}

// Format the XML
$dom = dom_import_simplexml($xml)->ownerDocument;
$dom->formatOutput = true;

// Set headers to download as file
header('Content-Type: application/xml');
header('Content-Disposition: attachment; filename="courses_export_' . date('Y-m-d') . '.xml"');

echo $dom->saveXML();
exit();
?>