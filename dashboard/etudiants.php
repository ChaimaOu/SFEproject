<?php
// Start session
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is properly authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || !isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    // Not authenticated, redirect to login page
    header("Location: ../index.php");
    exit();
}

// Get user information
$email = $_SESSION['email'];
$user_id = $_SESSION['user_id'];

// Sample student data
// In a real application, you would fetch this from your database
$students = [
    [
        'id' => 1,
        'photo' => 'https://randomuser.me/api/portraits/women/44.jpg',
        'first_name' => 'Marie',
        'last_name' => 'Dupont',
        'email' => 'marie.dupont@example.com',
        'phone' => '+33 6 12 34 56 78',
        'program' => 'Business Administration',
        'enrollment_date' => '2023-09-15',
        'status' => 'Active',
        'payment_status' => 'Paid',
        'progress' => 75
    ],
    [
        'id' => 2,
        'photo' => 'https://randomuser.me/api/portraits/men/32.jpg',
        'first_name' => 'Thomas',
        'last_name' => 'Martin',
        'email' => 'thomas.martin@example.com',
        'phone' => '+33 6 23 45 67 89',
        'program' => 'Computer Science',
        'enrollment_date' => '2023-08-20',
        'status' => 'Active',
        'payment_status' => 'Partial',
        'progress' => 60
    ],
    [
        'id' => 3,
        'photo' => 'https://randomuser.me/api/portraits/women/68.jpg',
        'first_name' => 'Sophie',
        'last_name' => 'Bernard',
        'email' => 'sophie.bernard@example.com',
        'phone' => '+33 6 34 56 78 90',
        'program' => 'Marketing',
        'enrollment_date' => '2023-10-05',
        'status' => 'Active',
        'payment_status' => 'Paid',
        'progress' => 45
    ],
    [
        'id' => 4,
        'photo' => 'https://randomuser.me/api/portraits/men/75.jpg',
        'first_name' => 'Lucas',
        'last_name' => 'Petit',
        'email' => 'lucas.petit@example.com',
        'phone' => '+33 6 45 67 89 01',
        'program' => 'Graphic Design',
        'enrollment_date' => '2023-07-10',
        'status' => 'On Leave',
        'payment_status' => 'Paid',
        'progress' => 80
    ],
    [
        'id' => 5,
        'photo' => 'https://randomuser.me/api/portraits/women/90.jpg',
        'first_name' => 'Emma',
        'last_name' => 'Leroy',
        'email' => 'emma.leroy@example.com',
        'phone' => '+33 6 56 78 90 12',
        'program' => 'Psychology',
        'enrollment_date' => '2023-09-01',
        'status' => 'Active',
        'payment_status' => 'Unpaid',
        'progress' => 30
    ],
    [
        'id' => 6,
        'photo' => 'https://randomuser.me/api/portraits/men/41.jpg',
        'first_name' => 'Hugo',
        'last_name' => 'Moreau',
        'email' => 'hugo.moreau@example.com',
        'phone' => '+33 6 67 89 01 23',
        'program' => 'Engineering',
        'enrollment_date' => '2023-08-15',
        'status' => 'Active',
        'payment_status' => 'Paid',
        'progress' => 65
    ],
    [
        'id' => 7,
        'photo' => 'https://randomuser.me/api/portraits/women/33.jpg',
        'first_name' => 'Camille',
        'last_name' => 'Fournier',
        'email' => 'camille.fournier@example.com',
        'phone' => '+33 6 78 90 12 34',
        'program' => 'Languages',
        'enrollment_date' => '2023-10-10',
        'status' => 'Inactive',
        'payment_status' => 'Refunded',
        'progress' => 0
    ],
    [
        'id' => 8,
        'photo' => 'https://randomuser.me/api/portraits/men/56.jpg',
        'first_name' => 'Louis',
        'last_name' => 'Girard',
        'email' => 'louis.girard@example.com',
        'phone' => '+33 6 89 01 23 45',
        'program' => 'Finance',
        'enrollment_date' => '2023-09-20',
        'status' => 'Active',
        'payment_status' => 'Paid',
        'progress' => 50
    ],
    [
        'id' => 9,
        'photo' => 'https://randomuser.me/api/portraits/women/22.jpg',
        'first_name' => 'Léa',
        'last_name' => 'Rousseau',
        'email' => 'lea.rousseau@example.com',
        'phone' => '+33 6 90 12 34 56',
        'program' => 'Art History',
        'enrollment_date' => '2023-08-05',
        'status' => 'Active',
        'payment_status' => 'Partial',
        'progress' => 70
    ],
    [
        'id' => 10,
        'photo' => 'https://randomuser.me/api/portraits/men/22.jpg',
        'first_name' => 'Gabriel',
        'last_name' => 'Mercier',
        'email' => 'gabriel.mercier@example.com',
        'phone' => '+33 6 01 23 45 67',
        'program' => 'Physics',
        'enrollment_date' => '2023-07-25',
        'status' => 'Active',
        'payment_status' => 'Paid',
        'progress' => 85
    ]
];

// Programs list for filter and new student form
$programs = [
    'Business Administration',
    'Computer Science',
    'Marketing',
    'Graphic Design',
    'Psychology',
    'Engineering',
    'Languages',
    'Finance',
    'Art History',
    'Physics',
    'Mathematics',
    'Biology',
    'Chemistry',
    'Economics',
    'Law'
];

// Status options
$statusOptions = ['Active', 'Inactive', 'On Leave', 'Graduated', 'Suspended'];

// Payment status options
$paymentStatusOptions = ['Paid', 'Partial', 'Unpaid', 'Refunded', 'Scholarship'];

// Handle search and filters
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$programFilter = isset($_GET['program']) ? $_GET['program'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$paymentFilter = isset($_GET['payment']) ? $_GET['payment'] : '';

// Filter students based on search and filters
$filteredStudents = $students;

if (!empty($searchTerm)) {
    $filteredStudents = array_filter($filteredStudents, function($student) use ($searchTerm) {
        return (
            stripos($student['first_name'], $searchTerm) !== false ||
            stripos($student['last_name'], $searchTerm) !== false ||
            stripos($student['email'], $searchTerm) !== false
        );
    });
}

if (!empty($programFilter)) {
    $filteredStudents = array_filter($filteredStudents, function($student) use ($programFilter) {
        return $student['program'] === $programFilter;
    });
}

if (!empty($statusFilter)) {
    $filteredStudents = array_filter($filteredStudents, function($student) use ($statusFilter) {
        return $student['status'] === $statusFilter;
    });
}

if (!empty($paymentFilter)) {
    $filteredStudents = array_filter($filteredStudents, function($student) use ($paymentFilter) {
        return $student['payment_status'] === $paymentFilter;
    });
}

// Pagination
$studentsPerPage = 5;
$totalStudents = count($filteredStudents);
$totalPages = ceil($totalStudents / $studentsPerPage);
$currentPage = isset($_GET['page']) ? max(1, min($totalPages, intval($_GET['page']))) : 1;
$offset = ($currentPage - 1) * $studentsPerPage;

// Get students for current page
$paginatedStudents = array_slice($filteredStudents, $offset, $studentsPerPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YOOL - Étudiants</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="etudiants.css">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../yoolLogo.png" alt="YOOL Logo" class="logo">
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="./dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a>
                    </li>
                    <li class="active">
                        <a href="./etudiants.php"><i class="fas fa-user-graduate"></i> <span>Étudiants</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-book"></i> <span>Courses</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-handshake"></i> <span>Partenaires</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-file-contract"></i> <span>Contrats</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-file-invoice-dollar"></i> <span>Factures</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-chart-line"></i> <span>Analytics</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-cog"></i> <span>Settings</span></a>
                    </li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="../logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="header-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                <div class="header-user">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="user-info">
                        <span class="user-name"><?php echo htmlspecialchars($email); ?></span>
                        <div class="user-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Students Content -->
            <div class="students-content">
                <div class="students-header">
                    <div>
                        <h1>Étudiants</h1>
                        <p class="subtitle">Manage student accounts and information</p>
                    </div>
                    <button class="add-student-btn" id="addStudentBtn">
                        <i class="fas fa-plus"></i> Add Student
                    </button>
                </div>

                <!-- Filters and Search -->
                <div class="filters-container">
                    <form action="" method="GET" class="filters-form">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Search students..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                        </div>
                        <div class="filters">
                            <div class="filter">
                                <select name="program" id="programFilter">
                                    <option value="">All Programs</option>
                                    <?php foreach ($programs as $program): ?>
                                    <option value="<?php echo $program; ?>" <?php echo $programFilter === $program ? 'selected' : ''; ?>>
                                        <?php echo $program; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="filter">
                                <select name="status" id="statusFilter">
                                    <option value="">All Status</option>
                                    <?php foreach ($statusOptions as $status): ?>
                                    <option value="<?php echo $status; ?>" <?php echo $statusFilter === $status ? 'selected' : ''; ?>>
                                        <?php echo $status; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="filter">
                                <select name="payment" id="paymentFilter">
                                    <option value="">All Payments</option>
                                    <?php foreach ($paymentStatusOptions as $payment): ?>
                                    <option value="<?php echo $payment; ?>" <?php echo $paymentFilter === $payment ? 'selected' : ''; ?>>
                                        <?php echo $payment; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="filter-btn">Apply Filters</button>
                            <a href="etudiants.php" class="reset-btn">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Students Table -->
                <div class="students-table-container">
                    <table class="students-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Program</th>
                                <th>Enrollment Date</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Progress</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($paginatedStudents)): ?>
                            <tr>
                                <td colspan="7" class="no-results">No students found matching your criteria.</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($paginatedStudents as $student): ?>
                            <tr>
                                <td>
                                    <div class="student-info">
                                        <img src="<?php echo $student['photo']; ?>" alt="<?php echo $student['first_name'] . ' ' . $student['last_name']; ?>" class="student-avatar">
                                        <div>
                                            <div class="student-name"><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></div>
                                            <div class="student-email"><?php echo $student['email']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo $student['program']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($student['enrollment_date'])); ?></td>
                                <td>
                                    <span class="status-badge <?php echo strtolower($student['status']); ?>">
                                        <?php echo $student['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="payment-badge <?php echo strtolower($student['payment_status']); ?>">
                                        <?php echo $student['payment_status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="progress-container">
                                        <div class="progress-bar" style="width: <?php echo $student['progress']; ?>%"></div>
                                        <span class="progress-text"><?php echo $student['progress']; ?>%</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn view-btn" data-id="<?php echo $student['id']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn edit-btn" data-id="<?php echo $student['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn delete-btn" data-id="<?php echo $student['id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <div class="pagination-info">
                        Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $studentsPerPage, $totalStudents); ?> of <?php echo $totalStudents; ?> students
                    </div>
                    <div class="pagination-controls">
                        <?php if ($currentPage > 1): ?>
                        <a href="?page=<?php echo $currentPage - 1; ?>&search=<?php echo urlencode($searchTerm); ?>&program=<?php echo urlencode($programFilter); ?>&status=<?php echo urlencode($statusFilter); ?>&payment=<?php echo urlencode($paymentFilter); ?>" class="pagination-btn">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $startPage + 4);
                        if ($endPage - $startPage < 4) {
                            $startPage = max(1, $endPage - 4);
                        }
                        ?>
                        
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchTerm); ?>&program=<?php echo urlencode($programFilter); ?>&status=<?php echo urlencode($statusFilter); ?>&payment=<?php echo urlencode($paymentFilter); ?>" class="pagination-btn <?php echo $i === $currentPage ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?php echo $currentPage + 1; ?>&search=<?php echo urlencode($searchTerm); ?>&program=<?php echo urlencode($programFilter); ?>&status=<?php echo urlencode($statusFilter); ?>&payment=<?php echo urlencode($paymentFilter); ?>" class="pagination-btn">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Add/Edit Student Modal -->
    <div class="modal" id="studentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Student</h2>
                <button class="close-btn" id="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="studentForm">
                    <input type="hidden" id="studentId" value="">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" name="lastName" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="program">Program</label>
                            <select id="program" name="program" required>
                                <?php foreach ($programs as $program): ?>
                                <option value="<?php echo $program; ?>"><?php echo $program; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="enrollmentDate">Enrollment Date</label>
                            <input type="date" id="enrollmentDate" name="enrollmentDate" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" required>
                                <?php foreach ($statusOptions as $status): ?>
                                <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="paymentStatus">Payment Status</label>
                            <select id="paymentStatus" name="paymentStatus" required>
                                <?php foreach ($paymentStatusOptions as $payment): ?>
                                <option value="<?php echo $payment; ?>"><?php echo $payment; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="progress">Progress (%)</label>
                        <input type="range" id="progress" name="progress" min="0" max="100" value="0">
                        <div class="range-value"><span id="progressValue">0</span>%</div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
                        <button type="submit" class="save-btn">Save Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Student Modal -->
    <div class="modal" id="viewStudentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Student Details</h2>
                <button class="close-btn" id="closeViewModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="student-profile">
                    <div class="student-profile-header">
                        <img src="/placeholder.svg" alt="" id="viewStudentPhoto" class="profile-photo">
                        <div class="profile-info">
                            <h3 id="viewStudentName"></h3>
                            <p id="viewStudentProgram"></p>
                            <div class="profile-badges">
                                <span class="status-badge" id="viewStudentStatus"></span>
                                <span class="payment-badge" id="viewStudentPayment"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-progress">
                        <h4>Course Progress</h4>
                        <div class="progress-container">
                            <div class="progress-bar" id="viewStudentProgressBar"></div>
                            <span class="progress-text" id="viewStudentProgress"></span>
                        </div>
                    </div>
                    
                    <div class="profile-details">
                        <div class="detail-group">
                            <div class="detail-label">Email</div>
                            <div class="detail-value" id="viewStudentEmail"></div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Phone</div>
                            <div class="detail-value" id="viewStudentPhone"></div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Enrollment Date</div>
                            <div class="detail-value" id="viewStudentEnrollment"></div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Student ID</div>
                            <div class="detail-value" id="viewStudentId"></div>
                        </div>
                    </div>
                    
                    <div class="profile-actions">
                        <button class="edit-profile-btn" id="editFromViewBtn">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                        <button class="delete-profile-btn" id="deleteFromViewBtn">
                            <i class="fas fa-trash"></i> Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content delete-modal">
            <div class="modal-header">
                <h2>Delete Student</h2>
                <button class="close-btn" id="closeDeleteModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="delete-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p class="delete-message">Are you sure you want to delete this student? This action cannot be undone.</p>
                <div class="delete-student-info">
                    <img src="/placeholder.svg" alt="" id="deleteStudentPhoto" class="delete-student-photo">
                    <div>
                        <div class="delete-student-name" id="deleteStudentName"></div>
                        <div class="delete-student-email" id="deleteStudentEmail"></div>
                    </div>
                </div>
                <div class="delete-actions">
                    <button class="cancel-delete-btn" id="cancelDeleteBtn">Cancel</button>
                    <button class="confirm-delete-btn" id="confirmDeleteBtn">Delete Student</button>
                </div>
            </div>
        </div>
    </div>

    <script src="dashboard.js"></script>
    <script src="etudiants.js"></script>
</body>
</html>
