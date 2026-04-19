$conn = new mysqli("localhost", "root", "", "gpa_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
<?php
header('Content-Type: application/json');

if (isset($_POST['course'])) {
    $courses = $_POST['course'];
    $credits = $_POST['credits'];
    $grades = $_POST['grade'];
    
    $totalPoints = 0;
    $totalCredits = 0;

    $tableHtml = "<table class='table table-sm table-hover bg-white mt-3'>
                    <thead class='thead-light'>
                        <tr><th>Course</th><th>Credits</th><th>Points</th></tr>
                    </thead><tbody>";

    for ($i = 0; $i < count($courses); $i++) {
        $cName = htmlspecialchars($courses[$i]);
        $cr = floatval($credits[$i]);
        $g = floatval($grades[$i]);
        
        $totalPoints += ($cr * $g);
        $totalCredits += $cr;
        $tableHtml .= "<tr><td>{$cName}</td><td>{$cr}</td><td>{$g}</td></tr>";
    }
    $tableHtml .= "</tbody></table>";

    if ($totalCredits > 0) {
        $gpa = $totalPoints / $totalCredits;
        
        // التفسير
        if ($gpa >= 3.7) $interpretation = "Distinction";
        elseif ($gpa >= 3.0) $interpretation = "Merit";
        elseif ($gpa >= 2.0) $interpretation = "Pass";
        else $interpretation = "Fail";

        echo json_encode([
            'success' => true,
            'gpa' => $gpa,
            'message' => "Your GPA is: " . number_format($gpa, 2) . " ($interpretation)",
            'tableHtml' => $tableHtml
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No credits found.']);
    }
}
exit;