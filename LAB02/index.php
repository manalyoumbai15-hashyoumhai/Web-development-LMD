<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GPA Calculator - AJAX Version</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>GPA Calculator</h1>

        <div id="result-area" style="display:none;">
            <div id="table-container"></div>
            <p id="gpa-message"></p>
        </div>

        <form id="gpa-form">
            <div id="courses-list">
                <div class="course-row">
                    <input type="text" name="course[]" placeholder="Course Name" required>
                    <input type="number" name="credits[]" placeholder="Credits" min="1" step="0.5" required>
                    <select name="grade[]">
                        <option value="4.0">A / A+</option>
                        <option value="3.0">B</option>
                        <option value="2.0">C</option>
                        <option value="1.0">D</option>
                        <option value="0.0">F</option>
                    </select>
                </div>
            </div>
            
            <div class="actions">
                <button type="button" id="add-course-btn">Add Course</button>
                <button type="submit" id="submit-btn">Calculate GPA</button>
            </div>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>