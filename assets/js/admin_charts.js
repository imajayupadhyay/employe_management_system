// Function to Load Attendance Chart
function loadAttendanceChart(attendance) {
    var ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Present', 'Absent'],
            datasets: [{
                data: [attendance, 30 - attendance],
                backgroundColor: ['#007bff', '#ffc107']
            }]
        }
    });
}

// Function to Load Task Chart
function loadTaskChart(tasks) {
    var ctx = document.getElementById('taskChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Assigned Tasks'],
            datasets: [{
                label: 'Tasks',
                data: [tasks],
                backgroundColor: '#28a745'
            }]
        }
    });
}
