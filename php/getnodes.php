<?php
// called by index.php where $serialized_data is to be parsed as js array.
// get route's nodes' lat-lng (curl request the routing machine, python-parse and json.dumps() to php variable

$osrmParser = 'py/osrmParser.py';
$pythonScript = 'python3 ' . $osrmParser . ' json/full_steps.json';
$serialized_data = shell_exec($pythonScript);
echo $serialized_data;
?>
