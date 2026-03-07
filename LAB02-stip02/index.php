<?php
// تعريف متغيرات النتائج لتجنب الأخطاء عند أول تحميل للصفحة
$result = "";
$tableHtml = "";

// التأكد من أن الملف استقبل بيانات عبر POST (عند الضغط على Calculate)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    [span_5](start_span)// استقبال المصفوفات من النموذج[span_5](end_span)
    $courses = $_POST['course'] ?? [];
    $credits = $_POST['credits'] ?? [];
    $grades  = $_POST['grade'] ?? [];

    $totalPoints = 0;
    $totalCredits = 0;

    [span_6](start_span)// بدء بناء جدول الملخص[span_6](end_span)
    $tableHtml = "<table>";
    $tableHtml .= "<tr><th>Course</th><th>Credits</th><th>Grade</th><th>Grade Points</th></tr>";

    for ($i = 0; $i < count($courses); $i++) {
        $courseName = htmlspecialchars($courses[$i]);
        $cr = floatval($credits[$i]);
        $g  = floatval($grades[$i]);

        if ($cr <= 0) continue; [span_7](start_span)// تخطي الحقول غير الصالحة[span_7](end_span)

        $pts = $cr * $g;
        $totalPoints += $pts;
        $totalCredits += $cr;

        $tableHtml .= "<tr><td>$courseName</td><td>$cr</td><td>$g</td><td>$pts</td></tr>";
    }
    $tableHtml .= "</table>";

    [span_8](start_span)// حساب المعدل النهائي وتحديد التقدير[span_8](end_span)
    if ($totalCredits > 0) {
        $gpa = $totalPoints / $totalCredits;
        
        [span_9](start_span)// منطق التقدير حسب متطلبات اللاب[span_9](end_span)
        if ($gpa >= 3.7) {
            $interpretation = "Distinction";
        } elseif ($gpa >= 3.0) {
            $interpretation = "Merit";
        } elseif ($gpa >= 2.0) {
            $interpretation = "Pass";
        } else {
            $interpretation = "Fail";
        }

        $result = "Your GPA is " . number_format($gpa, 2) . " ($interpretation).";
    } else {
        [span_10](start_span)$result = "No valid courses entered.";[span_10](end_span)
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GPA Calculator - Step 2</title>
    <link rel="stylesheet" href="style.css"> <script src="script.js"></script> </head>
<body>
    <h1>GPA Calculator</h1>

    <?php if ($result != ""): ?>
        <div id="result-section">
            <?php echo $tableHtml; ?>
            <p><strong><?= $result ?></strong></p>
        </div>
    <?php endif; ?>

    <form action="" method="post" onsubmit="return validateForm();">
        <div id="courses">
            <div class="course-row">
                <label>Course: </label>
                <input type="text" name="course[]" placeholder="e.g. Mathematics" required>
                
                <label>Credits: </label>
                <input type="number" name="credits[]" placeholder="e.g. 3" min="1" required>
                
                <label>Grade: </label>
                <select name="grade[]">
                    <option value="4.0">A</option>
                    <option value="3.0">B</option>
                    <option value="2.0">C</option>
                    <option value="1.0">D</option>
                    <option value="0.0">F</option>
                </select>
            </div>
        </div>
        <br>
        <button type="button" onclick="addCourse()">+ Add Course</button>
        <br><br>
        <input type="submit" value="Calculate GPA">
    </form>
</body>
</html>