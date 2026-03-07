<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course'])) {
    $courses = $_POST['course'];
    $credits = $_POST['credits'];
    $grades = $_POST['grade'];

    $totalPoints = 0;
    $totalCredits = 0;
    $tableHtml = "<table border='1' style='width:100%; border-collapse:collapse;'>
                    <tr><th>المادة</th><th>الساعات</th><th>النقاط</th></tr>";

    for ($i = 0; $i < count($courses); $i++) {
        $cr = floatval($credits[$i]);
        $gr = floatval($grades[$i]);
        $pts = $cr * $gr;

        $totalPoints += $pts;
        $totalCredits += $cr;

        $tableHtml .= "<tr>
                        <td>" . htmlspecialchars($courses[$i]) . "</td>
                        <td>$cr</td>
                        <td>$pts</td>
                      </tr>";
    }
    $tableHtml .= "</table>";

    if ($totalCredits > 0) {
        $gpa = $totalPoints / $totalCredits;
        
        // تحديد التقدير
        if ($gpa >= 3.7) $interpretation = "Distinction";
        elseif ($gpa >= 3.0) $interpretation = "Merit";
        elseif ($gpa >= 2.0) $interpretation = "Pass";
        else $interpretation = "Fail";

        $message = "Your GPA is " . number_format($gpa, 2) . " ($interpretation).";

        // إرسال الرد بصيغة JSON
        echo json_encode([
            'success' => true,
            'gpa' => number_format($gpa, 2),
            'message' => $message,
            'tableHtml' => $tableHtml
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'بيانات غير صالحة']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'لم يتم استلام بيانات']);
}
exit;
?>